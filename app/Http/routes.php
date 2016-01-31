<?php
Route::match(['get', 'post'], '/', 'ConfigController@home')->name('home');

Route::match(['get', 'post'], '{locale}/', 'ConfigController@index')->name('content');
Route::match(['get', 'post'], '{locale}/export_scene/{scene_id}', 'ConfigController@exportScene')->name('exportScene');
Route::match(['get', 'post'], '{locale}/tags/', 'ConfigController@tags')->name('tags');
Route::get('/setLocale/{locale}', 'ConfigController@changeLocale')->name('changeLocale');
