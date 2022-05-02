<?php

use App\rZeBot\sexodomeKernel;
use Illuminate\Support\Facades\Route;

// Web Plataforma formato domain.com
Route::group(['domain' => sexodomeKernel::getMainPlataformDomain()], function () {
    Route::match(['get'], "/", [ 'as' => 'home_website', 'uses' => 'WebController@home' ]);
    Route::match(['get'], "/google-keyword-position", [ 'as' => 'GoogleKeywordPosition', 'uses' => 'WebController@GooglePosition' ]);
    Route::match(['get'], "/webping", [ 'as' => 'webping', 'uses' => 'WebController@webping' ]);

});

// Web Plataforma formato www.domain.com
Route::group(['domain' => "www.".sexodomeKernel::getMainPlataformDomain()], function () {
    Route::match(['get'], "/", [ 'as' => 'home_website', 'uses' => 'WebController@home' ]);
});
