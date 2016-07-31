<?php

namespace App\Console\Commands;

use App\Model\LanguageTag;
use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use DB;

class BotSitesList extends Command
{
    protected $signature = 'zbot:sites:list';

    protected $description = 'List sites information';

    public function handle()
    {
        $sites = Site::all();
        rZeBotUtils::message("ALL SITES", "yellow", true, true);
        rZeBotUtils::message("---------", "yellow", true, true);
        rZeBotUtils::message("", "yellow", true, true);

        foreach($sites as $site) {
            $msg = "id: " . $site->id . " | http://". $site->getHost() . " | " . $site->user()->first()->name. " | " . $site->language->name;
            rZeBotUtils::message($msg, "yellow", true, true);
        }

        rZeBotUtils::message("", "yellow", true, true);
        rZeBotUtils::message("Total: " . Site::all()->count() . " sites", "yellow", true, true);
    }
}