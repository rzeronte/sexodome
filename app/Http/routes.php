<?php
Route::match(['get', 'post'], '/', 'ConfigController@home')->name('home');

Route::match(['get', 'post'], '{locale}/', 'ConfigController@index')->name('content');
Route::match(['get', 'post'], '{locale}/export_scene/{scene_id}', 'ConfigController@exportScene')->name('exportScene');
Route::match(['get', 'post'], '{locale}/tags/', 'ConfigController@tags')->name('tags');
Route::match(['get', 'post'], '{locale}/stats/', 'ConfigController@stats')->name('stats');
Route::match(['get', 'post'], '{locale}/sites', 'ConfigController@sites')->name('sites');

Route::match(['get'], '{locale}/ajax/tags/', 'ConfigController@ajaxTags')->name('ajaxTags');
Route::match(['get'], '{locale}/ajax/tagtiersinfo/', 'ConfigController@tagTiersInfo')->name('tagTiersInfo');
Route::match(['get'], '{locale}/ajax/sceneinfo/{scene_id}', 'ConfigController@scenePublicationInfo')->name('scenePublicationInfo');
Route::match(['get'], '{locale}/ajax/preview/{scene_id}', 'ConfigController@scenePreview')->name('scenePreview');

Route::match(['get'], '{locale}/ajax/seo/site/keywords/{site_id}', 'ConfigController@siteKeywords')->name('siteKeywords');
Route::match(['get'], '{locale}/ajax/seo/site/referrers/{site_id}', 'ConfigController@siteReferrers')->name('siteReferrers');
Route::match(['get'], '{locale}/ajax/seo/site/pages/{site_id}', 'ConfigController@sitePageViews')->name('sitePageViews');
Route::match(['get'], '{locale}/ajax/scene/thumbs/{site_id}', 'ConfigController@sceneThumbs')->name('sceneThumbs');
Route::match(['get'], '{locale}/ajax/scene/spin/{site_id}', 'ConfigController@spinScene')->name('spinScene');

Route::get('/setLocale/{locale}', 'ConfigController@changeLocale')->name('changeLocale');

Route::match(['get', 'post'], '{locale}/saveTranslation/{scene_id}', 'ConfigController@saveTranslation')->name('saveTranslation');


Route::match(['get', 'post'], '{locale}/admin/saveTagTranslation/{scene_id}', 'ConfigController@saveTagTranslation')->name('saveTagTranslation');
Route::match(['get', 'post'], '{locale}/admin/translateTag/{tag_id}', 'ConfigController@translateTag')->name('translateTag');
