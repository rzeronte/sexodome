<?php
namespace App\rZeBot;

use Elasticsearch\ClientBuilder;
use Goutte\Client;
use App\Model\Host;
use App\Model\Video;
use App\Model\Tag;
use App\Model\Domain;
use App\Model\Language;
use App\Model\Scene;
use App\Model\SceneTranslation;
use App\Model\TagTranslation;
use App\Model\SceneTag;
use App\Model\CategoryTranslation;
use App\Model\SceneCategory;
use App\Model\Category;
use App\Model\Site;

use DB;

class rZeBotUtils
{
    public $validExtensions;
    public $blacWordskList;

    public function __construct() {
        $this->invalidExtensions = array(
            '.exe',
            '.rar',
            '.zip',
            '.txt',
            '.flw',
            '.xml',
            '.json',
            ':81',
        );

        $this->blacWordskList = array(
            'ahora', 'antes', 'después', 'tarde', 'luego', 'ayer', 'temprano', 'ya', 'todavía', 'anteayer',
            'aún', 'pronto', 'hoy', 'aquí', 'ahí', 'allí', 'cerca', 'lejos', 'fuera', 'dentro', 'alrededor',
            'aparte', 'encima', 'debajo', 'delante', 'detrás', 'así', 'bien', 'mal', 'despacio', 'deprisa',
            'como', 'mucho', 'poco', 'muy', 'casi', 'todo', 'nada', 'algo', 'medio',
            'demasiado', 'bastante', 'más', 'menos', 'además', 'incluso', 'también', 'sí',
            'también', 'asimismo', 'no', 'tampoco', 'jamás', 'nunca', 'acaso', 'quizá',
            'tal vez', 'a lo mejor', 'ser'
        );
    }

    static function checkSubDomainAccess($locale) {
        $urlData = parse_url($_SERVER["HTTP_HOST"]);
        $path = $urlData["path"];

        $parts = explode(".", $path);


        // subdomain.assassinsporn.com
        if (count($parts) == 3) {
            $subdomain = $parts[0];
            $domain    = $parts[1];
            $ext       = $parts[2];
            $full = $domain.".".$ext;

            if ($subdomain == "www" && $full == rZeBotCommons::getMainPlataformDomain()) {
                return true;
            }else if ($subdomain == "accounts" && $full == rZeBotCommons::getMainPlataformDomain()) {
                return false;
            }

        }

        if (count($parts) == 2) {
            $domain    = $parts[0];
            $ext       = $parts[1];
            $full = $domain.".".$ext;

            if ($full == rZeBotCommons::getMainPlataformDomain()) {
                return true;
            }
        }

        return false;

    }

    static function getSiteFromHost() {
        $urlData = parse_url($_SERVER["HTTP_HOST"]);
        $path = $urlData["path"];

        $parts = explode(".", $path);

        if (count($parts) == 2 && $_SERVER["HTTP_HOST"] === rZeBotCommons::getMainPlataformDomain()) {
            // ----------------------------------- Dominio de la propia plataforma formato 'domain.com'
            return false;
        } elseif (count($parts) == 2 && $_SERVER["HTTP_HOST"] != rZeBotCommons::getMainPlataformDomain()) {
            // ----------------------------------- Dominio externo formato 'domain.com'
            $domain = $parts[0];
            $ext = $parts[1];
            $fullDomain = $domain . "." . $ext;

            return Site::where('domain', $fullDomain)->first();

        } elseif (count($parts) == 3 && $_SERVER["HTTP_HOST"] === "accounts.".rZeBotCommons::getMainPlataformDomain()) {
            // ----------------------------------- Dominio de miembros formato 'accounts.domain.com'
            return false;

        } elseif (count($parts) == 3 && $parts[0] == 'www' && $_SERVER["HTTP_HOST"] === "www.".rZeBotCommons::getMainPlataformDomain()) {
            // ----------------------------------- Dominio de la propia plataforma formato 'www.domain.com'
            return false;
        } elseif (count($parts) == 3 && $parts[0] == 'www' && $_SERVER["HTTP_HOST"] != "www.".rZeBotCommons::getMainPlataformDomain()) {
            // ----------------------------------- Dominio externo formato 'www.domain.com'
            $domain = $parts[1];
            $ext    = $parts[2];
            $fullDomain = $domain.".".$ext;
            $site = Site::where('domain', $fullDomain)->first();
            if (!$site) {
                abort("403", "Domain not allowed");
                return false;
            } else {
                return $site;
            }
        } elseif (count($parts) == 3 && $parts[0] !== 'www' && $_SERVER["HTTP_HOST"] != "www.".rZeBotCommons::getMainPlataformDomain()) {
            // ----------------------------------- Subdominio de la plataforma formato 'subdominio.plataforma.com'
            $subdomain = $parts[0];
            $site = Site::where('name', $subdomain)->first();

            if (!$site) {
                abort("403", "Subdomain not allowed");
                return false;
            } else {
                return $site;
            }

        } elseif (count($parts) > 3) {
            return false;
        }

        return false;
    }


