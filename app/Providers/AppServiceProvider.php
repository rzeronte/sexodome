<?php

namespace App\Providers;

use App\rZeBot\sexodomeKernel;
use Illuminate\Support\ServiceProvider;
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
        App::instance('sexodomeKernel', new sexodomeKernel());
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
