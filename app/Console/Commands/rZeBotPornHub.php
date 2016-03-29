<?php

namespace App\Console\Commands;

use App\Model\Category;
use App\Model\CategoryTranslation;
use App\Model\LanguageTag;
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

class rZeBotPornHub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rZeBot:pornhub:dump
                            {--truncate=false : Truncate before dump}
                            {--stop=false : Stop with nothing to do. Not include truncate}
                            {--max=false : Number max scenes to import}
                            {--tags=false : Only tags imported}
                            {--categories=false : Only categories imported}
                            {--rate=false : Only rate min imported}
                            {--views=false : Only views min imported}
                            {--only_update=false : Only update scenes }
                            {--duration=false : Only duration min imported}
                            {--clicks=false: Generate random visits (hard cpu, slow)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Launch rZeBot for PornHub dump';

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
        $clicks   = $this->option('clicks');
        $stop     = $this->option('stop');
        $max      = $this->option('max');
        $tags     = $this->option('tags');
        $categories = $this->option('categories');
        $rate     = $this->option('rate');
        $minViews = $this->option('views');
        $minDuration = $this->option('duration');
        $only_update = $this->option('only_update');

        if ($truncate == 'true') {
            echo "Truncando...".PHP_EOL.PHP_EOL;
            SceneTranslation::truncate();
            SceneTag::truncate();
            SceneClick::truncate();
            TagClick::truncate();

            foreach(Scene::all() as $scene) {
                $scene->delete();
            }
        }

        if ($stop == 'true') {
            die(PHP_EOL.'STOP'.PHP_EOL);
        }

        if ($tags !== 'false') {
            $tags = explode(",", $tags);
        } else {
            $tags = false;
        }

        if ($categories !== 'false') {
            $categories = explode(",", $categories);
        } else {
            $categories = false;
        }


        $fila = 1;
        $languages = Language::all();
        $added = 0;

        if (($gestor = fopen("../pornhub.com-db.csv", "r")) !== FALSE) {
            while (($datos = fgetcsv($gestor, 10000, "|")) !== FALSE) {
                echo "=============================================>$fila" . PHP_EOL;
                $fila++;
                // check limit import
                if ($max != 'false' && is_numeric($max) && $added >= $max) {
                    break;
                }

                $videorate = 0;
                if ($datos[9]+$datos[10] != 0) {
                    $videorate = ($datos[9]*100)/($datos[9]+$datos[10]);
                }

                $video = array(
                    "iframe"     => $datos[0],
                    "preview"    => $datos[1],
                    "thumbs"     => explode(";", $datos[2]),
                    "title"      => $datos[3],
                    "tags"       => explode(";", $datos[4]),
                    "categories" => explode(";", $datos[5]),
                    "duration"   => $datos[7],
                    "likes"      => $datos[8],
                    "views"      => $datos[9],
                    "unlikes"    => $datos[10],
                    "rate"       => $videorate
                );

                // check tags matched
                $mixed_tag = true;
                if ($tags !== false) {
                    $mixed_check = false;
                    foreach ($video["tags"] as $tagTxt) {
                        if (in_array($tagTxt, $tags)) {
                            $mixed_check = true;
                        }
                    }
                }

                if (!$mixed_tag) {
                    continue;
                }

                // check categories matched
                $mixed_tag = true;
                if ($categories !== false) {
                    $mixed_check = false;
                    foreach ($video["categories"] as $categoryTxt) {
                        if (in_array($categoryTxt, $categories)) {
                            $mixed_check = true;
                        }
                    }
                }

                if (!$mixed_tag) {
                    continue;
                }

                // preview is used to check if already exists
                if(Scene::where('preview', $video["preview"])->count() == 0) {
                    $mixed_check = true;

                    if ($only_update !== "false") {
                        $mixed_check = false;
                        echo "SKIPPED".PHP_EOL;
                        continue;
                    }

                    // check tags matched
                    if ($tags !== false) {
                        $mixed_check = false;
                        foreach ($video["tags"] as $tagTxt) {
                            if (in_array($tagTxt, $tags)) {
                                $mixed_check = true;
                            }
                        }
                    }

                    // check categories matched
                    if ($categories !== false) {
                        $mixed_check = false;
                        foreach ($video["categories"] as $categoryTxt) {
                            if (in_array($categoryTxt, $categories)) {
                                $mixed_check = true;
                            }
                        }
                    }

                    if (!$mixed_check) {
                        echo "TAGS/CATEGORIES -> SKIPPED" . PHP_EOL;
                    }

                    // rate check
                    if ($rate !== 'false') {
                        if ($video["rate"] < $rate) {
                            $mixed_check = false;
                            echo "RATE: Rate insuficiente" . PHP_EOL;
                        }
                    }

                    // views check
                    if ($minViews !== 'false') {
                        if ($video["views"] < $minViews) {
                            $mixed_check = false;
                            echo "VIEWS: Views insuficiente" . PHP_EOL;
                        }
                    }

                    // duration check
                    if ($minDuration !== 'false') {
                        if ($video["duration"] < $minDuration) {
                            $mixed_check = false;
                            echo "DURATION: duration insuficiente" . PHP_EOL;
                        }
                    }

                    if ($mixed_check) {
                        $added++;

                        $scene = new Scene();
                        $scene->preview = $video["preview"];
                        $scene->iframe  = $video["iframe"];
                        $scene->status  = 1;
                        $scene->status  = $video["views"];
                        $scene->channel_id = 1;         //pornhub
                        $scene->thumbs  = utf8_encode(json_encode($video["thumbs"]));
                        $scene->duration = $video["duration"];
                        $scene->rate = $video["rate"];
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
                            $sceneTranslation->language_id = $language->id;

                            if ($language->id == 2) {
                                $sceneTranslation->title = $video["title"];
                                $sceneTranslation->permalink = rZeBotUtils::slugify($video["title"]);
                            }

                            $sceneTranslation->save();
                        }

                        // tags
                        foreach ($video["tags"] as $tagTxt) {

                            if (TagTranslation::where('name', $tagTxt)->where('language_id', 2)->count() == 0) {
                                //echo "TAG: creando tag en la colección" . PHP_EOL;
                                $tag = new Tag();
                                $tag->status = 2;
                                $tag->save();
                                $tag_id=$tag->id;

                                // tag translations
                                foreach ($languages as $language) {
                                    $tagTranslation = new TagTranslation();
                                    $tagTranslation->language_id = $language->id;
                                    $tagTranslation->tag_id = $tag_id;

                                    if ($language->id == 2) {
                                        $tagTranslation->permalink = rZeBotUtils::slugify($tagTxt);;
                                        $tagTranslation->name = $tagTxt;
                                    }

                                    $tagTranslation->save();
                                }
                            } else {
                                $tagTranslation = TagTranslation::where('name', $tagTxt)->where('language_id', 2)->first();
                                $tag_id = $tagTranslation->tag_id;
                                //echo "TAG: ya existente en la colección" . PHP_EOL;
                            }

                            $sceneTag = new SceneTag();
                            $sceneTag->scene_id = $scene->id;
                            $sceneTag->tag_id = $tag_id;
                            $sceneTag->save();
                            //echo "TAG: asociando el tag $tagTxt" . PHP_EOL;
                        }

                        // categories
                        foreach ($video["categories"] as $categoryTxt) {

                            if (CategoryTranslation::where('name', $categoryTxt)->where('language_id', 2)->count() == 0) {
                                $category = new Category();
                                $category->status = 1;
                                $category->save();
                                $category_id=$category->id;

                                // tag translations
                                foreach ($languages as $language) {
                                    $categoryTranslation = new CategoryTranslation();
                                    $categoryTranslation->language_id = $language->id;
                                    $categoryTranslation->category_id = $category_id;

                                    if ($language->id == 2) {
                                        $categoryTranslation->permalink = str_slug($categoryTxt);
                                        $categoryTranslation->name = $categoryTxt;
                                    }

                                    $categoryTranslation->save();
                                }
                            } else {
                                $categoryTranslation = CategoryTranslation::where('name', $categoryTxt)->where('language_id', 2)->first();
                                $category_id = $categoryTranslation->category_id;
                            }

                            $scene = Scene::where('preview', $video["preview"])->first();
                            echo "Creando link scene_category".PHP_EOL;
                            $sceneCategory = new SceneCategory();
                            $sceneCategory->scene_id = $scene->id;
                            $sceneCategory->category_id = $category_id;
                            $sceneCategory->save();
                        }
                    }
                } else {
                    // Si ya existe recategorizamos categorias
                    echo "SCENE: ya existente".PHP_EOL;
                }
            }

            fclose($gestor);
        }
    }
}
