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

Route::get('/setLocale/{locale}', 'ConfigController@changeLocale')->name('changeLocale');

Route::match(['get', 'post'], '{locale}/saveTranslation/{scene_id}', 'ConfigController@saveTranslation')->name('saveTranslation');
