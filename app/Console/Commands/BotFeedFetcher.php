<?php

namespace App\Console\Commands;

use App\Model\Channel;
use App\Model\LanguageTag;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Site;
use App\Model\Tag;
use App\Model\InfoJobs;
use App\Model\Language;
use App\Model\Scene;
use App\Model\SceneTranslation;
use App\Model\TagTranslation;
use App\Model\Category;
use App\Model\CategoryTranslation;
use App\Model\SceneTag;
use App\Model\SceneCategory;
use App\rZeBot\rZeBotCommons;
use Artisan;

class BotFeedFetcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:feed:fetch {feed_name} {site_id}
                            {--max=false : Number max scenes to import}
                            {--tags=false : Process tags from scene}
                            {--categories=false : Process tags from scene}
                            {--rate=false : Only rate min imported}
                            {--views=false : Only views min imported}
                            {--only_update=false : Only update scenes }
                            {--duration=false : Only duration min imported}
                            {--create_categories_from_tags=false : Launch update categories}
                            {--job=false : Infojob Id}
                            {--test=false : Test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch scenes from feeds to one site';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $feed_name     = $this->argument('feed_name');
        $site_id       = $this->argument('site_id');

        $max         = $this->option('max');
        $tags        = $this->option('tags', false);
        $categories  = $this->option('categories');
        $rate        = $this->option('rate');
        $minViews    = $this->option('views');
        $minDuration = $this->option('duration');
        $only_update = $this->option('only_update');
        $test        = $this->option('test');
        $job         = $this->option('job');
        $create_categories_from_tags = $this->option('create_categories_from_tags');

        $tags       = $this->parseTagsOption($tags);
        $categories = $this->parseCategoriesOption($categories);

        // get feed
        $feed = Channel::where("name", "=", $feed_name)->first();

        if (!$feed) {
            rZeBotUtils::message("[ERROR] El feed '$feed_name' indicado no existe. Abortando ejecución.", "red");
            exit;
        }

        // check site
        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("[ERROR] El sitio '$site_id' indicado no existe. Abortando ejecución.", "red");
            exit;
        }

        // instance class dynamically from mapping_class field in bbdd
        $cfg = new $feed->mapping_class;

        // Info debug
        if ($job !== "false") {
            rZeBotUtils::message('Job: '. $job, "green");
        }

        $this->parseCSV(
            $site_id,
            $feed,
            $max,
            $cfg->mappingColumns(),
            $cfg->configFeed(),
            $tags,
            $categories,
            $only_update,
            $rate,
            $minViews,
            $minDuration,
            $default_status = env("DEFAULT_FETCH_STATUS", 1),
            $test,
            $create_categories_from_tags
        );
        
        // delete infojob
        if ($job !== "false") {
            rZeBotUtils::message('Finalizamos InfoJob: '. $job, "yellow");
            $infojob = InfoJobs::find($job);
            $infojob->finished = true;
            $infojob->finished_at = date("Y-m-d H:i:s");
            $infojob->save();
        }
    }

    public function parseTagsOption($tags)
    {
        if ($tags !== 'false') {
            $tags = explode(",", $tags);
            $tmp = [];
            foreach($tags as $tag) {
                $tmp[] = trim(strtolower($tag));
            }
            $tags = $tmp;
        } else {
            $tags = false;
        }

        return $tags;
    }

    public function parseCategoriesOption($categories)
    {
        if ($categories !== 'false') {
            $categories = explode(",", $categories);
            echo "Categories selected:".PHP_EOL;
            print_r($categories);
        } else {
            $categories = false;
        }

        return $categories;
    }

    public function parseCSV($site_id, $feed, $max, $mapped_colums, $feed_config, $tags, $categories, $only_update, $rate, $minViews, $minDuration, $default_status, $test, $create_categories_from_tags)
    {
        $fila = 1;
        $languages = Language::all();
        $added = 0;
        $fileCSV = rZeBotCommons::getDumpsFolder().$feed->file;

        if (!file_exists($fileCSV)) {
            rZeBotUtils::message("[ERROR] $fileCSV not exist...", "red", true, true);
        }

        if (($gestor = fopen($fileCSV, "r")) !== FALSE) {
            while (($datos = fgetcsv($gestor, 30000, $feed_config["fields_separator"])) !== FALSE) {

                $fila++;

                if ($feed_config["skip_first_list"] == true && $fila == 2) {
                    rZeBotUtils::message("[WARNING] Saltando primera linea del fichero...", "yellow", true, false);
                    continue;
                }

                // check total cols matched CSV <-> config array
                if ($feed_config["totalCols"] != count($datos)) {
                    rZeBotUtils::message("Error en el número de columnas, deteniendo ejecución...", "red", true, false);
                    print_r($datos);
                    continue;
                }

                // check limit import
                if ($max != 'false' && is_numeric($max) && $added >= $max) {
                    rZeBotUtils::message("[DONE] Alcanzado número máximo de escenas indicado: $max", "cyan", true, false);
                    break;
                }

                // likes/unlikes
                $videorate = 0;
                if ($mapped_colums['unlikes'] !== false && $mapped_colums['likes'] !== false) {
                    $unlikes = $datos[$mapped_colums['unlikes']];
                    $likes = $datos[$mapped_colums['likes']];
                } else {
                    $unlikes = $likes = 0;
                }

                if ($likes+$unlikes != 0) {
                    $videorate = ($likes*100)/($likes+$unlikes);
                }

                // mount $video data array
                $video = array(
                    "iframe"   => $datos[$mapped_colums['iframe']],
                    "title"    => $datos[$mapped_colums['title']],
                    "tags"     => explode($feed_config["tags_separator"], $datos[$mapped_colums['tags']]),
                    "duration" => $feed_config["parse_duration"]($datos[$mapped_colums['duration']]),
                    "likes"    => $likes,
                    "unlikes"  => $unlikes,
                    "views"    => ($mapped_colums['views'] !== false) ? $datos[$mapped_colums['views']] : 0,
                    "rate"     => $videorate
                );

                // ************************************************************ parse field individually

                // categories
                if ($mapped_colums['categories'] !== false) {
                    $video["categories"] = explode($feed_config["categories_separator"], $datos[$mapped_colums['categories']]);
                } else {
                    $video["categories"] = null;
                }

                // thumbs
                if ($mapped_colums['thumbs'] !== false) {
                    $video["thumbs"] = explode($feed_config["thumbs_separator"], $datos[$mapped_colums['thumbs']]);
                } else {
                    // if not have thumbs, try set only preview, else, empty
                    if ($mapped_colums['preview'] !== false) {
                        $video["thumbs"] = array($datos[$mapped_colums['preview']]);
                    } else {
                        $video["thumbs"] = null;
                    }
                }

                // preview
                if ($mapped_colums['preview'] !== false) {
                    $video["preview"] = $datos[$mapped_colums['preview']];
                } else {
                    // if not have preview, try set only preview, else, empty
                    if ($mapped_colums['thumbs'] !== false) {
                        $video["preview"] = explode($feed_config["thumbs_separator"], $datos[$mapped_colums['thumbs']])[0];
                    } else {
                        $video["preview"] = null;
                    }
                }

                // check tags matched
                $mixed_check = true;
                if ($tags !== false) {
                    $mixed_check = false;
                    foreach ($video["tags"] as $tagTxt) {
                        if (in_array(strtolower($tagTxt), $tags)) {
                            $mixed_check = true;
                        }
                    }
                }

                if (!$mixed_check) {
                    rZeBotUtils::message("mixed_check tags continue;", "yellow", true, false);
                    continue;
                }

                // check categories matched
                $mixed_check = true;
                if ($categories !== false) {
                    $mixed_check = false;
                    foreach ($video["categories"] as $categoryTxt) {
                        if (in_array($categoryTxt, $categories)) {
                            $mixed_check = true;
                            rZeBotUtils::message("Found category: " . $categoryTxt, "green", true, false);
                        }
                    }
                }

                if (!$mixed_check) {
                    rZeBotUtils::message("mixed_check categories continue;", "yellow", true, false);
                    continue;
                }

                // preview is used to check if already exists
                if(Scene::where('preview', $video["preview"])->where('site_id', $site_id)->count() == 0) {
                    $mixed_check = true;

                    if ($only_update !== "false") {
                        $mixed_check = false;
                        rZeBotUtils::message("SKIPPED", "yellow", true, false);
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
                        rZeBotUtils::message("TAGS/CATEGORIES -> SKIPPED", "yellow", true, false);
                    }

                    // rate check
                    if ($rate !== 'false') {
                        if ($video["rate"] < $rate) {
                            $mixed_check = false;
                            rZeBotUtils::message("RATE: Rate insuficiente", "yellow", true, false);
                        }
                    }

                    // views check
                    if ($minViews !== 'false') {
                        if ($video["views"] < $minViews) {
                            $mixed_check = false;
                            rZeBotUtils::message("VIEWS: Views insuficiente", "yellow", true, false);
                        }
                    }

                    // duration check
                    if ($minDuration !== 'false') {
                        if ($video["duration"] < $minDuration) {
                            $mixed_check = false;
                            rZeBotUtils::message("DURATION: duration insuficiente", "yellow", true, false);
                        }
                    }

                    if ($mixed_check) {
                        $added++;

                        if ($test !== 'false') {
                            rZeBotUtils::message("[TEST MAPPING FROM FEED", "yellow", true, false);
                            print_r($video);
                            exit;
                        }

                        rZeBotUtils::message("[SCENE CREATE] $fila -'". $video['title']."' (site_id: ".$site_id.")", "green", true, false);

                        $scene = $this->createScene($video, $default_status, $feed, $site_id, $languages);

                        // Create tags from CSV
                        $this->processTags($video, $site_id, $scene, $languages);

                        // Create categories from CSV
                        //$this->processCategories($video, $site_id, $scene, $languages);

                        // Create categories from tags
                        if ($create_categories_from_tags !== "false") {
                            rZeBotUtils::message("[CREATING CATEGORIES FROM TAGS FOR scene_id: $scene->id] ", "cyan", true, false);
                            $this->createCategoriesFromTags($scene, $languages);
                        }
                    }
                } else {
                    rZeBotUtils::message("[WARNING] Scene ya existente, saltando...", "yellow", true, false);
                }
            }

            fclose($gestor);
        }
    }

    public function createCategoriesFromTags($scene, $languages) {

        $sceneTags = Tag::getTranslationByScene($scene, $englishLanguage = 2);

        foreach($sceneTags->get() as $tag) {
            rZeBotUtils::createCategoryFromTag(
                $tag,
                $scene->site_id,
                $min_scenes_activation = env("MIN_SCENES_CATEGORY_ACTIVATION", 30),
                $languages,
                $englishLanguage = 2,
                $abs_total = 1,
                $timer = 0
            );
        }
    }

    public function createScene($video, $default_status, $feed, $site_id, $languages)
    {
        $scene = new Scene();
        $scene->preview    = $video["preview"];
        $scene->iframe     = $video["iframe"];
        $scene->status     = $default_status;
        $scene->views      = $video["views"];
        $scene->channel_id = $feed->id;
        $scene->thumbs     = utf8_encode(json_encode($video["thumbs"]));
        $scene->duration   = $video["duration"];
        $scene->rate       = $video["rate"];
        $scene->site_id    = $site_id;

        $scene->save();

        //translations
        foreach ($languages as $language) {
            $sceneTranslation = new SceneTranslation();
            $sceneTranslation->language_id = $language->id;
            $sceneTranslation->scene_id = $scene->id;

            if ($language->id == 2) {
                $sceneTranslation->title = $video["title"];
                $sceneTranslation->permalink = rZeBotUtils::slugify($video["title"]);
            }

            $sceneTranslation->save();
        }

        return $scene;
    }

    public function processTags($video, $site_id, $scene, $languages)
    {
        // tags
        foreach ($video["tags"] as $tagTxt) {

            if (TagTranslation::join('tags', 'tags.id', '=', 'tag_translations.tag_id')
                ->where('site_id', '=', $site_id)
                ->where('name', $tagTxt)
                ->where('language_id', 2)
                ->count() == 0
            ) {
                //echo "TAG: creando tag en la colección" . PHP_EOL;
                $tag = new Tag();
                $tag->status = 2;
                $tag->site_id = $site_id;
                $tag->save();
                $tag_id = $tag->id;

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
                $tagTranslation = TagTranslation::join('tags', 'tags.id', '=', 'tag_translations.tag_id')
                    ->where('name', $tagTxt)
                    ->where('site_id', '=', $site_id)
                    ->where('language_id', 2)
                    ->first();
                $tag_id = $tagTranslation->tag_id;
                //echo "TAG: ya existente en la colección" . PHP_EOL;
            }

            $sceneTag = new SceneTag();
            $sceneTag->scene_id = $scene->id;
            $sceneTag->tag_id = $tag_id;
            $sceneTag->save();
            //echo "TAG: asociando el tag $tagTxt" . PHP_EOL;
        }
    }

    public function processCategories($video, $site_id, $scene, $languages)
    {
        // categories
        foreach ($video["categories"] as $categoryTxt) {
            if(strlen($categoryTxt) == 0) {
                continue;
            }

            if (CategoryTranslation::join('categories', 'categories.id', '=', 'categories_translations.category_id')
                    ->where('categories.site_id', $site_id)
                    ->where('name', $categoryTxt)
                    ->where('language_id', 2)
                    ->count() == 0)
            {

                rZeBotUtils::message("Creando categoría $categoryTxt", "green");

                $category = new Category();
                $category->status = 1;
                $category->text = $categoryTxt;
                $category->site_id = $site_id;
                $category->save();
                $category_id = $category->id;

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
                $categoryTranslation = CategoryTranslation::join('categories', 'categories.id', '=', 'categories_translations.category_id')
                    ->where('categories.site_id', $site_id)
                    ->where('name', $categoryTxt)
                    ->where('language_id', 2)
                    ->first()
                ;
                $category_id = $categoryTranslation->category_id;
            }

            $sceneCategory = new SceneCategory();
            $sceneCategory->scene_id = $scene->id;
            $sceneCategory->category_id = $category_id;
            $sceneCategory->save();
        }
    }


}