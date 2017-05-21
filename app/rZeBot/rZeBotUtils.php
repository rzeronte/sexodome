<?php
namespace App\rZeBot;

use App\Model\Host;
use App\Model\Video;
use App\Model\Domain;
use App\Model\CategoryTranslation;
use App\Model\Category;
use App\Model\Site;
use Log;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class rZeBotUtils
{
    /**
     * get site or run exceptions from host
     *
     * @return bool
     */
    static function getSiteFromHost() {
        $urlData = parse_url($_SERVER["HTTP_HOST"]);
        $path = $urlData["path"];

        $parts = explode(".", $path);

        if (count($parts) == 2 && $_SERVER["HTTP_HOST"] === sexodomeKernel::getMainPlataformDomain()) {
            // ----------------------------------- Dominio de la propia plataforma formato 'domain.com'
            return false;
        } elseif (count($parts) == 2 && $_SERVER["HTTP_HOST"] != sexodomeKernel::getMainPlataformDomain()) {
            // ----------------------------------- Dominio externo formato 'domain.com'
            $domain = $parts[0];
            $ext = $parts[1];
            $fullDomain = $domain . "." . $ext;

            $site = Cache::remember('site_'.$fullDomain, env('MEMCACHED_QUERY_TIME', 30), function() use ($fullDomain) {
                return Site::where('domain', $fullDomain)->where('status', 1)->first();
            });

            return $site;

        } elseif (count($parts) == 3 && $_SERVER["HTTP_HOST"] === "accounts.".sexodomeKernel::getMainPlataformDomain()) {
            // ----------------------------------- Dominio de miembros formato 'accounts.domain.com'
            return false;
        } elseif (count($parts) == 3 && $parts[0] == 'www' && $_SERVER["HTTP_HOST"] === "www.".sexodomeKernel::getMainPlataformDomain()) {
            // ----------------------------------- Dominio de la propia plataforma formato 'www.domain.com'
            return false;
        } elseif (count($parts) == 3 && $parts[0] == 'www' && $_SERVER["HTTP_HOST"] != "www.".sexodomeKernel::getMainPlataformDomain()) {
            // ----------------------------------- Dominio externo formato 'www.domain.com'
            $domain = $parts[1];
            $ext    = $parts[2];
            $fullDomain = $domain.".".$ext;
            $site = Cache::remember('site_'.$fullDomain, env('MEMCACHED_QUERY_TIME', 30), function() use ($fullDomain) {
                return Site::where('domain', $fullDomain)->where('status', 1)->first();
            });

            if (!$site) {
                abort("403", "Domain not allowed");
                return false;
            } else {
                return $site;
            }
        } elseif (count($parts) == 3 && $parts[0] !== 'www' && $_SERVER["HTTP_HOST"] != "www.".sexodomeKernel::getMainPlataformDomain()) {
            // ----------------------------------- Subdominio de la plataforma formato 'subdominio.plataforma.com'
            $subdomain = $parts[0];
            $site = Cache::remember('site_'.$subdomain, env('MEMCACHED_QUERY_TIME', 30), function() use ($subdomain) {
                return Site::where('name', $subdomain)->where('status', 1)->first();
            });

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
    static function message($message, $type = 'default', $returnLine = true, $show = false) {
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
        Log::info($initColor.$message.$endColor);

        if ($show !== false) {
            echo $initColor.$message.$endColor;
            if ($returnLine == true) {
                echo PHP_EOL;
            }
        }
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

    /**
     * Download dump for a dump
     *
     * @param $feed
     * @return string
     */
    public static function downloadDump($feed)
    {
        $fileCSV = sexodomeKernel::getDumpsFolderTmp().$feed->file;

        $cfg = new $feed->mapping_class;
        $feedConfig = $cfg->configFeed();

        // Si el fichero del feed no existe, intentamos descargar
        if ($feed->is_compressed !== 1) {
            if (isset($feedConfig["is_xml"])) {
                if ($feedConfig["is_xml"] == true) {
                    rZeBotUtils::message("[DOWNLOADING JSON FILE] $fileCSV", "green", true, true);
                    $cmd = "wget -c '" . $feed->url . "' --output-document=". $fileCSV.".json";
                    exec($cmd);
                    $json_string = file_get_contents($fileCSV.".json");
                    rZeBotUtils::jsonToCSV($feed, $json_string, $fileCSV);
                }
            } else {
                // Si no está comprimido directamente descargamos con el nombre en bbdd (forzamos nombre para mayor ordenación)
                if (!file_exists($fileCSV)) {
                    rZeBotUtils::message("[DOWNLOADING FILE] $fileCSV", "green", true, false);
                    $cmd = "wget -c '" . $feed->url . "' --output-document=". $fileCSV;
                    exec($cmd);
                } else {
                    rZeBotUtils::message("[ALREADY EXISTSh] $fileCSV", "yellow", true, false);
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

            rZeBotUtils::message("[EXTRACTING DUMP] $compressFile", "yellow", true, false);
            if ($zip) {
                $cmd = "unzip $compressFile -d ". sexodomeKernel::getDumpsFolderTmp();
            } elseif($tgz) {
                $cmd = "tar xf $compressFile -C ". sexodomeKernel::getDumpsFolderTmp();
            }
            exec($cmd);

            $cmd = "mv " . sexodomeKernel::getDumpsFolderTmp() . $feed->compressed_filename ." " . sexodomeKernel::getDumpsFolderTmp() . $feed->file;
            rZeBotUtils::message("[RENAMING FILE] $cmd", "yellow", true, false);
            exec($cmd);
        }

        return $fileCSV;
    }

    /**
     * Download dump deletedfor a dump
     *
     * @param $feed
     * @return string
     */
    public static function downloadDumpDeleted($feed)
    {
        $fileCSV = sexodomeKernel::getDumpsFolderTmp()."deleted_".$feed->file;

        if (!file_exists($fileCSV)) {
            rZeBotUtils::message("[DOWNLOADING FILE] $fileCSV", "green", true, false);
            $cmd = "wget -c '" . $feed->url_deleted . "' --output-document=". $fileCSV;
            exec($cmd);
        } else {
            rZeBotUtils::message("[ALREADY EXISTSh] $fileCSV", "yellow", true, false);
        }

        return $fileCSV;
    }

    /**
     * update thumbnail for a category
     *
     * @param $category
     * @param null $exclude_scene_ids
     * @return bool
     */
    public static function updateCategoryThumbnail($category, $exclude_scene_ids = null, $ignore_locked = false)
    {
        $sceneRNDquery = $category->scenes()
            ->select('scenes.id', 'scenes.preview')
            ->orderBy('scenes.cache_order', 'desc');
        ;

        if ($exclude_scene_ids !== null) {
            $sceneRNDquery->whereNotIn('scenes.id', $exclude_scene_ids);
        }

        $sceneRND = $sceneRNDquery->first();

        if ($sceneRND) {
            $img = $sceneRND->preview;

            // la thumb es dependiente al idioma, seteamos todos con esta thumbnail
            foreach($category->translations()->where('language_id', $category->site->language_id)->get() as $translation) {

                if ($translation->thumb_locked == 1 && $ignore_locked == false) {
                    rZeBotUtils::message("[THUMBNAIL LOCKED (site_id: $category->site_id)] $category->text($category->id), tiene " . $category->scenes()->count() . " escenas | Excluyendo: ". count($exclude_scene_ids), "green", false, false);
                    continue;
                }

                rZeBotUtils::message("[UPDATING THUMBNAIL (site_id: $category->site_id)] $category->text($category->id), tiene " . $category->scenes()->count() . " escenas | Excluyendo: ". count($exclude_scene_ids), "green", false, false);
                $translation->thumb = $img;
                $translation->save();
            }

            return $sceneRND->id;
        } else {
            rZeBotUtils::message("[WARNING THUMBNAIL (site_id: $category->site_id)] $category->text($category->id), tiene " . $category->scenes()->count() . " escenas", "red", false, false);

            return false;
        }
    }

    public static function checkRedirection301($site)
    {
        $urlData = parse_url($_SERVER["HTTP_HOST"]);
        $path = $urlData["path"];
        $parts = explode(".", $path);

        if ($site != false) {
            // dominio externo formato www.dominio.com
            if (count($parts) == 3 && $parts[0] == 'www') {
                return "http://".$site->getHost();
            }
        }

        return false;
    }

    /**
     * Devuelve un array con las fechas entre los rangos indicados
     *
     * @param $first
     * @param $last
     * @param string $step
     * @param string $output_format
     * @return array
     */
    public static function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' )
    {

        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while( $current <= $last ) {

            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }

    /**
     * Descarga una thumbnail. Si se indica una escena, se eliminará si no se ha podido descargar su thumbnail
     * o esta no es válida.
     *
     * @param $src
     * @param string $i
     * @param bool $scene
     * @param null $overwrite
     * @return bool|void
     */
    public static function downloadThumbnail($src, $i = "", $scene = false, $overwrite = null)
    {
        $filename = md5($src).".jpg";   // El nombre del fichero esel md5 de la img tal como viene

        // Fix para cuando redtube viene con '//thumbs.redtube'. La imágen es buena pero no se puede
        // descargar mediante cURL sin añadirle el 'http:'
        $start_url = substr($src, 0, 2);
        if ($start_url == "//") {
            $src = "http:" . $src;
        }

        if (filter_var($src, FILTER_VALIDATE_URL) === false) {
            rZeBotUtils::message("[$i INVALID THUMBNAIL] $src", "red", false, false);
            if ($scene !== false) {
                rZeBotUtils::message("[$i DELETE SCENE] $src", "red", false, false);
                $scene->delete();
            }

            return;
        }
        
        $filepath = sexodomeKernel::getThumbnailsFolder().$filename;

        if ($overwrite == false) {
            if (file_exists($filepath)) {
                rZeBotUtils::message("[$i ALREADY EXISTS] $src", "yellow", false, false);
                return false;
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

            //$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            rZeBotUtils::message("[$i DOWNLOADING THUMBNAIL] $src", "cyan", false, false);

        } catch(\Exception $e) {
            rZeBotUtils::message("[$i ERROR DOWNLOAD THUMBNAIL. DELETING] $src", "red", false, false);
            if ($scene !== false) {
                $scene->delete();
            }
        }

        try {
             rZeBotUtils::redimensionateThumbnail($filepath, 190, 135);
        } catch(\Exception $e) {
            if ($scene !== false) {
                $scene->delete();
            }

        }

        return true;
    }

    public static function redimensionateThumbnail($file, $width, $height)
    {
        rZeBotUtils::message("[RESIZING THUMBNAIL] $file", "cyan", false, false);

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
            rZeBotUtils::message("[JSON TO CSV ERROR] Not JSON valid", "red", true, true);
            return false;
        }

        $cfg = new $feed->mapping_class;

        $array = json_decode($json, true);
        $f = fopen($filename, 'w');
        rZeBotUtils::message("[JSON TO CSV] $filename, Total: " . count($cfg->getVideosFromJSON($array)), "green", true, true);

        if (!is_array($array)) {
            rZeBotUtils::message("[JSON TO CSV ERROR] Not Array from JSON", "red", true, true);
            return false;
        }

        foreach ($cfg->getVideosFromJSON($array) as $line)
        {
            $lineCSV = $cfg->getCSVLineFromJSON($line);
            fputcsv($f, array_values($lineCSV), "|");
        }

        fclose($f);
    }

    public static function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function timesStart()
    {
        $time_start = microtime(true);

        return $time_start;
    }

    public static function timesEnd($time_start)
    {
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);

        rZeBotUtils::message("[TIEMPO DE EJECUCIÓN: ". gmdate("H:i:s", $execution_time)."]", "green", true, true);
    }

}