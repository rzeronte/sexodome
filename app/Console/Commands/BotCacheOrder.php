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
use Spatie\LaravelAnalytics\LaravelAnalyticsFacade;
use App\Model\CategoryTranslation;
use App\Model\Category;

class BotCacheOrder extends Command
{
    protected $signature = 'zbot:cache:order';

    protected $description = 'Reset cache order for categories';

    public function handle()
    {
        $sites = Site::where('order_type', $ANALYTICS = 1)->get();

        foreach($sites as $site) {

            $categoriesOrder = [];

            if ($site->ga_account != "") {
                $pageViews = LaravelAnalyticsFacade::setSiteId('ga:'.$site->ga_account)->getMostVisitedPages(90, $maxResults = 100);

                foreach ($pageViews as $pv) {
                    if (str_contains($pv["url"], "/category/") == true && str_contains($pv["url"], "?") == false) {
                        $txt_category = str_replace("/category/", "", $pv["url"]);
                        $categoriesOrder[] = [
                            'permalink' => $txt_category,
                            'views'     => $pv["pageViews"]
                        ];
                    }
                }
            }

            DB::table('categories')->where('site_id', $site->id)->update(['cache_order' => 0]);
            rZeBotUtils::message("RESETTING CACHE CATEGORY ORDER for " . $site->getHost(), "yellow", true, true);

            foreach($categoriesOrder as $categoryOrder) {
                $categoryTranslation = CategoryTranslation::join('categories','categories.id', '=', 'categories_translations.category_id')
                    ->where('categories.site_id', '=', $site->id)
                    ->where('permalink', $categoryOrder['permalink'])
                    ->first();

                if ($categoryTranslation) {
                    $category = Category::find($categoryTranslation->category_id);
                    $category->cache_order = $categoryOrder["views"];
                    $category->save();
                    rZeBotUtils::message("[SETTING ORDER FROM ANALYTICS] " . $categoryOrder['permalink'] . ": " . $categoryOrder['views'] . " for " . $site->getHost(), "green", true, true);
                }
            }

            // ORDER SCENES
            DB::transaction(function () use ($site) {
                //rZeBotUtils::message("Generating CACHE SCENES ORDER for " . $site->getHost(), "green", true, true);
                $scenes = Scene::getScenesOrderBySceneClicks($site->id)->get();

                $i = 1;
                foreach($scenes as $scene) {
                    //rZeBotUtils::message("Scene: " . $scene->id . ", Clicks: " . $scene->clicks . ", order: " . $i, "green", true, true);
                    $scene->cache_order = $i;
                    $scene->save();
                    $i++;
                }
            });
        }
    }
}