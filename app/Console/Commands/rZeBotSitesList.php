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

class rZeBotSitesList extends Command
{
    protected $signature = 'rZeBot:sites:list';

    protected $description = 'List sites';

    public function handle()
    {
        $sites = Site::all();

        foreach($sites as $site) {
            rZeBotUtils::message("id: " . $site->id . " | http://". $site->getHost() . " | name: " . $site->user()->first()->name. " | language_id: " . $site->language_id, "green");
        }

        rZeBotUtils::message("Total: " . Site::all()->count());
    }
}