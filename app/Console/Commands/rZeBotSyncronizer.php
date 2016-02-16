<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\Logpublish;
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
    protected $signature = 'rZeBot:syncronizer:scenes {database}';

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

        echo "Syncronize from " . $database . PHP_EOL;
        $logPublisheds = Logpublish::where('site', 'like', $database)
            ->groupBy('scene_id')
            ->orderBy('id', 'ASC')
            ->get()
        ;
        $ids = [];
        foreach($logPublisheds as $publish) {
            $ids[] = $publish->scene_id;
        }

        $total = count($ids);
        $i=0;
        foreach($ids as $id) {
            $scene = Scene::find($id);
            echo "[ " . number_format(($i*100)/ $total, 0) ."% ] " . $scene->id . PHP_EOL;
            $i++;
            $this->exportScene($database, $scene);
            sleep(1);
        }
    }

    public function exportScene($database, $scene) {
        $languages = Language::all();

        $sql_update = "UPDATE scenes SET status=".$scene->status . ",
                           preview = '".$scene->preview."',
                           thumbs = '".$scene->thumbs."',
                           iframe = '".$scene->iframe."',
                           status = ".$scene->status.",
                           rate = ".$scene->rate.",
                           updated_at = '".date('Y-m-d H:i:s')."'
                            WHERE id=".$scene->id;

        DB::connection($database)->update($sql_update);

        //$this->syncSceneTags($database, $scene, $domain_scene);

//        foreach ($languages as $lang) {
//            $translation = $scene->translations()->where('language_id', $lang->id)->first();
//
//            $sql_update = "UPDATE scene_translations SET
//                        scene_id=" . $scene->id . ",
//                        language_id=" . $lang->id. ",
//                        title=" . DB::connection()->getPdo()->quote((($translation->title != "") ? $translation->title : "")). ",
//                        permalink=" . DB::connection()->getPdo()->quote((($translation->permalink != "") ? $translation->permalink : "")) . ",
//                        description=" . DB::connection()->getPdo()->quote((($translation->description != "") ? $translation->description : "")) . "
//                        where id=" . $translation->id;
//            DB::connection($database)->update($sql_update);
//        }
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
