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
use Log;
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
        $transformed = utf8_encode($transformed);
        $transformed = trim($transformed);

        return $transformed;
    }

    public static function downloadDump($feed)
    {
        $fileCSV = rZeBotCommons::getDumpsFolderTmp().$feed->file;

        // Si el fichero del feed no existe, intentamos descargar
        if ($feed->is_compressed !== 1) {
            // Si no está comprimido directamente descargamos con el nombre en bbdd (forzamos nombre para mayor ordenación)
            if (!file_exists($fileCSV)) {
                rZeBotUtils::message("[DOWNLOADING FILE] $fileCSV", "green", true, false);
                $cmd = "wget -c '" . $feed->url . "' --output-document=". $fileCSV;
                exec($cmd);
            }
        } else {
            $tgz = $gz = $zip = false;

            // miramos si va comprimido
            if ((substr($feed->url, -8) == '.tar.gz') OR (substr($feed->url, -4) == '.tgz')) {
                $tgz = true;
                $ext = '.tar.gz';  // lo descargaremos con extensión .tar.gz
            } elseif (substr($feed->url, -4) == '.gz') {
                $ext = '.gz';  // lo descargaremos con extensión .gz
                $gz = true;
            } elseif (substr($feed->url, -4) == '.zip') {
                $ext = '.zip';  // lo descargaremos con extensión .gz
                $zip = true;
            }

            // Si es un fichero comprimido
            $compressFile = $fileCSV.$ext;
            $cmd = "wget -c '" . $feed->url . "' --directory-prefix=".rZeBotCommons::getDumpsFolderTmp() . " --output-document=" . $compressFile;
            exec($cmd);

            rZeBotUtils::message("[EXTRACTING DUMP] $compressFile", "yellow", true, false);
            if ($zip) {
                $cmd = "unzip $compressFile -d ". rZeBotCommons::getDumpsFolderTmp();
            } elseif($tgz) {
                $cmd = "tar xf $compressFile -C ". rZeBotCommons::getDumpsFolderTmp();
            }
            exec($cmd);

            $cmd = "mv " . rZeBotCommons::getDumpsFolderTmp() . $feed->compressed_filename ." " . rZeBotCommons::getDumpsFolderTmp() . $feed->file;
            rZeBotUtils::message("[RENAMING FILE] $cmd", "yellow", true, false);
            exec($cmd);
        }

        return $fileCSV;
    }
}
