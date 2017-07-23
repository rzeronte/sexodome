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
        rZeBotUtils::message("[BotSitesList] ALL SITES", "info",'kernel', true, true);
        rZeBotUtils::message("[BotSitesList] ---------", "info",'kernel', true, true);

        foreach($sites as $site) {
            $msg = "id: " . $site->id . " | status: " . intval($site->status) . " | http://". $site->getHost() . " | " . $site->user()->first()->name. " | " . $site->language->name;
            rZeBotUtils::message("[BotSitesList] " . $msg, "info",'kernel', true, true);
        }

        rZeBotUtils::message("[BotSitesList] Total: " . Site::all()->count() . " sites", "info",'kernel', true, true);
    }
}