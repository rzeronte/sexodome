<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\Logpublish;
use App\Model\SceneCategory;
use App\Model\SceneClick;
use App\Model\SceneTranslation;
use App\Model\TagTranslation;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Language;
use App\Model\Scene;
use App\Model\Tag;
use App\Model\Host;
use App\Model\SceneTag;
use App\Model\TagClick;
use DB;

class rZeBotSyncronizer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:syncronize {database}
                                {--sync_scenes=false : Determine if sync scenes table}
                                {--sync_translations=false : Determine if sync translation}
                                {--sync_tags=false : Determine if sync tags}
                                {--sync_categories=false : Determine if sync categories}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync remote databases';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $database = $this->argument('database');

        $sync_scenes = $this->option('sync_scenes');
        $sync_translations = $this->option('sync_translations');
        $sync_tags = $this->option('sync_tags');
        $sync_categories = $this->option('sync_categories');

        echo "Syncronize from " . $database . PHP_EOL;

        $remoteIds = Scene::getRemoteActiveScenesIdsFor($database);
        $total = count($remoteIds);
        $i=0;
        foreach($remoteIds as $id) {
            $scene = Scene::find($id);
            if ($scene) {
                echo "[ " . number_format(($i*100)/ $total, 0) ."% ".date('Y-m-d H:i:s')."] " . $scene->id . PHP_EOL;
                $i++;

                // scenes data
                if ($sync_scenes == 'true') {
                    $sql_update = "UPDATE scenes SET status=".$scene->status . ",
                           preview = '".$scene->preview."',
                           thumbs = '".$scene->thumbs."',
                           iframe = '".$scene->iframe."',
                           status = ".$scene->status.",
                           rate = ".$scene->rate."
                            WHERE id=".$scene->id;

                    DB::connection($database)->update($sql_update);
                } else {
                    echo "[JUMPING SCENES]".PHP_EOL;
                }

                // scene tags
                if ($sync_tags == 'true') {
                    $this->syncSceneTags($database, $scene);
                } else {
                    echo "[JUMPING TAGS]".PHP_EOL;
                }

                // scene categories
                if ($sync_categories == 'true') {
                    $this->syncSceneCategories($database, $scene);
                } else {
                    echo "[JUMPING CATEGORIES]".PHP_EOL;
                }

                // scene translations
                if ($sync_translations == 'true') {
                    $this->syncSceneTranslations($database, $scene);
                } else {
                    echo "[JUMPING TRANSLATIONS]".PHP_EOL;
                }
            }
        }
    }


    public function syncSceneTranslations($database, $scene)
    {
        $languages = Language::all();

        foreach ($languages as $lang) {
            $translation = $scene->translations()->where('language_id', $lang->id)->first();

            $sql_update = "UPDATE scene_translations SET
                        scene_id=" . $scene->id . ",
                        language_id=" . $lang->id. ",
                        title=" . DB::connection()->getPdo()->quote((($translation->title != "") ? $translation->title : "")). ",
                        permalink=" . DB::connection()->getPdo()->quote((($translation->permalink != "") ? $translation->permalink : "")) . ",
                        description=" . DB::connection()->getPdo()->quote((($translation->description != "") ? $translation->description : "")) . "
                        where id=" . $translation->id;
            DB::connection($database)->update($sql_update);
        }
    }

    public function syncSceneTags($database, $scene)
    {
        $tagsScene = $scene->tags()->get();

        DB::connection($database)->table('scene_tag')->where('scene_id', $scene->id)->delete();

        foreach ($tagsScene as $tag) {
            $scene_tag = SceneTag::where('scene_id', $scene->id)->where('tag_id', $tag->id)->first();
            $sql_insert = "insert into scene_tag (id, tag_id, scene_id) values ($scene_tag->id, $tag->id, $scene->id)";
            DB::connection($database)->insert($sql_insert);
        }
    }

    public function syncSceneCategories($database, $scene)
    {
        $categoriesScene = $scene->categories()->get();

        DB::connection($database)->table('scene_category')->where('scene_id', $scene->id)->delete();

        foreach ($categoriesScene as $category) {
            $scene_category = SceneCategory::where('scene_id', $scene->id)->where('category_id', $category->id)->first();
            $sql_insert = "insert ignore into scene_category (id, category_id, scene_id) values ($scene_category->id, $category->id, $scene->id)";
            DB::connection($database)->insert($sql_insert);
        }
    }

    public function syncTags($database)
    {
        $languages = Language::all();
        $tags = Tag::all();

        $total = count($tags);
        $i=0;
        foreach ($tags as $tag) {
            echo "[ " . number_format(($i*100)/ $total, 0) ."% ] ";
            $i++;

            $sql = "SELECT * FROM tags WHERE id=".$tag->id;
            $domain_tag= DB::connection($database)->select($sql);

            if (!$domain_tag) {
                echo "Añadimos el tag " . $tag->id. ": ".PHP_EOL;
                $values = array(
                    $tag->id,
                    $tag->status,
                    date("Y-m-d H:i:s"),
                    date("Y-m-d H:i:s"),
                );

                DB::connection($database)->insert('insert into tags (id, status, created_at, updated_at) values (?, ?, ?, ?)', $values);

                foreach ($languages as $lang) {
                    $translation = $tag->translations()->where('language_id', $lang->id)->first();

                    $values = array(
                        $translation->id,
                        $tag->id,
                        $translation->name,
                        $translation->permalink,
                        $lang->id,
                    );

                    echo $translation->name."|";

                    DB::connection($database)->insert('insert into tag_translations (id, tag_id, name, permalink, language_id) values (?, ?, ?, ?, ?)', $values);
                }
            } else {
                echo "Actualizando el tag " . $tag->id . ": ";
                $sql_update = "UPDATE tags SET status=".$tag->status . " WHERE id=" . $tag->id;
                DB::connection($database)->update($sql_update);

                foreach ($languages as $lang) {
                    $translation = $tag->translations()->where('language_id', $lang->id)->first();

                    echo $translation->name."|";

                    $sql_update = "UPDATE tag_translations SET
                            tag_id=" . $tag->id . ",
                            name='" . $translation->name . "',
                            permalink='" . $translation->permalink . "',
                            language_id=" . $lang->id. " where id=" . $translation->id;

                    DB::connection($database)->update($sql_update);
                }
            }
            echo PHP_EOL;
        }
    }
}
