<?php

namespace App\rZeBot;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;
use App\Model\Language;
use App\Model\Site;
use Illuminate\Support\Facades\Storage;

class sexodomeKernel extends Controller {

    public $language;
    public $languages;
    public $perPage;
    public $videoStatus;
    public $tagsStatus;
    public $agent;
    public $perPageScenes;
    public $perPagePornstars;
    public $perPageTags;
    public $perPageJobs;
    public $site;
    public $routeParameters;
    public $cloudFlareCfg;
    public $perPageCategories;
    public $perPagePanelPornstars;
    public $sex_types;
    public $secret;
    public $isPreview;

    public function __construct()
    {
        if (!$this->isSexodomeBackend() && !$this->isSexodomeFront()) {
            $this->setSiteFromDomainOrFail();
            $this->instanciateFrontEndSite();
        } else {
            App::instance('site', false);
        }

        // php artisan active flag runningInConsole
        // but in 'testing' too and we want continue...
        if (App::runningInConsole() and App::environment() !== 'testing') {
            return false;
        }

        $this->setLanguageOrFail();

        // per page setups
        $this->perPage = 48;
        $this->perPageScenes = 48;
        $this->perPageTags = 30;
        $this->perPageCategories = 60;
        $this->perPageJobs = 15;
        $this->perPagePanelPornstars = 12;
        $this->perPagePornstars = 48;
        $this->perPagePanelCategories = 20;
        $this->secret = "3%3_K3/)!-8jZ&";

        // sex types
        $this->sex_types = [
            'straigth' => 1,
            'gay'      => 2,
            'trans'    => 3
        ];

        // all valid languages
        $this->languages = Cache::remember('languages', env('MEMCACHED_QUERY_TIME', 30), function(){
            return Language::where('status', 1)->orderBy('code', 'asc')->get();
        });

        // status video config mapping
        $this->videoStatus = array(
            'active'   => 1,
            'inactive' => 0,
        );

        // status tags config mapping
        $this->tagsStatus = array(
            'inactive' => 0,
            'active'   => 1,
        );

        // cloudflarecredentials and setup
        $this->cloudFlareCfg = [
            'ip'    => '91.121.81.154',
            'zone'  => 'sexodome.com',
            'email' => 'assassinsporn@assassinsporn.com',
            'key'   => 'c71115618735b641d5c573fa72f9f6b5372d8'
        ];

        $this->agent = new Agent();
    }

    public function isSexodomeFront()
    {
        if (!isset($_SERVER["HTTP_HOST"])) {
            return false;
        }

        $urlData = parse_url($_SERVER["HTTP_HOST"]);
        if ($urlData["host"] == sexodomeKernel::getMainPlataformDomain() ||
            $urlData["host"] == "www." . sexodomeKernel::getMainPlataformDomain()
        ) {
            return true;
        }

        return false;
    }

    public function isSexodomeBackend()
    {
        if ( App::environment() == 'testing') {
            return false;
        }

        if (!isset($_SERVER["HTTP_HOST"])) {
            return false;
        }

        $urlData = parse_url($_SERVER["HTTP_HOST"]);

        if ($urlData["host"] === "accounts.".sexodomeKernel::getMainPlataformDomain()) {
            // ----------------------------------- Dominio de miembros formato 'accounts.domain.com'
            return true;
        }

        return false;
    }

    public function setSiteFromDomainOrFail()
    {
        if ( App::environment() == 'testing') {
            $this->site = Site::where('id', env('DEMO_SITE_ID'))->where('status', 1)->first();
            return;
        }

        if (!isset($_SERVER["HTTP_HOST"])) {
            return false;
        }

        $urlData = parse_url($_SERVER["HTTP_HOST"]);
        $domain = $urlData["host"];

        // Preview is not cached
        if ($this->isPreview) {
            $site = Site::where('domain', $domain)->first();
        } else {
            $site = Cache::remember('site_'.$domain, env('MEMCACHED_QUERY_TIME', 5), function() use ($domain) {
                return Site::where('domain', $domain)->where('status', 1)->first();
            });

            if (!$site) {
                echo "no hay site";exit;
                abort(404, 'Site not available');
            }
        }

        if (!$site) {
            abort(403, 'Site not available');
        }

        $this->setSite($site);
    }

    public function instanciateFrontEndSite()
    {
        if ( App::environment() == 'testing') {
            $site = Site::where('id', env('DEMO_SITE_ID'))->where('status', 1)->first();
            App::instance('site', $site);
            return;
        }

        if (App::runningInConsole()) {

            $site = Site::where('id', env('DEMO_SITE_ID'))->where('status', 1)->first();
            if ($site) {
                App::instance('site', $site);
            } else {
                App::instance('site', false);
            }
            return;
        }

        if (isset($_SERVER['HTTP_HOST'])) {
            $urlData = parse_url($_SERVER['HTTP_HOST']);
            $domain = $urlData["host"];
            $site = Site::where('domain', $domain)->where('status', 1)->first();
            if ($site) {
                App::instance('site', $site);
            } else {
                if (Request::input('p') != "") {
                    $site =  Site::where('domain', $domain)->first();

                    if (!$site) {
                        App::instance('site', false);
                    } else {
                        if ($this->successPreviewCode($site->getHost())) {
                            $this->isPreview = true;
                            App::instance('site', $site);
                        } else {
                            App::instance('site', false);
                        }
                    }
                } else {
                    App::instance('site', false);
                }
            }
        }
    }

