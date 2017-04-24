<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Model\Site;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $domain = $_SERVER['HTTP_HOST'];

            $site = Cache::remember('domain_'.$domain, env('MEMCACHED_QUERY_TIME', 30), function() use ($domain) {
                return Site::where('domain', $domain)->where('status', 1)->first();
            });

            if ($site) {
                App::instance('site', $site);
            } else {
                App::instance('site', false);
            }

        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