    /**
     * format console message
     *
     * @param $message
     * @param string $type
     */
    static function message($message, $type = 'default', $returnLine = true) {
        switch($type) {
            case 'green':
                $initColor = "\033[32m";
                break;
            case 'red':
                $initColor = "\033[31m";
                break;
            case 'yellow':
                $initColor = "\033[1;33m";
                break;
            case 'blue':
                $initColor = "\033[34m";
                break;
            case 'brown':
                $initColor = "\033[33m";
                break;
            case 'cyan':
                $initColor = "\033[36m";
                break;
            default:    //white
                $initColor = "\033[0m";
        }

        $endColor = "\033[0m";
        echo $initColor.$message.$endColor;
        if ($returnLine == true) {
            echo PHP_EOL;
        }
    }

    /**
     * check if url already exists in database
     *
     * @param $url
     * @return bool
     */
    public function existsHostInDatabase($url) {
        $hosts = Host::where('host','like', $url)->count();

        if ($hosts !== 0) {
            return true;
        }

        return false;
    }


    /**
     * slugify
     *
     * @param $text
     * @return mixed|string
     */
    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }

    static public function getTagsByLanguage($language_id)
    {
        $tags = Tag::where('language_id',"=", $language_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();
        return $tags;
    }

    static public function getTagsByPermalinksForLanguage($permalinks, $language_id)
    {
        $tags = DB::table('tags')
            ->where("language_id", "=", $language_id)
            ->whereIn('permalink', $permalinks)
            ->get();

        return $tags;
    }

    static public function parseCSV($site_id, $feed, $max, $mapped_colums, $feed_config, $tags, $categories, $only_update, $rate, $minViews, $minDuration, $default_status, $test)
    {
        $fila = 1;
        $languages = Language::all();
        $added = 0;
        $fileCSV = rZeBotCommons::getDumpsFolder().$feed->file;

        if (!file_exists($fileCSV)) {
            if ($feed->is_compressed !== 1) {
                if (!file_exists($fileCSV)) {
                    rZeBotUtils::message("[WARNING] No existe el fichero '$fileCSV', intentando descargar...".PHP_EOL, "yellow");
                    $cmd = "wget -c '" . $feed->url . "' --output-document=". $fileCSV;
                    exec($cmd);
                }
            } else {
                rZeBotUtils::message("[WARNING] El fichero de la url '$feed->url' está comprimido. Descargamos con nombre original, pero detenemos inserción.".PHP_EOL, "yellow");
                $cmd = "wget -c '" . $feed->url . "' --directory-prefix=".rZeBotCommons::getDumpsFolderTmp();
                exec($cmd);
                rZeBotUtils::message("[STOP] Ejecución detenida, Debes descomprimir el fichero del channel.".PHP_EOL, "yellow");
                exit;
            }
        }

        if (($gestor = fopen($fileCSV, "r")) !== FALSE) {
            while (($datos = fgetcsv($gestor, 30000, $feed_config["fields_separator"])) !== FALSE) {

                $fila++;

                if ($feed_config["skip_first_list"] == true && $fila == 2) {
                    rZeBotUtils::message("[WARNING] Saltando primera linea del fichero...", "yellow");
                    continue;
                }

                // check total cols matched CSV <-> config array
                if ($feed_config["totalCols"] != count($datos)) {
                    rZeBotUtils::message("Error en el número de columnas, deteniendo ejecución...", "red");
                    print_r($datos);
                    continue;
                }

                // check limit import
                if ($max != 'false' && is_numeric($max) && $added >= $max) {
                    rZeBotUtils::message("[DONE] Alcanzado número máximo de escenas indicado: $max", "cyan");
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
                        if (in_array($tagTxt, $tags)) {
                            $mixed_check = true;
                        }
                    }
                }

                if (!$mixed_check) {
                    continue;
                }

                // check categories matched
                $mixed_check = true;
                if ($categories !== false) {
                    $mixed_check = false;
                    foreach ($video["categories"] as $categoryTxt) {
                        if (in_array($categoryTxt, $categories)) {
                            $mixed_check = true;
                            rZeBotUtils::message("Found category: " . $categoryTxt, "green");
                        }
                    }
                }

                if (!$mixed_check) {
                    rZeBotUtils::message("mixed_check continue;", "yellow");
                    continue;
                }

                // preview is used to check if already exists
                if(Scene::where('preview', $video["preview"])->where('site_id', $site_id)->count() == 0) {
                    $mixed_check = true;

                    if ($only_update !== "false") {
                        $mixed_check = false;
                        rZeBotUtils::message("SKIPPED", "yellow");
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
                        rZeBotUtils::message("TAGS/CATEGORIES -> SKIPPED", "yellow");
                    }

                    // rate check
                    if ($rate !== 'false') {
                        if ($video["rate"] < $rate) {
                            $mixed_check = false;
                            rZeBotUtils::message("RATE: Rate insuficiente", "yellow");
                        }
                    }

                    // views check
                    if ($minViews !== 'false') {
                        if ($video["views"] < $minViews) {
                            $mixed_check = false;
                            rZeBotUtils::message("VIEWS: Views insuficiente", "yellow");
                        }
                    }

                    // duration check
                    if ($minDuration !== 'false') {
                        if ($video["duration"] < $minDuration) {
                            $mixed_check = false;
                            rZeBotUtils::message("DURATION: duration insuficiente", "yellow");
                        }
                    }

                    if ($mixed_check) {
                        $added++;

                        if ($test !== 'false') {
                            rZeBotUtils::message("[TEST MAPPING FROM FEED", "yellow");
                            print_r($video);
                            exit;
                        }

                        rZeBotUtils::message("[SUCCESS - $fila] Creando escena '". $video['title']."' (Duration: ".$video["duration"].")", "cyan");

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

//                        // categories
//                        foreach ($video["categories"] as $categoryTxt) {
//                            if(strlen($categoryTxt) == 0) {
//                                continue;
//                            }
//
//                            if (CategoryTranslation::join('categories', 'categories.id', '=', 'categories_translations.category_id')
//                                    ->where('categories.site_id', $site_id)
//                                    ->where('name', $categoryTxt)
//                                    ->where('language_id', 2)
//                                    ->count() == 0)
//                            {
//
//                                rZeBotUtils::message("Creando categoría $categoryTxt", "green");
//
//                                $category = new Category();
//                                $category->status = 1;
//                                $category->text = $categoryTxt;
//                                $category->site_id = $site_id;
//                                $category->save();
//                                $category_id = $category->id;
//
//                                // tag translations
//                                foreach ($languages as $language) {
//                                    $categoryTranslation = new CategoryTranslation();
//                                    $categoryTranslation->language_id = $language->id;
//                                    $categoryTranslation->category_id = $category_id;
//
//                                    if ($language->id == 2) {
//                                        $categoryTranslation->permalink = str_slug($categoryTxt);
//                                        $categoryTranslation->name = $categoryTxt;
//                                    }
//
//                                    $categoryTranslation->save();
//                                }
//                            } else {
//                                $categoryTranslation = CategoryTranslation::join('categories', 'categories.id', '=', 'categories_translations.category_id')
//                                    ->where('categories.site_id', $site_id)
//                                    ->where('name', $categoryTxt)
//                                    ->where('language_id', 2)
//                                    ->first()
//                                ;
//                                $category_id = $categoryTranslation->category_id;
//                            }
//
//                            $sceneCategory = new SceneCategory();
//                            $sceneCategory->scene_id = $scene->id;
//                            $sceneCategory->category_id = $category_id;
//                            $sceneCategory->save();
//                        }
                    }
                } else {
                    // Si ya existe recategorizamos categorias
                    rZeBotUtils::message("[WARNING] Scene ya existente, saltando...", "yellow");
                }
            }

            fclose($gestor);
        }
    }

    public static function checkCFDNS($domain)
    {
        $result = dns_get_record($domain);

        $founded = 0;

        foreach ($result as $record_dns) {
            if ($record_dns["type"] == "NS") {
                if ($record_dns["target"] == "ivan.ns.cloudflare.com" || $record_dns["target"] == "nola.ns.cloudflare.com") {
                    $founded++;
                }
            }
        }

        if ($founded !== 2) {
            Request::session()->flash('error_domain', 'Domain <'.trim(Input::get('domain')).'> font have right DNS');

            return false;
        }

        return true;
    }
    /**
     * Check if tag is valid for Category
     *
     * @param $tag
     * @return bool
     */
    public static function isValidTag($tag) {

        // menores de 2 carácteres
        if (strlen($tag) < 2) {
            return false;
        }

        // longitud cero
        if (!strlen($tag)) {
            return false;
        }

        // números
        if (is_numeric($tag)) {
            return false;
        }

        // mayores de 2 palabras
        if (count(explode(" ", $tag)) > 3) {
            return false;
        }

        // que contengan alguno de estos textos
        if (str_contains($tag, array(".com", ".net", ".es", ".xxx", ".tv", ".co"))) {
            return false;
        }

        return true;
    }

    public static function transformTagForCategory($tag) {
        $transformed = str_replace("-", " ", $tag);

        return $transformed;
    }
}
