<?php

// Frontend...
Route::get('/', 'IndexController@index')->name('index');
Route::get('/setLocale/{locale}', 'ConfigController@changeLocale')->name('changeLocale');

// Backend...
Route::get('{locale}/admin/', 'ConfigController@config')->name('config');
Route::match(['get', 'post'], '{locale}/admin/general', 'ConfigController@general')->name('general');
Route::match(['get', 'post'], '{locale}/admin/content', 'ConfigController@content')->name('content');
Route::match(['get', 'post'], '{locale}/admin/translations', 'ConfigController@translations')->name('translations');
Route::match(['get', 'post'], '{locale}/admin/tags', 'ConfigController@tags')->name('tags');
Route::match(['get', 'post'], '{locale}/admin/createTag/', 'ConfigController@createTag')->name('createTag');
Route::match(['get', 'post'], '{locale}/admin/tweets', 'ConfigController@tweets')->name('tweets');
Route::match(['get', 'post'], '{locale}/admin/ads', 'ConfigController@ads')->name('ads');
Route::match(['get', 'post'], '{locale}/admin/export', 'ConfigController@export')->name('export');
Route::match(['get', 'post'], '{locale}/admin/export_to_site', 'ConfigController@exportToSite')->name('exportToSite');

// Backend internals...
Route::get('{locale}/admin/addtag/{permalink}', 'ConfigController@addTag')->name('addTag');
Route::get('{locale}/admin/removetag/{permalink}', 'ConfigController@removeTag')->name('removeTag');
Route::get('{locale}/admin/accept/{scene_id}', 'ConfigController@accept')->name('accept');
Route::get('{locale}/admin/discard/{scene_id}', 'ConfigController@discard')->name('discard');
Route::match(['get', 'post'], '{locale}/admin/addscene/', 'ConfigController@addScene')->name('addScene');
Route::match(['get', 'post'], '{locale}/admin/saveTranslation/{scene_id}', 'ConfigController@saveTranslation')->name('saveTranslation');
Route::match(['get', 'post'], '{locale}/admin/saveTagTranslation/{scene_id}', 'ConfigController@saveTagTranslation')->name('saveTagTranslation');
Route::match(['get', 'post'], '{locale}/admin/editScene/{scene_id}', 'ConfigController@editScene')->name('editScene');
Route::match(['get', 'post'], '{locale}/admin/removeSceneTag/{tag_id}/{scene_id}', 'ConfigController@removeSceneTag')->name('removeSceneTag');
Route::match(['get', 'post'], '{locale}/admin/addSceneTag/{tag_id}/{scene_id}', 'ConfigController@addSceneTag')->name('addSceneTag');
Route::match(['get'], '{locale}/admin/deleteScene/{scene_id}', 'ConfigController@deleteScene')->name('deleteScene');
Route::match(['get', 'post'], '{locale}/admin/addTweet', 'ConfigController@addTweet')->name('addTweet');
Route::match(['get', 'post'], '{locale}/admin/removeTweet/{tweet_id}', 'ConfigController@removeTweet')->name('removeTweet');
Route::match(['get', 'post'], '{locale}/admin/addAd', 'ConfigController@addAd')->name('addAd');
Route::match(['get', 'post'], '{locale}/admin/removeAd/{ad_id}', 'ConfigController@removeAd')->name('removeAd');
Route::match(['get', 'post'], '{locale}/admin/editAd/{ad_id}', 'ConfigController@editAd')->name('editAd');
Route::match(['get', 'post'], '{locale}/admin/cleanTags', 'ConfigController@cleanTags')->name('cleanTags');
Route::match(['get', 'post'], '{locale}/admin/translateScene/{scene_id}', 'ConfigController@translateScene')->name('translateScene');
Route::match(['get', 'post'], '{locale}/admin/translateTag/{tag_id}', 'ConfigController@translateTag')->name('translateTag');

// Authentication routes...
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin')->name('login');;
Route::get('logout', 'Auth\AuthController@getLogout')->name('logout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
