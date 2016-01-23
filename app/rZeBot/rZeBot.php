<?php
namespace App\rZeBot;

use App\Model\Host;
use App\Model\Video;
use Elasticsearch\ClientBuilder;
use Goutte\Client;

class rZeBot
{
    public $ESClient;
    public $goutteClient;
    public $visitedURLs;
    public $cacheURLs;
    public $maxLevel;
    public $Utils;
    public $time;
    public $huntingMode;

    public $formats;
    public $players;

    /**
     * construct
     *
     * @param int $depthMaxLevel
     * @param string $huntingMode
     */
    public function __construct($depthMaxLevel = 5, $huntingMode = 'hosts')
    {
        $this->huntingMode = $huntingMode;

        // elastic search
        $this->ESClient = ClientBuilder::create()->build();

        // goutte client
        $this->goutteClient = new Client([
            'timeout'         => 300,
            'connect_timeout' => 300,
            'allow_redirects' => true
        ]);

        // cache urls
        $this->visitedURLs = array();
        $this->cacheURLs= array();

        // utils
        $this->Utils = new rZeBotUtils();
        // maxlevel
        $this->maxLevel = $depthMaxLevel;

        // timming
        $this->time = time();
    }

    /**
     * start crawler for url
     *
     * @param $url
     */
    public function initCrawlForUrl($url) {
        $currentLevel = 0;
        $this->Utils->message("Start level $currentLevel crawling for: " . $url . PHP_EOL, 'cyan');
        $urlData = parse_url($url);

        $this->recursiveCrawlerForUrl($url, $currentLevel, $urlData['host']);
    }

    /**
     * recursive crawler for url
     *
     * @param $url
     * @param $currentLevel
     * @param $origin
     * @return bool|void
     */
    public function recursiveCrawlerForUrl($url, $currentLevel, $origin)
    {
        if ($currentLevel > $this->maxLevel) {
            //$this->Utils->message('[warning] MaxDepthLevevel achieved', 'red');
            return;
        }

        // mailtos / crawleable / file extension
        if (!$this->Utils->checkIsValidUrl($url, $origin)) {
            return false;
        }

        // cached current sessión
        if (in_array($url, $this->visitedURLs)) {
            //$this->Utils->message(PHP_EOL."[WARNING] URL already visited: ".$url.PHP_EOL, 'red');
            return false;
        }

        // ***************************************************** launch crawler
        $crawler = $this->goutteClient->request('GET', $url);
        //******************************************************

        $status_code = $this->goutteClient->getResponse()->getStatus();

        if($status_code != 200){
            $this->Utils->message(PHP_EOL."[ERROR] $status_code", 'red');
            return false;
        }

        // cached url
        $this->visitedURLs[] = $url;

        // get/set title
        $title = ($crawler->filter('title')->count() > 0) ? $crawler->filter('title')->text() : "title-not-available";

        // debug info
        $this->Utils->message(PHP_EOL."[".gmdate("H:i:s", (time() - $this->time) )."] ".count($this->visitedURLs)." | Lvl: $currentLevel: " . $url, 'white');
        $this->Utils->message(" | $title", 'green');

        // type crawling
        $currentLevel++;
        switch($this->huntingMode) {
            case 'hosts':

                if ($this->Utils->existsHostInDatabase($url)) {
                    $this->Utils->message(PHP_EOL . "[WARNING] URL already in BBDD hosts: " . $url, 'red');
                } else {
                    // host creation
                    $host = new Host();
                    $host->title = $title;
                    $host->host = $url;
                    $host->from = $origin;
                    $host->save();
                }

                // launch recursive crawl
                $crawler->filter('body a')->each(function ($node) use ($currentLevel, $origin) {
                    $url = $node->extract(array('href'));
                    $url = $url[0];
                    $this->cacheURLs[] = $url;

                    $this->recursiveCrawlerForUrl($url, $currentLevel, $origin);
                });
                break;
            case 'videos':
                // html5 type
                $crawler->filter('video')->each(function ($node) use ($title, $url, $currentLevel) {
                    $src = $node->extract(array('src'))[0];
                    $poster = $node->extract(array('poster'))[0];

                    if ($this->Utils->existsVideoInDatabase($url)) {
                        $this->Utils->message(PHP_EOL . "[WARNING] URL already in BBDD videos: " . $url, 'red');
                    } else {
                        // video creation
                        $video = new Video();
                        $video->title = $title;
                        $video->url = $src;
                        $video->thumb = $poster;
                        $video->save();
                        $this->Utils->message(PHP_EOL."New HTML5 Video Found: ".$src, 'yellow');
                    }
                });

                // flowplayer type
                $crawler->filter('script')->each(function ($node) use ($url, $title) {
                    $html = $node->text();

                    if ($foundedAt = strpos($html, 'flowplayer(')) {
                        if ($foundedAt = strpos($html, 'playlist')) {
                            $endPos = strpos($html, "]", $foundedAt);
                            $list = substr($html, $foundedAt, $endPos);

                            preg_match("/url: '(.*)'/", $list, $match);
                            $thumb = $match[1];

                            if ($this->Utils->existsVideoInDatabase($url)) {
                                $this->Utils->message(PHP_EOL . "[WARNING] URL already in BBDD videos: " . $url, 'red');
                            } else {
                                // video creation
                                $video = new Video();
                                $video->title = $title;
                                $video->url = $url;
                                $video->thumb = $thumb;
                                $video->save();
                            }

                            $this->Utils->message(PHP_EOL."Flowplayer Video Found: ".$thumb, 'yellow');
                        }
                    }
                });

                // xhamster video type
                $crawler->filter('script')->each(function ($node) use ($url, $title) {
                    $html = $node->text();

                    if ($foundedAt = strpos($html, 'new XPlayer')) {

                        preg_match("/thumb: '(.*)'/", $html, $match);
                        $thumb = $match[1];

                        if ($this->Utils->existsVideoInDatabase($url)) {
                            $this->Utils->message(PHP_EOL . "[WARNING] URL already in BBDD videos: " . $url, 'red');
                        } else {
                            // video creation
                            $video = new Video();
                            $video->title = $title;
                            $video->url = $url;
                            $video->thumb = $thumb;
                            $video->save();
                        }
                        $this->Utils->message(PHP_EOL."xHamster Video Found: ".$thumb, 'yellow');
                    }
                });

                // launch recursive crawl
                $crawler->filter('body a')->each(function ($node) use ($currentLevel, $origin) {
                    $url = $node->extract(array('href'));
                    $url = $url[0];
                    $this->cacheURLs[] = $url;

                    $this->recursiveCrawlerForUrl($url, $currentLevel, $origin);
                });

                break;
        }
    }

