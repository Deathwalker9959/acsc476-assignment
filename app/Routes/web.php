<?php

use App\Router\Route;

return [
    Route::Group([
        "middleware" => [
            "checkAuth"
        ]
    ], [
        Route::Get("/", "HomeController@index"),
    ]),
    Route::Group([
        "middleware" => [
            "auth"
        ]
    ], [
        Route::Get("/shops", "ShopsController@index"),
    ]),
    Route::Group([
        "middleware" => [
            "AuthPartner"
        ]
    ], [
        Route::Get("/dashboard", "SellersController@index"),
    ]),
    Route::Group([
        "middleware" => [
            "checkAuth",
            "checkPartner"
        ]
    ], [
        Route::Get("/register", "AccountsController@register"),
        Route::Get("/login", "AccountsController@login"),
    ]),
];
