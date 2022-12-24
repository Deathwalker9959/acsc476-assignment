<?php

require_once "../vendor/base/Route.php";

use App\Router\Route;

return [
    Route::Group([
        'prefix' => 'api',
    ], [
        Route::Get("/", "HomeController@index"),
        Route::Get("/as", "HomeController@dam"),
    ]),
    Route::Group([
        'prefix' => 'none',
    ], [
        Route::Get("/", "HomeController@kek"),
    ]),
    Route::Get("getfucked","HomeController@inexist"),
];