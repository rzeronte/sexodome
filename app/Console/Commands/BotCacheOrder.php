<?php

namespace App\Console\Commands;

use App\Model\Site;
use Illuminate\Console\Command;
use App\rZeBot\rZeBotUtils;
use App\Model\Scene;
use Spatie\LaravelAnalytics\LaravelAnalyticsFacade;
use App\Model\CategoryTranslation;
use App\Model\Category;
use Illuminate\Support\Facades\DB;

class BotCacheOrder extends Command
{
    protected $signature = 'zbot:cache:order';

    protected $description = 'Reset cache order in categories for "AnalyticsOrder" sites';

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
            rZeBotUtils::message("[BotCacheOrder] Reset cache order for " . $site->getHost(), "yellow", 'cache_order');

            foreach($categoriesOrder as $categoryOrder) {
                $categoryTranslation = CategoryTranslation::join('categories','categories.id', '=', 'categories_translations.category_id')
                    ->where('categories.site_id', '=', $site->id)
                    ->where('permalink', $categoryOrder['permalink'])
                    ->first()
                ;

                if ($categoryTranslation) {
                    $category = Category::find($categoryTranslation->category_id);
                    $category->cache_order = $categoryOrder["views"];
                    $category->save();
                    rZeBotUtils::message("[BotCacheOrder] Set order from GA for " . $categoryOrder['permalink'] . ": " . $categoryOrder['views'] . " in " . $site->getHost(), "info", 'cache_order');
                }
            }

            // ORDER SCENES
            DB::transaction(function () use ($site) {
                $scenes = Scene::getScenesOrderBySceneClicks($site->id)->get();

                $i = 1;
                foreach($scenes as $scene) {
                    $scene->cache_order = $i;
                    $scene->save();
                    $i++;
                }
            });
        }
    }
}