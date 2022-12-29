<?php

require_once "../vendor/base/Route.php";

use App\Router\Route;

return [
    Route::Group([
        'middleware' => [
            'Authenticate'
        ]
    ], [
        Route::Get("/{user}", "HomeController@dam"),
        Route::Get("/", "HomeController@index"),
    ]),
    Route::Group([
         'prefix' => 'api'
    ], [
        Route::Get("/{user}/{user1}/asd/{user3}", "HomeController@index"),
        Route::Get("/", "HomeController@index"),
        // Route::Get("/as", "HomeController@dam"),
    ]),
    Route::Group([
        'prefix' => 'none',
    ], [
        Route::Get("/", "HomeController@kek"),
    ]),
    Route::Get("getfucked","HomeController@inexist"),
];