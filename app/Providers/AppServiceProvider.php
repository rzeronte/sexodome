<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

use App\rZeBot\sexodomeKernel;
use App\Services\Front\getCategoriesService;
use App\Services\Front\getSearchService;
use App\Services\Front\getCategoryService;
use App\Services\Front\getPornstarsService;
use App\Services\Front\getPornstarService;
use App\Services\Front\getVideoService;
use App\Services\Front\getSceneIframeService;
use App\Services\Front\getSiteAdsService;
use App\Services\Front\getSitemapService;
use App\Services\Front\runOutService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        App::instance('sexodomeKernel', new sexodomeKernel());

        // Front
        $this->app->bind('getCategoriesService', function ($app) { return new getCategoriesService(); });
        $this->app->bind('getSearchService', function ($app) { return new getSearchService(); });
        $this->app->bind('getCategoryService', function ($app) { return new getCategoryService(); });
        $this->app->bind('getPornstarsService', function ($app) { return new getPornstarsService(); });
        $this->app->bind('getPornstarService', function ($app) { return new getPornstarService(); });
        $this->app->bind('getVideoService', function ($app) { return new getVideoService(); });
        $this->app->bind('getSceneIframeService', function ($app) { return new getSceneIframeService(); });
        $this->app->bind('getSiteAdsService', function ($app) { return new getSiteAdsService(); });
        $this->app->bind('runOutService', function ($app) { return new runOutService(); });
        $this->app->bind('getSitemapService', function ($app) { return new getSitemapService(); });


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
