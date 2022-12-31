<?php

use App\Router\Route;

return [
    Route::Group([], [
        Route::Get("/", "HomeController@index"),
    ]),
    Route::Group([
        "middleware" => [
            "auth"
        ]
    ], [
        Route::Get("/shops", "HomeController@inexist"),
    ]),
    Route::Group([
        "middleware" => [
            "checkAuth"
        ]
    ], [
        Route::Get("/register", "AccountsController@register"),
        Route::Get("/login", "AccountsController@login"),
    ]),
];