    public function setLanguage($language_id = false)
    {
        if ($language_id !== false) {
            $this->language = Cache::remember('language_'.$language_id, env('MEMCACHED_QUERY_TIME', 30), function() use ($language_id) {
                return Language::where('id', '=', $language_id)->first();
            });

            App::setLocale($this->language->code);
        } else {
            $this->language = Cache::remember('language_'.App::getLocale(), env('MEMCACHED_QUERY_TIME', 30), function() {
                return Language::where('code', '=', App::getLocale())->first();
            });
        }
    }

    public function setLanguageOrFail()
    {
        if ($this->isSexodomeBackend()) {
            $this->setLanguage();
        } elseif ($this->isSexodomeFront()) {
            $this->setLanguage(env('DEFAULT_LANGUAGE_ID', 2));
        } else {
            $this->setLanguage($this->site->language->id);
        }
    }

    public function setSite($site)
    {
        $this->site = $site;
    }

    public static function getMainPlataformDomain()
    {
        return env("MAIN_PLATAFORMA_DOMAIN", "sexodome.loc");
    }

    public static function getLogosFolder()
    {
        return env("FOLDER_LOGOS", "../public/logos/");
    }

    public static function getFaviconsFolder()
    {
        return env("FOLDER_FAVICONS", "../public/favicons/");
    }

    public static function getHeadersFolder()
    {
        return env("FOLDER_HEADERS", "../public/headers/");
    }

    public static function getDumpsFolder()
    {
        return env("DEFAULT_DUMPS_FOLDER", "./dumps/");
    }

    public static function getDumpsFolderTmp()
    {
        return sexodomeKernel::getDumpsFolder()."tmp/";
    }

    public static function getThumbnailsFolder()
    {
        return env("DEFAULT_THUMBNAILS_FOLDER", "../thumbnails/");
    }

