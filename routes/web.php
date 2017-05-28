<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Web Plataforma formato domain.com
// *********************************************************************************************************************
Route::group(['domain' => \App\rZeBot\sexodomeKernel::getMainPlataformDomain()], function () {

    Route::match(['get', 'post'], "/", [ 'as' => 'home_website', 'uses' => 'WebController@home' ]);
    Route::match(['get', 'post'], "/google-keyword-position", [ 'as' => 'GoogleKeywordPosition', 'uses' => 'WebController@GooglePosition' ]);
    Route::match(['get', 'post'], "/webping", [ 'as' => 'webping', 'uses' => 'WebController@webping' ]);

});

// Web Plataforma formato www.domain.com
// *********************************************************************************************************************
Route::group(['domain' => "www.".\App\rZeBot\sexodomeKernel::getMainPlataformDomain()], function () {

    Route::match(['get', 'post'], "/", [ 'as' => 'home_website', 'uses' => 'WebController@home' ]);
});

// Zona accounts
// *********************************************************************************************************************
Route::group(['domain' => 'accounts.'.\App\rZeBot\sexodomeKernel::getMainPlataformDomain()], function () {
    Route::match(['get', 'post'], "/", ['as' => 'home', 'uses' => 'ConfigController@home']);

    Route::match(['get', 'post'], '/sites', 'ConfigController@sites')->name('sites');
    Route::match(['get', 'post'], '/site/{site_id}', 'ConfigController@site')->name('site');

    Route::get('verify/{token}', 'Auth\LoginController@verify')->name('verify');

    Route::get('welcome', 'ConfigController@welcome')->name('welcome');
    Route::get('unverified', 'ConfigController@unverified')->name('unverified');

    // ConfigController
    Route::match(['get', 'post'], "/fetch/{site_id}", ['as' => 'fetch', 'uses' => 'ConfigController@fetch']);

    Route::match(['get', 'post'], '/site/tags/{site_id}', 'ConfigController@ajaxSiteTags')->name('ajaxSiteTags');
    Route::match(['get', 'post'], '/site/categories/{site_id}', 'ConfigController@ajaxSiteCategories')->name('ajaxSiteCategories');
    Route::match(['get', 'post'], '/ajax/updateSiteSEO/{site_id}', 'ConfigController@updateSiteSEO')->name('updateSiteSEO');

    Route::match(['get', 'post'], '/site/{site_id}/order_categories/', 'ConfigController@orderCategories')->name('orderCategories');

    Route::match(['get'], '/ajax/popunders/{site_id}', 'ConfigController@ajaxPopunders')->name('ajaxPopunders');
    Route::match(['get'], '/ajax/savePopunder/{site_id}', 'ConfigController@ajaxSavePopunder')->name('ajaxSavePopunder');
    Route::match(['get'], '/ajax/deletePopunder/{popunder_id}', 'ConfigController@ajaxDeletePopunder')->name('ajaxDeletePopunder');

    Route::match(['get'], '/workers/{site_id}', 'ConfigController@ajaxSiteWorkers')->name('ajaxSiteWorkers');

    Route::match(['get'], '/ajax/preview/{scene_id}', 'ConfigController@scenePreview')->name('scenePreview');

    Route::match(['get'], '/ajax/cronjobs/{site_id}', 'ConfigController@ajaxCronJobs')->name('ajaxCronJobs');
    Route::match(['post'],'/ajaxSaveCronJob', 'ConfigController@ajaxSaveCronJob')->name('ajaxSaveCronJob');
    Route::match(['get'], '/deleteCronJob/{cronjob_id}', 'ConfigController@deleteCronJob')->name('deleteCronJob');


    Route::match(['get'], '/ajax/scene/thumbs/{site_id}', 'ConfigController@sceneThumbs')->name('sceneThumbs');
    Route::match(['get'], '/ajax/category/thumbs/{category_id}', 'ConfigController@categoryThumbs')->name('categoryThumbs');
    Route::match(['get'], '/ajax/category/unlock/{category_translation_id}', 'ConfigController@categoryUnlock')->name('categoryUnlock');

    Route::match(['get', 'post'], '/ajax/category/tags/{category_id}', 'ConfigController@categoryTags')->name('categoryTags');

    Route::match(['get', 'post'], '/saveTranslation/{scene_id}', 'ConfigController@saveTranslation')->name('saveTranslation');

    Route::match(['get', 'post'], '/site/pornstars/{site_id}', 'ConfigController@ajaxSitePornstars')->name('ajaxSitePornstars');

    Route::match(['get', 'post'], '/admin/saveTagTranslation/{tag_id}', 'ConfigController@saveTagTranslation')->name('saveTagTranslation');
    Route::match(['get', 'post'], '/admin/saveCategoryTranslation/{scene_id}', 'ConfigController@saveCategoryTranslation')->name('saveCategoryTranslation');
    Route::match(['get', 'post'], '/admin/translateTag/{tag_id}', 'ConfigController@translateTag')->name('translateTag');
    Route::match(['get', 'post'], '/admin/category/create/{site_id}', 'ConfigController@createCategory')->name('createCategory');

    Route::match(['get', 'post'], '/admin/addSite/', 'ConfigController@addSite')->name('addSite');
    Route::match(['get', 'post'], '/delete/{site_id}/', 'ConfigController@deleteSite')->name('deleteSite');
    Route::match(['get', 'post'], '/check_subdomain/', 'ConfigController@checkSubdomain')->name('checkSubdomain');
    Route::match(['get', 'post'], '/check_domain/', 'ConfigController@checkDomain')->name('checkDomain');

    Route::match(['get', 'post'], '/scenes/{site_id}', 'ConfigController@scenes')->name('content');

    Route::match(['get', 'post'], '/uploadCategory/{category_id}', 'ConfigController@uploadCategory')->name('uploadCategory');

    Route::match(['get', 'post'], '/updateGoogleData/{site_id}', ['as'=> 'updateGoogleData','uses' => 'ConfigController@updateGoogleData']);
    Route::match(['get', 'post'], '/updateIframeData/{site_id}', ['as'=> 'updateIframeData','uses' => 'ConfigController@updateIframeData']);
    Route::match(['get', 'post'], '/updateLogo/{site_id}', ['as'=> 'updateLogo','uses' => 'ConfigController@updateLogo']);
    Route::match(['get', 'post'], '/updateColors/{site_id}', ['as'=> 'updateColors','uses' => 'ConfigController@updateColors']);
    Auth::routes();

});

