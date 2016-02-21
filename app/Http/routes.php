<?php
Route::match(['get', 'post'], '/', 'ConfigController@home')->name('home');

Route::match(['get', 'post'], '{locale}/', 'ConfigController@index')->name('content');
Route::match(['get', 'post'], '{locale}/export_scene/{scene_id}', 'ConfigController@exportScene')->name('exportScene');
Route::match(['get', 'post'], '{locale}/tags/', 'ConfigController@tags')->name('tags');
Route::match(['get', 'post'], '{locale}/stats/', 'ConfigController@stats')->name('stats');
Route::match(['get'], '{locale}/ajax/tags/', 'ConfigController@ajaxTags')->name('ajaxTags');

Route::get('/setLocale/{locale}', 'ConfigController@changeLocale')->name('changeLocale');

Route::match(['get', 'post'], '{locale}/saveTranslation/{scene_id}', 'ConfigController@saveTranslation')->name('saveTranslation');
