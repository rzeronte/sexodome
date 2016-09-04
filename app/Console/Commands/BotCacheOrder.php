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
            $pageViews = LaravelAnalyticsFacade::setSiteId('ga:'.$site->ga_account)->getMostVisitedPages(90, $maxResults = 100);
            print_r($pageViews);
            continue;

            // ORDER SCENES
            DB::transaction(function () use ($site) {
                rZeBotUtils::message("Generating CACHE SCENES ORDER for " . $site->getHost(), "green", true, true);
                $scenes = Scene::getScenesOrderBySceneClicks($site->id)->get();

                $i = 1;
                foreach($scenes as $scene) {
                    rZeBotUtils::message("Scene: " . $scene->id . ", Clicks: " . $scene->clicks . ", order: " . $i, "green", true, true);
                    $scene->cache_order = $i;
                    $scene->save();
                    $i++;
                }

            });




            // ORDER CATEGORIES
            DB::transaction(function () use ($site) {
                rZeBotUtils::message("Generating CACHE CATEGORIES ORDER for " . $site->getHost(), "green", true, true);
                $categories = $site->categories()->get();

                $i = 1;
                foreach($categories as $category) {
                    rZeBotUtils::message("Category: " . $category->id . ", order: " . $i, "green", true, true);
                    $category->cache_order = $i;
                    $category->save();
                    $i++;
                }

            });

        }
    }
}