    public function getSite()
    {
        return $this->site;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function getLanguages()
    {
        return $this->languages;
    }

    public function getUA() {
        return $this->agent;
    }

    public static function downloadDump($feed)
    {
        $fileCSV = sexodomeKernel::getDumpsFolderTmp().$feed->file;

        $cfg = new $feed->mapping_class;
        $feedConfig = $cfg->configFeed();

        // Si el fichero del feed no existe, intentamos descargar
        if ($feed->is_compressed !== 1) {
            if (isset($feedConfig["is_xml"])) {
                if ($feedConfig["is_xml"] == true) {
                    rZeBotUtils::message("[downloadDump] Downloading json file $fileCSV", "info",'kernel');
                    $cmd = "wget -c '" . $feed->url . "' --output-document=". $fileCSV.".json";
                    exec($cmd);
                    $json_string = file_get_contents($fileCSV.".json");
                    sexodomeKernel::jsonToCSV($feed, $json_string, $fileCSV);
                }
            } else {
                // Si no está comprimido directamente descargamos con el nombre en bbdd (forzamos nombre para mayor ordenación)
                if (!file_exists($fileCSV)) {
                    rZeBotUtils::message("[downloadDump] Downloading file $fileCSV", "info",'kernel');
                    $cmd = "wget -c '" . $feed->url . "' --output-document=". $fileCSV;
                    exec($cmd);
                } else {
                    rZeBotUtils::message("[downloadDump] Already exists $fileCSV", "warning",'kernel');
                }

            }
        } else {
            $tgz = $gz = $zip = false;

            if ((substr($feed->url, -8) == '.tar.gz') OR (substr($feed->url, -4) == '.tgz')) {
                $tgz = true;
                $ext = '.tar.gz';
            } elseif (substr($feed->url, -4) == '.gz') {
                $ext = '.gz';
                $gz = true;
            } elseif (substr($feed->url, -4) == '.zip') {
                $ext = '.zip';
                $zip = true;
            }

            // Si es un fichero comprimido
            $compressFile = $fileCSV.$ext;
            $cmd = "wget -c '" . $feed->url . "' --directory-prefix=".sexodomeKernel::getDumpsFolderTmp() . " --output-document=" . $compressFile;
            exec($cmd);

            rZeBotUtils::message("[downloadDump] Extracting dump $compressFile", "warning",'kernel');
            if ($zip) {
                $cmd = "unzip $compressFile -d ". sexodomeKernel::getDumpsFolderTmp();
            } elseif($tgz) {
                $cmd = "tar xf $compressFile -C ". sexodomeKernel::getDumpsFolderTmp();
            }
            exec($cmd);

            $cmd = "mv " . sexodomeKernel::getDumpsFolderTmp() . $feed->compressed_filename ." " . sexodomeKernel::getDumpsFolderTmp() . $feed->file;
            rZeBotUtils::message("[downloadDump] Renaming file $cmd", "warning",'kernel');
            exec($cmd);
        }

        return $fileCSV;
    }

    public static function downloadDumpDeleted($feed)
    {
        $fileCSV = sexodomeKernel::getDumpsFolderTmp()."deleted_".$feed->file;

        if (!file_exists($fileCSV)) {
            rZeBotUtils::message("[downloadDump] Downloading file $fileCSV", "info",'kernel');
            $cmd = "wget -c '" . $feed->url_deleted . "' --output-document=". $fileCSV;
            exec($cmd);
        } else {
            rZeBotUtils::message("[downloadDump] Already exists $fileCSV", "warning",'kernel');
        }

        return $fileCSV;
    }

    public static function downloadThumbnail($src, $sceneForRemoval = false, $overwrite = null, $fileLog = 'kernel')
    {
        $filename = md5($src).".jpg";   // El nombre del fichero esel md5 de la img tal como viene

        // Fix para cuando redtube viene con '//thumbs.redtube'. La imágen es buena pero no se puede
        // descargar mediante cURL sin añadirle el 'http:'
        $start_url = substr($src, 0, 2);
        if ($start_url == "//") {
            $src = "http:" . $src;
        }

        if (filter_var($src, FILTER_VALIDATE_URL) === false) {
            rZeBotUtils::message("[downloadThumbnail] Invalid thumbnails '$src'", "error",$fileLog);
            if ($sceneForRemoval !== false) {
                rZeBotUtils::message("[downloadThumbnail] Delete scene($sceneForRemoval->id) '$src'", "warning", $fileLog);
                $sceneForRemoval->delete();
            }

            return false;
        }

        $filepath = base_path() . "/" . sexodomeKernel::getThumbnailsFolder().$filename;

        if ($overwrite == false) {
            if (file_exists($filepath)) {
                //rZeBotUtils::message("[downloadThumbnail] Already exists '$src' in $filepath", "warning", $fileLog);
                return true;
            }
        }

        try {
            $fp = fopen ( $filepath , 'w+');
            $ch = curl_init( str_replace(" ", "%20", $src) );  // cambiamos los espacios por %20
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
            curl_exec($ch);

            rZeBotUtils::message("[downloadThumbnail] Downloading thumbnail '$src'", "kernel", $fileLog);
        } catch(\Exception $e) {
            rZeBotUtils::message("[downloadThumbnail] Thumbnail can't be downloaded: $src' in '$filepath'. Deleting scene... ", "error", $fileLog);
            if ($sceneForRemoval !== false) {
                $sceneForRemoval->delete();
            }

            return false;
        }

        try {
            sexodomeKernel::redimensionateThumbnail($filepath, 190, 135);
        } catch(\Exception $e) {
            rZeBotUtils::message("[downloadThumbnail] Thumbnail can't be resized: '$filepath'. Deleting scene... ", "error", $fileLog);
            if ($sceneForRemoval !== false) {
                $sceneForRemoval->delete();
            }

            return false;
        }

        return true;
    }

    public static function redimensionateThumbnail($file, $width, $height)
    {
        rZeBotUtils::message("[redimensionateThumbnail] Resizing thumbnail '$file'", "info",'kernel');

        $uploadedfile = $file;
        $src = \imagecreatefromjpeg($uploadedfile);
        list($width_origin, $height_origin) = getimagesize($uploadedfile);

        $tmp = imagecreatetruecolor($width, $height);

        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width_origin, $height_origin);
        imagejpeg($tmp, $file, 100);
    }

    public static function jsonToCSV($feed, $json, $filename)
    {
        if (!rZeBotUtils::isJson($json)) {
            rZeBotUtils::message("[jsonToCSV] Not JSON valid", "error",'kernel');
            return false;
        }

        $cfg = new $feed->mapping_class;

        $array = json_decode($json, true);
        $f = fopen($filename, 'w');
        rZeBotUtils::message("[jsonToCSV] $filename, Total: " . count($cfg->getVideosFromJSON($array)), "info",'kernel');

        if (!is_array($array)) {
            rZeBotUtils::message("[jsonToCSV] Not Array from JSON", "error",'kernel');
            return false;
        }

        foreach ($cfg->getVideosFromJSON($array) as $line) {
            $lineCSV = $cfg->getCSVLineFromJSON($line);
            fputcsv($f, array_values($lineCSV), "|");
        }

        fclose($f);
    }

    public static function getSitemapFile()
    {
        if (App::make('sexodomeKernel')->site) {
            $sitemapFile = App::make('sexodomeKernel')->site->getSitemap();
            $file = Storage::disk('web')->get('sitemaps/'.$sitemapFile);
        } else {
            // www.sexodome.com sitemap
            $file = Storage::disk('web')->get('sexodome-sitemap.xml');
        }

        return response($file, "200")->header('Content-Type', "application/xml");
    }

    public function getPreviewCode($host)
    {
        $code = $host . "@@@@". "3%3_K3/)!-8jZ&" . "@@@@" . date('G');
        return md5($code);
    }

    public function successPreviewCode($host)
    {
        if (Request::input('p') == $this->getPreviewCode($host)) {
            return true;
        }

        return false;
    }

}
