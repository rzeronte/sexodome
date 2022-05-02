<?php

namespace App\Providers;


use Sexodome\SexodomeApi\Application\ShowOrderCategoriesCommandHandler;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use App\rZeBot\sexodomeKernel;
use Sexodome\SexodomeApi\Application\UpdateCategoryTagsCommandHandler;

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


        // Admin mapping services
        $this->app->bind('AdminShowOrderCategoriesService', function ($app) { return new ShowOrderCategoriesCommandHandler(); });
        $this->app->bind('AdminSaveCategoryTagsService', function ($app) { return new UpdateCategoryTagsCommandHandler(); });

    }

    public function register()
    {
    }
}
