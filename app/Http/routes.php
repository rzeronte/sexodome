<?php

// Frontend...
Route::match(['get', 'post'], '{locale}/', 'ConfigController@index')->name('content');
Route::match(['get', 'post'], '{locale}/export_scene/{scene_id}', 'ConfigController@exportScene')->name('exportScene');
Route::get('/setLocale/{locale}', 'ConfigController@changeLocale')->name('changeLocale');
