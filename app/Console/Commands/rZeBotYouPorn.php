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

class rZeBotYouPorn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:youporn:dump
                            {--truncate=false : Truncate before dump}
                            {--stop=false : Stop with nothing to do}
                            {--max=false : Number to import}
                            {--clicks=false: Generate random visits}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Launch rZeBot for YouPorn dump';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->loadCSV();
    }

    public function loadCSV()
    {

        $truncate = $this->option('truncate');
        $clicks = $this->option('clicks');
        $stop = $this->option('stop');
        $max = $this->option('max');

        if ($truncate == 'true') {
            echo "Truncando...".PHP_EOL.PHP_EOL;
            LanguageTag::truncate();
            SceneTranslation::truncate();
            TagTranslation::truncate();
            SceneTag::truncate();
            SceneClick::truncate();
            TagClick::truncate();

            foreach(Tag::all() as $tag) {
                $tag->delete();
            }

            foreach(Scene::all() as $scene) {
                $scene->delete();
            }
        }

        if ($stop == 'true') {
            die(PHP_EOL.'STOP'.PHP_EOL);
        }


        $fila = 1;
        $languages = Language::all();
        $added = 0;
        if (($gestor = fopen("../YouPorn-Embed-Videos-Dump.csv", "r")) !== FALSE) {
            while (($datos = fgetcsv($gestor, 50000, "|")) !== FALSE) {
                echo "=============================================>$fila" . PHP_EOL;
                $fila++;

                // check limit import
                if ($max != 'false' && is_numeric($max) && $added >= $max) {
                    break;
                }

                if ($fila > 2) {
                    //    [0] => EMBEDIFRAMECODE
                    //    [1] => THUMB
                    //    [2] => TITLE
                    //    [3] => TAG
                    //    [4] => CATEGORY
                    //    [5] => PORNSTAR
                    //    [6] => DURATION

                    $duration = $datos[6];
                    $pattern_mins = "/[0-9]*m/";
                    $pattern_secs = "/[0-9]*s/";
                    preg_match($pattern_mins, $duration, $minsData);
                    preg_match($pattern_secs, $duration, $secsData);
                    $minutos  = str_replace("m", "", $minsData[0]);
                    $segundos = str_replace("s", "", $secsData[0]);

                    $duration = $minutos*60+$segundos+0;

                    $thumbs = explode(",", $datos[1]);
                    $video = array(
                        "iframe"   => $datos[0],
                        "preview"  => $thumbs[0],
                        "thumbs"   => $thumbs,
                        "title"    => $datos[2],
                        "duration" => $duration,
                        "tags"     => explode(",", $datos[4])
                    );

                    if(SceneTranslation::where('title', $video["title"])->where('language_id', 2)->count() == 0) {
                        $added++;

                        $scene = new Scene();
                        $scene->preview = $video["preview"];
                        $scene->iframe  = $video["iframe"];
                        $scene->status  = 1;
                        $scene->channel_id = 2;         //youporn
                        $scene->duration = $duration;
                        $scene->thumbs  = utf8_encode(json_encode($video["thumbs"]));
                        $scene->save();

                        // scene clicks
                        if ($clicks == 'random') {
                            $max = rand(0, 99);
                            for ($z=0;$z<=$max;$z++) {
                                $sceneClick = new SceneClick();
                                $sceneClick->scene_id = $scene->id;
                                $sceneClick->referer = 'pornhub-dump';
                                $sceneClick->save();
                            }
                        }
                        //translations
                        foreach ($languages as $language) {
                            $sceneTranslation = new SceneTranslation();
                            $sceneTranslation->scene_id = $scene->id;
                            $sceneTranslation->title = $video["title"];
                            $sceneTranslation->permalink = rZeBotUtils::slugify($video["title"]);
                            $sceneTranslation->language_id = $language->id;
                            $sceneTranslation->save();
                        }

                        // tags
                        foreach ($video["tags"] as $tagTxt) {

                            if (TagTranslation::where('name', $tagTxt)->count() == 0) {
                                echo "TAG: creando tag en la colección" . PHP_EOL;
                                $tag = new Tag();
                                $tag->save();
                                $tag_id=$tag->id;

                                // tag translations
                                foreach ($languages as $language) {
                                    $tagTranslation = new TagTranslation();
                                    $tagTranslation->name = $tagTxt;
                                    $tagTranslation->permalink = rZeBotUtils::slugify($tagTxt);;
                                    $tagTranslation->language_id = $language->id;
                                    $tagTranslation->tag_id = $tag_id;
                                    $tagTranslation->save();
                                }
                            } else {
                                $tagTranslation = TagTranslation::where('name', $tagTxt)->first();
                                $tag_id = $tagTranslation->tag_id;
                                echo "TAG: ya existente en la colección" . PHP_EOL;
                            }

                            $sceneTag = new SceneTag();
                            $sceneTag->scene_id = $scene->id;
                            $sceneTag->tag_id = $tag_id;
                            $sceneTag->save();
                            echo "TAG: asociando el tag $tagTxt" . PHP_EOL;
                        }
                    } else {
                        echo "SCENE: ya existente" . PHP_EOL;
                    }

                }
            }

            fclose($gestor);
        }
    }
}
