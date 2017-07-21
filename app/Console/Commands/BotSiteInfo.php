<?php

namespace App\Console\Commands;

use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;

class BotSiteInfo extends Command
{
    protected $signature = 'zbot:site:info {domain}';

    protected $description = 'Show info for a site';

    public function handle()
    {
        $domain = $this->argument("domain");

        $site = Site::where('domain', $domain)->first();

        if (!$site) {
            rZeBotUtils::message($domain . " not found", "red", true, true, 'kernel');
            return;
        }

        $categories_no_tags = 0;
        $scene_no_tags = 0;

        foreach ($site->scenes()->select('id')->get() as $scene) {
            if ($scene->tags()->count() > 0) {
                $scene_no_tags++;
            }
        }

        foreach ($site->categories()->select('id')->get() as $category) {
            if ($category->tags()->count() > 0) {
                $categories_no_tags++;
            }
        }

        rZeBotUtils::message("About " . $site->getHost(), "green", true, true, 'kernel');

        rZeBotUtils::message("Num. Scenes getTotalScenes(): " . $site->getTotalScenes(), "green", true, true, 'kernel');
        rZeBotUtils::message("Num. Scenes No Tags: " . $scene_no_tags, "green", true, true, 'kernel');
        rZeBotUtils::message("Num. Scenes BBDD: " . $site->scenes()->count(), "green", true, true, 'kernel');
        rZeBotUtils::message("Num. Categories: " . $site->categories()->count(), "green", true, true, 'kernel');
        rZeBotUtils::message("Num. Categories No Tags: " . $categories_no_tags, "green", true, true, 'kernel');
        rZeBotUtils::message("Num. Tags: " . $site->tags()->count(), "green", true, true, 'kernel');
        rZeBotUtils::message("Status: " . ($site->status == 1) ? "On": "Off", "green", true, true, 'kernel');
        rZeBotUtils::message("Title: " . str_replace("{domain}", $site->getHost(), $site->title_index), "green", true, true, 'kernel');
        rZeBotUtils::message("Description: " . str_replace("{domain}", $site->getHost(), $site->description_index), "green", true, true, 'kernel');
        rZeBotUtils::message("Analytics GA: " . $site->ga_account, "green", true, true, 'kernel');

    }

}