<?php

namespace App\Console\Commands;

use App\Model\Channel;
use App\rZeBot\rZeBotUtils;
use Illuminate\Console\Command;
use App\Model\Language;
use App\Model\Scene;
use App\Model\Logpublish;
use App\Model\SceneTag;
use DB;
use Mockery\CountValidator\Exception;

class rZeBotScenesPublisher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:scenes:publisher {database} {scenesNumber}
                            {--duration=false : Min duration for publish}
                            {--channel=false : Publish only for this channel}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrappe sentences and titles';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $database     = $this->argument('database');
        $scenesNumber = $this->argument('scenesNumber');

        $channel = $this->option('channel');
        $duration = $this->option('duration');

        $remoteScenes = Scene::getRemoteActiveScenesIdsFor($database);

        $languages = Language::all();

        $query = Scene::whereNotIn('scenes.id', $remoteScenes)
            ->orderBy('rate', 'desc')
            ->limit($scenesNumber)
        ;

        if ($channel !== 'false') {
            $bbddChannel = Channel::where('name', '=', $channel)->first();
            if (!$bbddChannel) {
                rZeBotUtils::message("[ERROR] El channel '$channel' no ha sido encontrado, saliendo..." . PHP_EOL, "red");
                exit;
            } else {
                $query->where('channel_id', "=", $bbddChannel->id);
            }
        }

        if ($duration != 'false'){
            if (!is_numeric($duration)) {
                rZeBotUtils::message("[ERROR] --duration debe ser numérico" . PHP_EOL, "red");
                exit;
            } else {
                $query->where('duration', ">=", $duration);
            }
        }

        foreach($query->get() as $scene) {
            echo "Publicando escena " . $scene->id . PHP_EOL;
            $logdatabase = $scene->logspublish()->where('site', $database)->count();

            if ($logdatabase == 0) {
                $log = new Logpublish();
                $log->scene_id = $scene->id;
                $log->site = $database;
                $log->save();
            }

            $sql = "SELECT * FROM scenes WHERE id = " . $scene->id;
            $domain_scene = DB::connection($database)->select($sql);

            if (!$domain_scene) {
                echo "[SCENE] Añadimos la scene " . $scene->id . PHP_EOL;
                $values = array(
                    $scene->id,
                    $scene->preview,
                    $scene->thumbs,
                    $scene->iframe,
                    1,
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

                    try {
                        DB::connection($database)->insert('insert into scene_translations (id, scene_id, language_id, title, permalink, description) values (?, ?, ?, ?, ?, ?)', $values);
                    } catch(\Exception $e) {
                        rZeBotUtils::   message("Error al procesar scene_translation.id:" . $translation->id.PHP_EOL, "red");
                    }
                }
            } else {
                echo "[SCENE] Actualizando scene " . $scene->id . " ya existe" . PHP_EOL;
                $sql_update = "UPDATE scenes SET status=" . $scene->status . ",
                               preview = '" . $scene->preview . "',
                               thumbs = '" . $scene->thumbs . "',
                               published_at = '" . date('Y-m-d H:i:s') . "',
                               iframe = '" . $scene->iframe . "',
                               status = 1,
                               rate = " . $scene->rate . "
                                WHERE id=" . $scene->id;

                DB::connection($database)->update($sql_update);

                $this->syncSceneTags($database, $scene, $domain_scene);

                foreach ($languages as $lang) {
                    $translation = $scene->translations()->where('language_id', $lang->id)->first();

                    if ($translation) {
                        echo "Actualizando translation " . $lang->code.PHP_EOL;
                        $sql_update = "UPDATE scene_translations SET
                            scene_id=" . $scene->id . ",
                            language_id=" . $lang->id . ",
                            title=" . DB::connection()->getPdo()->quote($translation->title) . ",
                            permalink=" . DB::connection()->getPdo()->quote($translation->permalink) . ",
                            description='" . $translation->description . "' where id=" . $translation->id;

                        DB::connection($database)->update($sql_update);
                    } else {
                        echo "Translation not found for " . $lang->code.PHP_EOL;
                    }
                }
            }
        }
    }

    public function syncSceneTags($database, $scene, $domainScene)
    {
        $tagsScene = $scene->tags()->get();

        DB::connection($database)->table('scene_tag')->where('scene_id', $scene->id)->delete();

        foreach ($tagsScene as $tag) {
            $scene_tag = SceneTag::where('scene_id', $scene->id)->where('tag_id', $tag->id)->first();
            $sql_insert = "insert ignore into scene_tag (id, tag_id, scene_id) values ($scene_tag->id, $tag->id, $scene->id)";
            DB::connection($database)->insert($sql_insert);
        }
    }
}
