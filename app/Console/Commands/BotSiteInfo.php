<?php

namespace App\Console\Commands;

use App\Model\Language;
use App\Model\LanguageTag;
use App\Model\SceneCategory;
use App\Model\ScenePornstar;
use App\Model\Site;
use App\Model\Tag;
use App\Model\TagTranslation;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Host;
use App\Model\Tweet;
use App\rZeBot\TwitterAPIExchange;
use App\Model\Category;
use App\Model\Pornstar;
use DB;

class BotSiteInfo extends Command
{
    protected $signature = 'zbot:site:info {domain}';

    protected $description = 'Show info for a site';

    public function handle()
    {
        $domain = $this->argument("domain");

        $site = Site::where('domain', $domain)->first();

        if (!$site) {
            rZeBotUtils::message($domain . " not found", "red", true, true);
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

        rZeBotUtils::message("About " . $site->getHost(), "green", true, true);

        rZeBotUtils::message("Num. Scenes getTotalScenes(): " . $site->getTotalScenes(), "green", true, true);
        rZeBotUtils::message("Num. Scenes No Tags: " . $scene_no_tags, "green", true, true);
        rZeBotUtils::message("Num. Scenes BBDD: " . $site->scenes()->count(), "green", true, true);
        rZeBotUtils::message("Num. Categories: " . $site->categories()->count(), "green", true, true);
        rZeBotUtils::message("Num. Categories No Tags: " . $categories_no_tags, "green", true, true);
        rZeBotUtils::message("Num. Tags: " . $site->tags()->count(), "green", true, true);
        rZeBotUtils::message("Status: " . ($site->status == 1) ? "On": "Off", "green", true, true);
        rZeBotUtils::message("Title: " . str_replace("{domain}", $site->getHost(), $site->title_index), "green", true, true);
        rZeBotUtils::message("Description: " . str_replace("{domain}", $site->getHost(), $site->description_index), "green", true, true);
        rZeBotUtils::message("Analytics GA: " . $site->ga_account, "green", true, true);

    }

}