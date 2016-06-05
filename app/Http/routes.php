<?php

// Web Plataforma
Route::group(['domain' => \App\rZeBot\rZeBotCommons::getMainPlataformDomain()], function () {
    Route::match(['get', 'post'], "/", [ 'as' => 'home_website', 'uses' => 'WebController@home' ]);
});

// Zona accounts
Route::group(['domain' => 'accounts.'.\App\rZeBot\rZeBotCommons::getMainPlataformDomain()], function () {

    Route::match(['get', 'post'], "/", ['as' => 'home', 'uses' => 'ConfigController@home']);
    Route::match(['get', 'post'], "/fetch", ['as' => 'fetch', 'uses' => 'ConfigController@fetch']);

    // Authentication routes...
    Route::get('auth/login', 'Auth\AuthController@getLogin')->name('login');
    Route::post('auth/login', 'Auth\AuthController@postLogin')->name('login');
    Route::get('auth/logout', 'Auth\AuthController@getLogout')->name('logout');

    // Registration routes...
    Route::get('auth/register', 'Auth\AuthController@getRegister')->name('register');
    Route::post('auth/register', 'Auth\AuthController@postRegister')->name('register');

    // Password reset link request routes...
    Route::get('password/email', 'Auth\PasswordController@getEmail')->name('password');
    Route::post('password/email', 'Auth\PasswordController@postEmail')->name('password');

    // Password reset routes...
    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset')->name('reset');
    Route::post('password/reset', 'Auth\PasswordController@postReset')->name('reset');

    // ConfigController
    Route::match(['get', 'post'], '{locale}/export_scene/{scene_id}', 'ConfigController@exportScene')->name('exportScene');
    Route::match(['get', 'post'], '{locale}/tags/{site_id}', 'ConfigController@tags')->name('tags_admin');
    Route::match(['get', 'post'], '{locale}/categories/{site_id}', 'ConfigController@categories')->name('categories_admin');
    Route::match(['get', 'post'], '{locale}/sites', 'ConfigController@sites')->name('sites');
    Route::match(['get', 'post'], '{locale}/site/{site_id}', 'ConfigController@site')->name('site');

    Route::match(['get'], '{locale}/ajax/tags/', 'ConfigController@ajaxTags')->name('ajaxTags');
    Route::match(['get'], '{locale}/ajax/categories/', 'ConfigController@ajaxCategories')->name('ajaxCategories');

    Route::match(['get'], '{locale}/ajax/sceneinfo/{scene_id}', 'ConfigController@scenePublicationInfo')->name('scenePublicationInfo');

    Route::match(['get'], '{locale}/ajax/preview/{scene_id}', 'ConfigController@scenePreview')->name('scenePreview');

    Route::match(['get'], '{locale}/ajax/seo/site/keywords/{site_id}', 'ConfigController@siteKeywords')->name('siteKeywords');
    Route::match(['get'], '{locale}/ajax/seo/site/referrers/{site_id}', 'ConfigController@siteReferrers')->name('siteReferrers');
    Route::match(['get'], '{locale}/ajax/seo/site/pages/{site_id}', 'ConfigController@sitePageViews')->name('sitePageViews');

    Route::match(['get'], '{locale}/ajax/scene/thumbs/{site_id}', 'ConfigController@sceneThumbs')->name('sceneThumbs');
    Route::match(['get'], '{locale}/ajax/scene/spin/{site_id}', 'ConfigController@spinScene')->name('spinScene');

    Route::get('/setLocale/{locale}', 'ConfigController@changeLocale')->name('changeLocale');

    Route::match(['get', 'post'], '{locale}/saveTranslation/{scene_id}', 'ConfigController@saveTranslation')->name('saveTranslation');

    Route::match(['get', 'post'], '{locale}/admin/saveTagTranslation/{tag_id}', 'ConfigController@saveTagTranslation')->name('saveTagTranslation');
    Route::match(['get', 'post'], '{locale}/admin/saveCategoryTranslation/{scene_id}', 'ConfigController@saveCategoryTranslation')->name('saveCategoryTranslation');
    Route::match(['get', 'post'], '{locale}/admin/translateTag/{tag_id}', 'ConfigController@translateTag')->name('translateTag');

    Route::match(['get', 'post'], '{locale}/feeds', 'ConfigController@feeds')->name('feeds');

    Route::get('{locale}/admin/removecategory/{category_id}/{site_id}', 'ConfigController@removeCategory')->name('removeCategory');
    Route::get('{locale}/admin/addcategory/{category_id}/{site_id}', 'ConfigController@addCategory')->name('addCategory');
    Route::match(['get', 'post'], '{locale}/admin/addSite/', 'ConfigController@addSite')->name('addSite');
    Route::match(['get', 'post'], '{locale}/delete/{site_id}/', 'ConfigController@deleteSite')->name('deleteSite');
    Route::match(['get', 'post'], '{locale}/check_subdomain/', 'ConfigController@checkSubdomain')->name('checkSubdomain');
    Route::match(['get', 'post'], '{locale}/check_domain/', 'ConfigController@checkDomain')->name('checkDomain');

    Route::match(['get', 'post'], '{locale}/', ['as' => 'content','uses' => 'ConfigController@index']);

    Route::match(['get', 'post'], '{locale}/updateGoogleData/{site_id}', ['as'=> 'updateGoogleData','uses' => 'ConfigController@updateGoogleData']);
    Route::match(['get', 'post'], '{locale}/updateIframeData/{site_id}', ['as'=> 'updateIframeData','uses' => 'ConfigController@updateIframeData']);
    Route::match(['get', 'post'], '{locale}/updateLogo/{site_id}', ['as'=> 'updateLogo','uses' => 'ConfigController@updateLogo']);

});


// TubeFronts domains
Route::group(['domain' => '{host}'], function () {
    Route::get('/', 'TubeController@index')->name('index');
    Route::get('/search', 'TubeController@search')->name('search');
    Route::get('/tag/{permalinkTag}', 'TubeController@tag')->name('tag');
    Route::get('/category/{permalinkCategory}', 'TubeController@category')->name('category');
    Route::match(['get'], '/video/{permalink}', 'TubeController@video')->name('video');
    Route::match(['get'], '/iframe/{scene_id}', 'TubeController@iframe')->name('iframe');
    Route::match(['get'], '/topscenes/', 'TubeController@topscenes')->name('topscenes');
    Route::match(['get'], '/categories/', 'TubeController@categories')->name('categories');
    Route::match(['get'], '/ads/', 'TubeController@ads')->name('ads');

    Route::match(['get'], '/dmca/', 'TubeController@dmca')->name('dmca');
    Route::match(['get'], '/terms/', 'TubeController@terms')->name('terms');
    Route::match(['get'], '/2257/', 'TubeController@C2257')->name('C2257');
    Route::match(['get'], '/contact/', 'TubeController@contact')->name('contact');
});