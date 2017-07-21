<?php

namespace App\Console\Commands;

use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;

class BotSitesList extends Command
{
    protected $signature = 'zbot:sites:list';

    protected $description = 'List sites information';

    public function handle()
    {
        $sites = Site::all();
        rZeBotUtils::message("[BotSitesList] ALL SITES", "info",'kernel');
        rZeBotUtils::message("[BotSitesList] ---------", "info",'kernel');

        foreach($sites as $site) {
            $msg = "id: " . $site->id . " | http://". $site->getHost() . " | " . $site->user()->first()->name. " | " . $site->language->name;
            rZeBotUtils::message("[BotSitesList] " . $msg, "info",'kernel');
        }

        rZeBotUtils::message("[BotSitesList] Total: " . Site::all()->count() . " sites", "info",'kernel');
    }
}