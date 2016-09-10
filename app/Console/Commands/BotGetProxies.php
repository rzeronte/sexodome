<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Category;
use App\rZeBot\rZeBotUtils;
use App\Model\Site;
use App\Model\Scene;
use App\rZeBot\ProxyBot;

class BotGetProxies extends Command
{
    protected $signature = 'zbot:proxies:get {site_id}';

    protected $description = 'Launch request with proxies in loop for random scenes';

    public function handle()
    {
        $site_id = $this->argument('site_id');

        $site = Site::find($site_id);

        if (!$site) {
            rZeBotUtils::message("[ERROR] El site '$site_id' indicado no existe. Abortando ejecución.", "red");
            exit;
        }

        rZeBotUtils::message("[GETTING PROXIES LIST]", "yellow", true, true);

        $list = new ProxyBot();
        $obj = $list->get();

        $proxyList = [];
        foreach ($obj as $prxobj) {
            if (!empty($prxobj['ip'])) {
                $proxyList[] = $prxobj['ip'] . ':' . $prxobj['port'];
            }
        }

        foreach($proxyList as $proxy) {
            if (strlen($proxy)) {

                $sceneRND = $site->scenes()->orderByRaw("RAND()")->first();

                if (!$sceneRND) {
                    rZeBotUtils::message("[RANDOM SITE NOT FOUND]", "yellow", true, true);
                    continue;
                }

                $url = route('out', ['profile' => $site->getHost(), 'scene_id' => $sceneRND->id]);
                $ip = preg_replace('~[\r\n\t]+~', '', $proxy );

                rZeBotUtils::message("[REQUEST PROXY] " . "$url from $ip", "yellow", true, true);

                //$this->connectProxy($ip, $url."?artisan");
                try {
                    $this->connectGoutteProxy($ip, $url);

                } catch(\Exception $e){
                    rZeBotUtils::message("[ERROR PROXY] $ip", "red", true, true);
                }
            }
        }
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

    public function connectProxy($ip, $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch, CURLOPT_PROXY, $ip);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'GET');
        curl_setopt ($ch, CURLOPT_HEADER, 1);
        curl_setopt ($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch,CURLOPT_TIMEOUT, 5);
        curl_exec ($ch);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
    }

}

