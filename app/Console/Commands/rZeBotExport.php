<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
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

class rZeBotExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:export {database} {tags}
                                {--truncatedatabase=false : Truncate origin before dump}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export to other databases';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $database = $this->argument('database');
        $tags_cmd = $this->argument('tags');
        $truncatedatabase = $this->option('truncatedatabase');

        echo "Export to " . $database . PHP_EOL;

        // escape/trim tags
        $requiredTags = [];
        foreach(explode(",", $tags_cmd) as $tag){
            $requiredTags[] = trim(utf8_encode($tag));
        }

        if ($truncatedatabase !== 'false') {
            echo "[TRUNCATE] From " . $database.PHP_EOL;
            DB::connection($database)->table('scene_tag')->delete();
            DB::connection($database)->table('scenes_clicks')->delete();
            DB::connection($database)->table('tags')->delete();
            DB::connection($database)->table('scenes')->delete();
            DB::connection($database)->table('language_tag')->delete();
        }

        $this->syncTags($database);
        $this->syncScenes($database, $requiredTags);
    }

    public function checkTagsRequired($sceneTags, $requiredTags)
    {
        $find = false;
        foreach($sceneTags as $sceneTag) {
            $translation = $sceneTag->translations('language_id', 2)->first();
            if (in_array($translation->name, $requiredTags)) {
                $find = true;
                break;
            }
        }

        if ($find) {
            return true;
        }

        return false;
    }

    public function syncScenes($database, $requiredTags)
    {
        $scenes = Scene::all();
        $languages = Language::all();

        foreach ($scenes as $scene) {
            $check_tags = $this->checkTagsRequired($scene->tags()->get(), $requiredTags);

            if ($check_tags) {
                $sql = "SELECT * FROM scenes WHERE id = ".$scene->id;
                $domain_scene = DB::connection($database)->select($sql);

                if (!$domain_scene) {
                    echo "[SCENE] Añadimos la scene " . $scene->id.PHP_EOL;
                    $values = array(
                        $scene->id,
                        $scene->preview,
                        $scene->thumbs,
                        $scene->iframe,
                        $scene->status,
                        $scene->duration,
                        $scene->rate,
                        $scene->channel_id,
                        date("Y-m-d H:i:s"),
                        date("Y-m-d H:i:s"),
                    );

                    $sql_insert = 'insert into scenes (id, preview, thumbs, iframe, status, duration, rate, channel_id, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                    DB::connection($database)->insert($sql_insert, $values);

                    $this->syncSceneTags($database, $scene, $domain_scene);

                    foreach ($languages as $lang) {
                        $translation = $scene->translations()->where('language_id', $lang->id)->first();

                        $values = array(
                            $translation->id,
                            $scene->id,
                            $lang->id,
                            $translation->title,
                            $translation->permalink,
                            $translation->description,
                        );
                        DB::connection($database)->insert('insert into scene_translations (id, scene_id, language_id, title, permalink, description) values (?, ?, ?, ?, ?, ?)', $values);
                    }
                } else {
                    echo "[SCENE] Actualizando scene " . $scene->id . " ya existe".PHP_EOL;
                    $sql_update = "UPDATE scenes SET status=".$scene->status . ",
                               preview = '".$scene->preview."',
                               thumbs = '".$scene->thumbs."',
                               iframe = '".$scene->iframe."',
                               status = ".$scene->status.",
                               rate = ".$scene->rate."
                                WHERE id=".$scene->id;

                    DB::connection($database)->update($sql_update);

                    $this->syncSceneTags($database, $scene, $domain_scene);

                    foreach ($languages as $lang) {
                        $translation = $scene->translations()->where('language_id', $lang->id)->first();

                        $sql_update = "UPDATE scene_translations SET
                            scene_id=" . $scene->id . ",
                            language_id=" . $lang->id. ",
                            title='" . $translation->title. "',
                            permalink='" . $translation->permalink . "',
                            description='" . $translation->description. "' where id=" . $translation->id;

                        DB::connection($database)->update($sql_update);
                    }
                }
            } else {
                echo "NO CUMPLE CON TAGS".PHP_EOL;
            }
        }
    }

    public function syncSceneTags($database, $scene, $domainScene)
    {
        $tagsScene = $scene->tags()->get();

        DB::connection($database)->table('scene_tag')->where('scene_id', $scene->id)->delete();

        foreach ($tagsScene as $tag) {
            $scene_tag = SceneTag::where('scene_id', $scene->id)->where('tag_id', $tag->id)->first();
            $sql_insert = "insert into scene_tag (id, tag_id, scene_id) values ($scene_tag->id, $tag->id, $scene->id)";
            DB::connection($database)->insert($sql_insert);
        }
    }

    public function syncTags($database)
    {
        $languages = Language::all();
        $tags = Tag::all();
        foreach ($tags as $tag) {
            $sql = "SELECT * FROM tags WHERE id=".$tag->id;
            $domain_tag= DB::connection($database)->select($sql);

            if (!$domain_tag) {
                echo "Añadimos el tag " . $tag->id. "".PHP_EOL;
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
                        DB::connection($database)->insert('insert into tag_translations (id, tag_id, name, permalink, language_id) values (?, ?, ?, ?, ?)', $values);
                }
            } else {
                echo "Actualizando el tag " . $tag->id . " ya existe".PHP_EOL;
                $sql_update = "UPDATE tags SET status=".$tag->status . " WHERE id=" . $tag->id;
                DB::connection($database)->update($sql_update);

                foreach ($languages as $lang) {
                    $translation = $tag->translations()->where('language_id', $lang->id)->first();

                    $sql_update = "UPDATE tag_translations SET
                            tag_id=" . $tag->id . ",
                            name='" . $translation->name . "',
                            permalink='" . $translation->permalink . "',
                            language_id=" . $lang->id. " where id=" . $translation->id;

                    DB::connection($database)->update($sql_update);
                }
            }
        }
    }
}