// TubeFronts domains
// *********************************************************************************************************************
if (!App::runningInConsole() && App::make('site')) {

    Route::group(['domain' => '{host}'], function () {

        Route::get('/' . App::make('site')->pornstars_url, 'TubeController@pornstars')->name('pornstars');
        Route::get('/' . App::make('site')->pornstars_url."/{page}/", 'TubeController@pornstars')->name('pornstars_page');

        Route::get('/' . App::make('site')->category_url . '/{permalinkCategory}', 'TubeController@category')->name('category');
        Route::get('/' . App::make('site')->category_url . '/{permalinkCategory}/{page}', 'TubeController@category')->name('category_page');

        Route::get('/' . App::make('site')->pornstar_url . '/{permalinkPornstar}', 'TubeController@pornstar')->name('pornstar');
        Route::get('/' . App::make('site')->pornstar_url . '/{permalinkPornstar}/{page}', 'TubeController@pornstar')->name('pornstar_page');

        Route::get('/' . App::make('site')->video_url . '/{permalink}', 'TubeController@video')->name('video');

        Route::get('/search', 'TubeController@search')->name('search');
        Route::get('/ads/', 'TubeController@ads')->name('ads');

        Route::get('/sitemap.xml', 'TubeController@sitemap')->name('sitemap');

        Route::get('/out/{scene_id_id}', 'TubeController@out')->name('out');
        Route::get('/iframe/{scene_id}', 'TubeController@iframe')->name('iframe');

        Route::get('/dmca/', 'TubeController@dmca')->name('dmca');
        Route::get('/terms/', 'TubeController@terms')->name('terms');
        Route::get('/2257/', 'TubeController@C2257')->name('C2257');
        Route::get('/contact/', 'TubeController@contact')->name('contact');

        Route::get('/', 'TubeController@categories')->name('categories');
        Route::get('/{page}', 'TubeController@categories')->name('categories_page');

        //Route::match(['get'], '/', 'TubeController@siteError')->name('siteError');
    });

}

