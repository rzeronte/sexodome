<?php
namespace App\rZeBot;

use Elasticsearch\ClientBuilder;
use Goutte\Client;
use App\Model\Host;
use App\Model\Video;
use App\Model\Tag;
use App\Model\Domain;
use App\Model\Language;

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

    static function checkDomainAccess() {
        $urlData = parse_url($_SERVER["HTTP_HOST"]);
        $path = $urlData["path"];

        $parts = explode(".", $path);

        // xx.assassinsporn.com
        if (count($parts) == 3) {

            $subdomain = $parts[0];

            if ($subdomain == "www") {
                return "en";
            }

            $language = Language::where('code', $subdomain)
                ->where('status', 1)
                ->first();

            if ($language) {
                return $language->code;
            }

            return false;
        }

        // assassinsporn.com
        if (count($parts) == 2) {
            return "en";
        }

        return false;
    }


    /**
     * format console message
     *
     * @param $message
     * @param string $type
     */
    public function message($message, $type = 'default') {
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
    }

    /**
     * check if url can be crawled
     *
     * @param $url
     * @return bool
     */
    public function checkIsValidUrl($url, $base_url) {
        $result = true;

        // mailto:
        if ($this->checkIfMailLink($url)) {
            //$this->message("[WARNING] URL is mail link: ".$url.PHP_EOL, 'red');
            $result = false;
        }

        // check if crawlable
        if (!$this->checkIfCrawlable($url)) {
            //$this->message("[WARNING] URL not crawlable: ".$url.PHP_EOL, 'red');
            $result = false;
        }

        // check if external
        if ($this::checkIfExternal($url, $base_url)) {
            //$this->message(PHP_EOL."[WARNING] URL External: ".$url.PHP_EOL, 'red');
            $result = false;
        }

        // check if bad extension
        if (!$this::checkFilterBadExtensions($url)) {
            $this->message("[WARNING] Invalid extension: ".$url.PHP_EOL, 'red');
            $result = false;
        }

        return $result;
    }

    /**
     * check extension for file links
     */
    public function checkFilterBadExtensions($url)
    {

        $filename = basename($url);

        foreach ($this->invalidExtensions as $extension) {
            if (strpos($url, $extension) > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * checks the uri if can be crawled or not
     * in order to prevent links like "javascript:void(0)" or "#something" from being crawled again
     * @param string $uri
     * @return boolean
     */
    public function checkIfCrawlable($uri) {
        if (empty($uri)) {
            return false;
        }

        $stop_links = array(//returned deadlinks
            '@^javascript\:void\(0\)$@',
            '@^#.*@',
        );

        foreach ($stop_links as $ptrn) {
            if (preg_match($ptrn, $uri)) {
                return false;
            }
        }

        return true;
    }

    /**
     * check if url is mail link
     * @param $url
     * @return bool
     */
    public function checkIfMailLink($url) {
        $result = strpos($url, 'mailto:');
        if ($result >= 0 and is_numeric($result)) {
            return true;
        }

        return false;
    }

    /**
     * check if the link leads to external site or not
     * @param string $url
     * @return boolean
     */
    public function checkIfExternal($url, $base_url) {

        $urlData = parse_url($url);
        if (isset($urlData['host'])) {
            if ($urlData['host'] != $base_url) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check Internet Connection.
     *
     * @param string $sCheckHost
     * @return bool
     */
    public function check_internet_connection($sCheckHost = '212.89.0.56')
    {
        return (bool) @fsockopen($sCheckHost, 80, $iErrno, $sErrStr, 3);
    }


    /**
     * go sleep mode
     */
    public function goSleepMode()
    {
        while(!$this->check_internet_connection()) {
            sleep(1);
            $this->Utils->message(PHP_EOL."[ERROR] Internet has gone! sleeping mode...", 'yellow');
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
     * check if video url already exists in database
     *
     * @param $url
     * @return bool
     */
    public function existsVideoInDatabase($url)
    {
        $videos = Video::where('url','like', $url)->count();

        if ($videos !== 0) {
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

    /**
     * regenerate permalinks from name tags
     */
    static public function parseAndFixTagsPermalink()
    {
        $tags = Tag::all();

        foreach($tags as $tag) {
            $permalink = rZeBotUtils::slugify($tag->name);
            $tag->permalink = $permalink;
            $tag->save();
        }

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

//        $tags = Tag::where('language_id',"=", $language_id)
//            ->where('status', 1)
//            ->orderBy('name')
//            ->get();

        return $tags;
    }

}


