<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use App\Model\Scene;
use DB;

class BotCacheOrder extends Command
{
    protected $signature = 'zbot:cache:order';

    protected $description = 'Reset cache order.';

    public function handle()
    {
        $sites = Site::all();

        foreach($sites as $site) {
            rZeBotUtils::message("Generating CACHE ORDER for " . $site->getSitemap(), "green", true, true);
            $scenes = Scene::getScenesOrderBySceneClicks($site->id)->get();

            $i = 1;
            foreach($scenes as $scene) {
                rZeBotUtils::message("Scene: " . $scene->id . ", Clicks: " . $scene->clicks . ", order: " . $i, "green", true, true);
                $scene->cache_order = $i;
                $scene->save();
                $i++;
            }
        }
    }
}