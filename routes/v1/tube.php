<?php

// TubeFronts domains
// *********************************************************************************************************************
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

if (!App::make('sexodomeKernel')->isSexodomeBackend() and !App::make('sexodomeKernel')->isSexodomeFront() and App::make('site')) {
    Route::group(['domain' => '{host}'], function () {
        Route::get('/dmca', 'TubeController@dmca')->name('dmca');
        Route::get('/search', 'TubeController@search')->name('search');
        Route::get('/ads', 'TubeController@ads')->name('ads');
        Route::get('/sitemap.xml', 'TubeController@sitemap')->name('sitemap');
        Route::get('/out/{scene_id_id}', 'TubeController@out')->name('out');
        Route::get('/iframe/{scene_id}', 'TubeController@iframe')->name('iframe');
        Route::get('/terms', 'TubeController@terms')->name('terms');
        Route::get('/2257', 'TubeController@C2257')->name('C2257');
        Route::get('/contact', 'TubeController@contact')->name('contact');

        Route::get('/' . App::make('site')->seo->pornstars_url, 'TubeController@pornstars')->name('pornstars');
        Route::get('/' . App::make('site')->seo->pornstars_url."/{page}/", 'TubeController@pornstars')->name('pornstars_page')->where('page', '[0-9]+');

        Route::get('/' . App::make('site')->seo->category_url . '/{permalinkCategory}', 'TubeController@category')->name('category');
        Route::get('/' . App::make('site')->seo->category_url . '/{permalinkCategory}/{page}', 'TubeController@category')->name('category_page')->where('page', '[0-9]+');

        Route::get('/' . App::make('site')->seo->pornstar_url . '/{permalinkPornstar}', 'TubeController@pornstar')->name('pornstar');
        Route::get('/' . App::make('site')->seo->pornstar_url . '/{permalinkPornstar}/{page}', 'TubeController@pornstar')->name('pornstar_page')->where('page', '[0-9]+');

        Route::get('/' . App::make('site')->seo->video_url . '/{permalink}', 'TubeController@video')->name('video');


        Route::get('/', 'TubeController@categories')->name('categories');
        Route::get('/{page}', 'TubeController@categories')->name('categories_page')->where('page', '[0-9]+');
    });
}
