<?php

use App\Router\Route;

return [
    Route::Group([
        'prefix' => 'partner',
        // 'middleware' => ['authPartner']
    ], [
        Route::Get("/api/shops/{team}/products/add", "ShopsController@addItem"),
    ]),
    Route::Group([
        'prefix' => 'api'
    ], [
        Route::Post("/register", "AccountsController@handleRegister"),
        Route::Post("/login", "AccountsController@handleLogin"),
        Route::Post("/logout", "AccountsController@logout"),
    ]),
    Route::Group([
        'prefix' => 'partner'
    ], [
        Route::Post("/api/register", "AccountsController@handlePartnerRegister"),
        Route::Post("/api/login", "AccountsController@handlePartnerLogin"),
    ]),
    Route::Group([
        'prefix' => 'api',
        'middleware' => ['auth']
    ], [
        Route::Get("/shops", "ShopsController@indexShops"),
    ]),
    Route::Group([
        'middleware' => ['authPartner']
    ], [
        Route::Get("/dashboard", "SellersController@index"),
    ]),
    Route::Group([
        'prefix' => 'partner',
        // 'middleware' => ['authPartner']
    ], [
        Route::Get("/api/shops", "ShopsController@indexOwnedShops"),
    ]),
];
