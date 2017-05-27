<?php

namespace App\Console\Commands;

use App\Model\Channel;
use App\Model\LanguageTag;
use App\Model\ScenePornstar;
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
use App\rZeBot\sexodomeKernel;
use App\Model\Pornstar;
use DB;
use Artisan;
use Illuminate\Support\Str;

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
                            {--rate=false : Only rate min imported}
                            {--views=false : Only views min imported}
                            {--duration=false : Only duration min imported}
                            {--spin=false : Spin scene title and description}
                            {--only_with_pornstars=false : Only import scenes with pornstars}
                            {--categorize=false : Categorize scene}
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
        $rate        = $this->option('rate');
        $minViews    = $this->option('views');
        $minDuration = $this->option('duration');
        $spin        = $this->option('spin');
        $test        = $this->option('test');
        $job         = $this->option('job');
        $only_with_pornstars = $this->option('only_with_pornstars');
        $categorize  = $this->option('categorize');

        $tags       = $this->parseTagsOption($tags);

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
            $site,
            $feed,
            $max,
            $cfg->mappingColumns(),
            $cfg->configFeed(),
            $tags,
            $rate,
            $minViews,
            $minDuration,
            $default_status = env("DEFAULT_FETCH_STATUS", 1),
            $test,
            $only_with_pornstars,
            $spin,
            $categorize
        );

        // delete infojob
        if ($job !== "false") {
            rZeBotUtils::message('Finalizamos InfoJob: '. $job, "yellow");
            $infojob = InfoJobs::find($job);
            if ($infojob) {
                $infojob->finished = true;
                $infojob->finished_at = date("Y-m-d H:i:s");
                $infojob->save();
            } else {
                rZeBotUtils::message('[ERROR : '. $job, "yellow");
            }
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
            echo "tags selected: ". implode(", ", $tags) . PHP_EOL;

        } else {
            $tags = false;
        }

        return $tags;
    }

    public function parseCategoriesOption($categories)
    {
        if ($categories !== 'false') {
            $categories = explode(",", $categories);
            echo "Categories selected: ". implode(", ", $categories) . PHP_EOL;
        } else {
            $categories = false;
        }

        return $categories;
    }

    public function parseCSV($site, $feed, $max, $mapped_colums, $feed_config, $tags, $rate, $minViews, $minDuration, $default_status, $test, $only_with_pornstars, $spin, $categorize)
    {
        DB::transaction(function () use ($site, $feed, $max, $mapped_colums, $feed_config, $tags, $rate, $minViews, $minDuration, $default_status, $test, $only_with_pornstars, $spin, $categorize) {

            $site_id = $site->id;

            $fila = 1;
            $languages = Language::all();
            $added = 0;
            $fileCSV = sexodomeKernel::getDumpsFolder().$feed->file;

            if (!file_exists($fileCSV)) {
                rZeBotUtils::message("[ERROR] $fileCSV not exist...", "red", true, true);
            }

            if (($gestor = fopen($fileCSV, "r")) !== FALSE) {
                while (($datos = fgetcsv($gestor, 30000, $feed_config["fields_separator"])) !== FALSE) {

                    $fila++;

                    if ($feed_config["skip_first_list"] == true && $fila == 2) {
                        rZeBotUtils::message("[WARNING] Saltando primera linea del fichero...", "yellow", false, false);
                        continue;
                    }

                    // check total cols matched CSV <-> config array
                    if ($feed_config["totalCols"] != count($datos)) {
                        rZeBotUtils::message("Error en el número de columnas, deteniendo ejecución...", "red", false, false);
                        continue;
                    }

                    // check limit import
                    if ($max != 'false' && is_numeric($max) && $added >= $max) {
                        rZeBotUtils::message("[DONE] Alcanzado número máximo de escenas indicado: $max", "cyan", false, false);
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
                        "iframe"    => $datos[$mapped_colums['iframe']],
                        "url"       => $datos[$mapped_colums['url']],
                        "title"     => $datos[$mapped_colums['title']],
                        "duration"  => $feed_config["parse_duration"]($datos[$mapped_colums['duration']]),
                        "likes"     => $likes,
                        "unlikes"   => $unlikes,
                        "views"     => ($mapped_colums['views'] !== false) ? $datos[$mapped_colums['views']] : 0,
                        "rate"      => $videorate
                    );

                    // description (no todos los feeds llevan description)
                    if (isset($mapped_colums['description'])) {
                        $video["description"] = $datos[$mapped_colums['description']];
                    }
                    // ************************************************************ parse field individually arrays

                    // tags
                    if ($mapped_colums['tags'] !== false && strlen($datos[$mapped_colums['tags']])) {
                        $video["tags"] = explode($feed_config["tags_separator"], $datos[$mapped_colums['tags']]);
                    } else {
                        $video["tags"] = [];
                    }

                    // categories
                    if ($mapped_colums['categories'] !== false && strlen($datos[$mapped_colums['categories']]) > 0) {
                        $video["categories"] = explode($feed_config["categories_separator"], $datos[$mapped_colums['categories']]);
                    } else {
                        $video["categories"] = [];
                    }

                    // unimos las categorías del video con los tags. Para nosotros serán tags
                    $video['tags'] = array_merge($video["tags"], $video["categories"]);
                    $video['tags'] = array_unique($video['tags']);

                    // pornstars
                    if ($mapped_colums['pornstars'] !== false && strlen($datos[$mapped_colums['pornstars']]) > 0) {
                        $video["pornstars"] = explode($feed_config["pornstars_separator"], $datos[$mapped_colums['pornstars']]);
                    } else {
                        $video["pornstars"] = null;
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

                    if ($only_with_pornstars !== "false") {

                        // Si el feed no tiene pornstars directamente fuera
                        if ( $video["pornstars"] == null) {
                            rZeBotUtils::message("[PORNSTAR FLAG SKIPPED. CHANNEL NOT HAVE PORNSTAR]", "yellow", true, false);
                            continue;
                        } else {
                            if (count($video["pornstars"]) == 0) {
                                rZeBotUtils::message("[PORNSTAR FLAG SKIPPED. NO PORNSTARS IN SCENE]", "yellow", true, false);
                                continue;
                            }
                        }
                    }

                    // check tags matched
                    $tags_check = true;
                    if ($tags !== false) {
                        $tags_check = false;
                        if (is_array($video["tags"])) {
                            foreach ($video["tags"] as $tagTxt) {
                                if (in_array(strtolower($tagTxt), $tags)) {
                                    $tags_check = true;
                                    rZeBotUtils::message("Found tag: " . $tagTxt, "green", true, false);
                                }
                            }
                        } else {
                            rZeBotUtils::message("No hay TAGS en el video y hay filtro existente: " . implode(",", $tags), "green", true, false);
                            continue;
                        }
                    }

                    // check categories matched
                    if (!$tags_check) {
                        rZeBotUtils::message("skipped scene by tag filter...", "yellow", true, false);
                        continue;
                    }

                    // url is used to check if already exists
                    if( Scene::where('url', $video["url"])->where('site_id', $site_id)->count() == 0 ) {

                        $mixed_check = true;

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
                                sleep(10);
                                exit;
                            }

                            rZeBotUtils::message("[SCENE CREATE] $fila -'". $video['title']."' (site_id: ".$site_id.")", "green", true, false);

                            $scene = $this->createScene($video, $default_status, $feed, $site_id, $languages);

                            // Create tags from CSV
                            $this->processTags($video, $site_id, $scene, $languages);

                            $this->processPornstars($video, $site_id, $scene);

                            if ($site->language_id != 2) {
                                $exitCodeTranslation = Artisan::call('zbot:translate:video', [
                                    'from'     => 'en',
                                    'to'       => $site->language->code,
                                    'scene_id' => $scene->id,
                                ]);
                            } else {
                                if ($spin !== 'false') {
                                    $exitCodeSpin = Artisan::call('zbot:spin:scene', [
                                        'scene_id' => $scene->id,
                                    ]);
                                }
                            }

                            if ($categorize !== 'false') {
                                $exitCodeCategorize = Artisan::call('zbot:categorize:scene', [
                                    'scene_id' => $scene->id,
                                ]);
                            }
                        }
                    } else {
                        rZeBotUtils::message("[WARNING] Scene de ".$feed->name." ya existente en " . $site->getHost().", saltando...", "yellow", true, false);
                    }
                }
                fclose($gestor);
            }
        });
    }

    public function createScene($video, $default_status, $feed, $site_id, $languages)
    {
        $scene = new Scene();

        $scene->preview    = $video["preview"];
        if ($video["iframe"] !== false) {
            $scene->iframe     = $video["iframe"];
        }

        if ($video["url"] !== false) {
            $scene->url = $video["url"];
        }

        $scene->status     = $default_status;
        $scene->views      = is_integer($video["views"]) ? $video["views"] : 0;
        $scene->channel_id = $feed->id;
        $scene->thumbs     = utf8_encode(json_encode($video["thumbs"]));
        $scene->duration   = $video["duration"];
        $scene->rate       = $video["rate"];
        $scene->site_id    = $site_id;

        $scene->save();

        // thumbnail
        rZeBotUtils::downloadThumbnail($scene->preview);

        //translations
        foreach ($languages as $language) {
            $sceneTranslation = new SceneTranslation();
            $sceneTranslation->language_id = $language->id;
            $sceneTranslation->scene_id = $scene->id;

            if ($language->id == 2) {
                $sceneTranslation->title = utf8_encode($video["title"]);
                $sceneTranslation->permalink = rZeBotUtils::slugify($video["title"]);
                if (isset($video["description"])) {
                    $sceneTranslation->description = substr(trim(Str::ascii(utf8_encode($video["description"]))), 0, 255);
                }
            }

            $sceneTranslation->save();
        }

        return $scene;
    }

    public function processPornstars($video, $site_id, $scene)
    {
        if ($video["pornstars"] == null) {
            return false;
        }

        foreach ($video["pornstars"] as $pornstarTxt) {
            if (strlen($pornstarTxt) > 0) {
                $pornstar = Pornstar::where('site_id', $site_id)->where('name', $pornstarTxt)->first();

                $sceneRND = false;

                if (!$pornstar) {

                    $pornstar = new Pornstar();
                    $pornstar->site_id = $site_id;
                    $pornstar->name = ucwords($pornstarTxt);
                    $pornstar->permalink = str_slug($pornstarTxt);
                    $sceneRND = $pornstar->scenes()->select('preview')->orderByRaw("RAND()")->first();
                    if ($sceneRND) {
                        $pornstar->thumbnail = $sceneRND->preview;
                    }
                    $pornstar->save();
                }

                $scenePornstar = new ScenePornstar();
                $scenePornstar->scene_id = $scene->id;
                $scenePornstar->pornstar_id = $pornstar->id;
                $scenePornstar->save();

                // Si no hemos podido ponerle thumbnails, reintentamos por si era el primero
                if (!$sceneRND) {
                    $sceneRND = $pornstar->scenes()->select('preview')->orderByRaw("RAND()")->first();
                    if (!$sceneRND) {
                        rZeBotUtils::message("[ERROR] Pornstar thumbnail not found", "red");
                    } else {
                        $sceneRND = $pornstar->scenes()->select('preview')->orderByRaw("RAND()")->first();
                        $pornstar->thumbnail = $sceneRND->preview;
                        $pornstar->save();
                    }
                }
                rZeBotUtils::message("[ADD/UPDATING FOR PORNSTAR] " . ucwords($pornstarTxt), "cyan", true, false);
            }
        }
    }

    public function processTags($video, $site_id, $scene, $languages)
    {
        if ( $video["tags"] == null ) {
            return false;
        }

        // tags
        foreach ($video["tags"] as $tagTxt) {

            if (strpos($tagTxt, ',') !== false || strpos($tagTxt, ';') !== false) {
                rZeBotUtils::message("[Skipping Tag] " . ucwords($tagTxt), "cyan", true, false);
                continue;
            }

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

}