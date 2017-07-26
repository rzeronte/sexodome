<?php

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
    Route::match(['get', 'post'], "/", ['as' => 'home', 'uses' => 'AdminController@home']);

    Route::match(['get', 'post'], '/sites', 'AdminController@sites')->name('sites');
    Route::match(['get', 'post'], '/site/{site_id}', 'AdminController@site')->name('site');

    Route::get('verify/{token}', 'Auth\LoginController@verify')->name('verify');

    Route::get('welcome', 'AdminController@welcome')->name('welcome');
    Route::get('unverified', 'AdminController@unverified')->name('unverified');

    Route::match(['get', 'post'], "/fetch/{site_id}", ['as' => 'fetch', 'uses' => 'AdminController@fetch']);

    Route::match(['get', 'post'], '/site/tags/{site_id}', 'AdminController@ajaxSiteTags')->name('ajaxSiteTags');
    Route::match(['get', 'post'], '/site/categories/{site_id}', 'AdminController@ajaxSiteCategories')->name('ajaxSiteCategories');
    Route::match(['get', 'post'], '/ajax/updateSiteSEO/{site_id}', 'AdminController@updateSiteSEO')->name('updateSiteSEO');

    Route::match(['get', 'post'], '/site/{site_id}/order_categories/', 'AdminController@orderCategories')->name('orderCategories');

    Route::match(['get'], '/ajax/popunders/{site_id}', 'AdminController@ajaxPopunders')->name('ajaxPopunders');
    Route::match(['get'], '/ajax/savePopunder/{site_id}', 'AdminController@ajaxSavePopunder')->name('ajaxSavePopunder');
    Route::match(['get'], '/ajax/deletePopunder/{popunder_id}', 'AdminController@ajaxDeletePopunder')->name('ajaxDeletePopunder');

    Route::match(['get'], '/ajax/preview/{scene_id}', 'AdminController@scenePreview')->name('scenePreview');

    Route::match(['get'], '/ajax/cronjobs/{site_id}', 'AdminController@ajaxCronJobs')->name('ajaxCronJobs');
    Route::match(['post'],'/ajaxSaveCronJob', 'AdminController@ajaxSaveCronJob')->name('ajaxSaveCronJob');
    Route::match(['get'], '/deleteCronJob/{cronjob_id}', 'AdminController@deleteCronJob')->name('deleteCronJob');


    Route::match(['get'], '/ajax/scene/thumbs/{site_id}', 'AdminController@sceneThumbs')->name('sceneThumbs');
    Route::match(['get'], '/ajax/category/thumbs/{category_id}', 'AdminController@categoryThumbs')->name('categoryThumbs');
    Route::match(['get'], '/ajax/category/unlock/{category_translation_id}', 'AdminController@categoryUnlock')->name('categoryUnlock');

    Route::match(['get'], '/ajax/category/delete/{category_id}', 'AdminController@ajaxDeleteCategory')->name('ajaxDeleteCategory');
    Route::match(['get'], '/ajax/tag/delete/{tag_id}', 'AdminController@ajaxDeleteTag')->name('ajaxDeleteTag');
    Route::match(['get'], '/ajax/scene/delete/{scene_id}', 'AdminController@ajaxDeleteScene')->name('ajaxDeleteScene');

    Route::match(['get', 'post'], '/ajax/category/tags/{category_id}', 'AdminController@categoryTags')->name('categoryTags');

    Route::match(['get', 'post'], '/saveTranslation/{scene_id}', 'AdminController@saveTranslation')->name('saveTranslation');

    Route::match(['get', 'post'], '/site/pornstars/{site_id}', 'AdminController@ajaxSitePornstars')->name('ajaxSitePornstars');

    Route::match(['get', 'post'], '/admin/saveTagTranslation/{tag_id}', 'AdminController@saveTagTranslation')->name('saveTagTranslation');
    Route::match(['get', 'post'], '/admin/saveCategoryTranslation/{scene_id}', 'AdminController@saveCategoryTranslation')->name('saveCategoryTranslation');
    Route::match(['get', 'post'], '/admin/translateTag/{tag_id}', 'AdminController@translateTag')->name('translateTag');
    Route::match(['get', 'post'], '/admin/category/create/{site_id}', 'AdminController@createCategory')->name('createCategory');
    Route::match(['get', 'post'], '/admin/tag/create/{site_id}', 'AdminController@createTag')->name('createTag');

    Route::match(['get', 'post'], '/admin/addSite/', 'AdminController@addSite')->name('addSite');
    Route::match(['get', 'post'], '/delete/{site_id}/', 'AdminController@deleteSite')->name('deleteSite');
    Route::match(['get', 'post'], '/check_subdomain/', 'AdminController@checkSubdomain')->name('checkSubdomain');
    Route::match(['get', 'post'], '/check_domain/', 'AdminController@checkDomain')->name('checkDomain');

    Route::match(['get', 'post'], '/scenes/{site_id}', 'AdminController@scenes')->name('content');

    Route::match(['get', 'post'], '/uploadCategory/{category_id}', 'AdminController@uploadCategory')->name('uploadCategory');

    Route::match(['get', 'post'], '/updateGoogleData/{site_id}', ['as'=> 'updateGoogleData','uses' => 'AdminController@updateGoogleData']);
    Route::match(['get', 'post'], '/updateIframeData/{site_id}', ['as'=> 'updateIframeData','uses' => 'AdminController@updateIframeData']);
    Route::match(['get', 'post'], '/updateLogo/{site_id}', ['as'=> 'updateLogo','uses' => 'AdminController@updateLogo']);
    Route::match(['get', 'post'], '/updateColors/{site_id}', ['as'=> 'updateColors','uses' => 'AdminController@updateColors']);
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

    });

}