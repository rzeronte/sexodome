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

                $this->connectProxy($ip, $url);
            }
        }
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
        curl_setopt($ch,CURLOPT_TIMEOUT, 5);
        curl_exec ($ch);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
    }

}

