<?php

namespace App\Console\Commands;

use App\Tag;
use App\TagClick;
use Illuminate\Console\Command;
use App\Scene;
use App\SceneTag;
use Log;
use Artisan;
use App\rZeBot\ProxyBot;
use App\rZeBot\rZeBotUtils;
use App\rZeBot\sexodomeKernel;
use App\Model\Site;

class BotClicker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zbot:clicker {--hdzog} {--exoclick}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Launch clicker for clicker_ids.csv';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $hdzog = $this->option("hdzog");
        $exoclick = $this->option("exoclick");

        if ($hdzog) {
            $this->runHDZog();
        }

        if ($exoclick) {
            $this->runExoclick();
        }
    }

    public function runExoclick()
    {
        $url = "http://pornokrachen.com?click";

        $url_test = "http://sexodome.com/webping";
        rZeBotUtils::message("[EXOCLICK ] ".$url, "yellow", true, true);

        $proxyList = $this->getProxies();

        foreach ($proxyList as $proxy) {
            rZeBotUtils::message("[PROXY] $proxy -> ".$url, "yellow", true, true);
            try {
                $responseTest = $this->connectProxy($proxy, $url_test);

                if ($responseTest == "ping") {
                    rZeBotUtils::message("[PROCESSING PROXY $proxy]", "cyan", true, true);
                    $cmd = "./chrome-click.sh " . $url. " ". $proxy;
                    exec($cmd);
                } else {
                    rZeBotUtils::message("[DISCARD PROXY $proxy]", "red", true, true);
                }

            } catch (\Exception $e) {
                rZeBotUtils::message("[DISCARD PROXY $proxy]", "red", true, true);
            }

        }
    }

    public function runHDZog()
    {
        $sourceFile = sexodomeKernel::getDumpsFolder()."bot_urls_hdzog.txt";
        rZeBotUtils::message("[GETTING PROXIES LIST FOR HDZOG] $sourceFile", "yellow", true, true);
        $url_test = "http://sexodome.com/webping";

        while(true) {
            $this->processProxies($sourceFile, $url_test);
        }
    }

    public function processProxies($sourceFile, $url_test)
    {
        $proxyList = $this->getProxies();

        rZeBotUtils::message("[PROCESSING $sourceFile]", "cyan", true, true);
        $lines = file($sourceFile, FILE_IGNORE_NEW_LINES);

        $validProxies = [];

        foreach($proxyList as $proxy) {
            $url = trim($lines[rand(0, count($lines)-1)]);
            if (strlen($proxy) && strlen($url)) {
                $responseTest = $this->connectProxy($proxy, $url_test);

                if ($responseTest == "ping") {
                    rZeBotUtils::message("[PROCESSING PROXY $proxy]", "cyan", true, true);
                    $cmd = "./chrome-play.sh " . $url. " ". $proxy;
                    exec($cmd);
                } else {
                    rZeBotUtils::message("[DISCARD PROXY $proxy]", "red", true, true);
                }
            }
        }
    }

    public function getProxies()
    {
        $list = new ProxyBot();
        $obj = $list->get();

        $proxyList = [];
        foreach ($obj as $prxobj) {
            if (!empty($prxobj['ip'])) {
                $proxyList[] = $prxobj['ip'] . ':' . $prxobj['port'];
            }
        }

        return $proxyList;
    }

    public function connectProxy($ip, $url) {
        rZeBotUtils::message("[TEST] $ip - $url", "yellow", true, true);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch, CURLOPT_PROXY, $ip);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'GET');
        curl_setopt ($ch, CURLOPT_HEADER, false);
        curl_setopt($ch,CURLOPT_TIMEOUT, 5);

        ob_start();
        $html = curl_exec($ch);
        ob_end_clean();

        curl_close($ch);

        return $html;
    }

    public function connectGoutteProxy($ip, $url)
    {
        $queryParameter = "?gtu";

        $uas = [
            'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0',
            'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko',
            'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:48.0) Gecko/20100101 Firefox/48.0',
            'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12) AppleWebKit/602.1.50 (KHTML, like Gecko) Version/10.0 Safari/602.1.50',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36',
        ];
        $config = [
            'proxy' => [
                'http' => $ip
            ]
        ];

        $client = new \Goutte\Client;
        $client->setClient(new \GuzzleHttp\Client($config));
        $client->setHeader('user-agent', $uas[rand(0, count($uas)-1)]);
        $crawler = $client->request('GET', $url.$queryParameter);
    }

}