    /**
     * init bot crawling
     *
     * @param $urls
     */
    public function init($urls, $truncate = false)
    {
        // hosts truncate
        if ($this->huntingMode == 'hosts') {
            if ($truncate == 'true') {
                $this->Utils->message('Truncate Hosts...'.PHP_EOL, 'cyan');
                Host::truncate();
            }
        }

        // video truncate
        if ($this->huntingMode == 'videos') {
            $this->Utils->message("Searching for players: " . implode(', ', $this->formats).PHP_EOL, 'yellow');
            $this->Utils->message("Searching for formats: " . implode(', ', $this->players).PHP_EOL, 'yellow');
            if ($truncate == 'true') {
                $this->Utils->message('Truncate Videos...'.PHP_EOL, 'cyan');
                Video::truncate();
            }
        }

        $this->Utils->message('Depth: ' . $this->maxLevel." | Truncate: " .$truncate.PHP_EOL.PHP_EOL, 'cyan');

        for($i=0; $i<count($urls); $i++)
        {
            $url = $urls[$i];
            $this->Utils->message(PHP_EOL.$i.") ".$url.PHP_EOL, 'brown');
            $this->initCrawlForUrl($url);
        }

        echo PHP_EOL;
    }

    /**
     * index a new document from data array (index, type, id, body)
     * @param $params
     * @return array
     */
    public function indexDocument($params)
    {
        $response = $this->ESClient->index($params);

        return $response;
    }

    /**
     * set formats for video hunting mode
     *
     * @param $formats
     */
    public function setFormats($formats) {
        $this->formats = $formats;
    }

    /**
     * set players for video hunting mode
     *
     * @param $players
     */
    public function setPlayers($players) {
        $this->players = $players;
    }

    public function initClicksMyself()
    {
        $url = 'http://www.assassinsporn.com?autoclick=1';
        $this->recursiveClick($url);
    }

    public function recursiveClick($url)
    {
        $crawler = $this->goutteClient->request('GET', $url);

        $home_horizontal_link = $crawler->selectLink('Click here if not can see normally.')->link();
        $result = $this->goutteClient->click($home_horizontal_link);

        $this->Utils->message($url.PHP_EOL, "green");
        sleep(rand(6, 8));
        print_r($result);
        $this->recursiveClick($url);
    }
}