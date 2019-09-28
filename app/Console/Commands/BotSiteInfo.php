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
            rZeBotUtils::message("[BotSiteInfo] " . $domain . " not found", "error",'kernel', true, true);
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

        rZeBotUtils::message("[BotSiteInfo] About " . $site->getHost(), "info",'kernel', true, true);
        rZeBotUtils::message("[BotSiteInfo] Num. Scenes getTotalScenes(): " . $site->getTotalScenes(), "info",'kernel', true, true);
        rZeBotUtils::message("[BotSiteInfo] Num. Scenes No Tags: " . $scene_no_tags, "info",'kernel', true, true);
        rZeBotUtils::message("[BotSiteInfo] Num. Scenes BBDD: " . $site->scenes()->count(), "info",'kernel', true, true);
        rZeBotUtils::message("[BotSiteInfo] Num. Categories: " . $site->categories()->count(), "info",'kernel', true, true);
        rZeBotUtils::message("[BotSiteInfo] Num. Categories No Tags: " . $categories_no_tags, "info", 'kernel', true, true);
        rZeBotUtils::message("[BotSiteInfo] Num. Tags: " . $site->tags()->count(), "info",'kernel', true, true);
        rZeBotUtils::message("[BotSiteInfo] Status: " .  (($site->status == 1) ? "On": "Off"), "info",'kernel', true, true);
        rZeBotUtils::message("[BotSiteInfo] Title: " . str_replace("{domain}", $site->getHost(), $site->title_index), "info", 'kernel', true, true);
        rZeBotUtils::message("[BotSiteInfo] Description: " . str_replace("{domain}", $site->getHost(), $site->description_index), "info",'kernel', true, true);
        rZeBotUtils::message("[BotSiteInfo] Analytics GA: " . $site->ga_account, "info",'kernel', true, true);

    }

}