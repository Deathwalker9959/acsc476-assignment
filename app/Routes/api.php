<?php

use App\Router\Route;

return [
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
        /*
        * Products
        */
        Route::Post("/api/shops/{team}/products", "SellersController@addProduct"),
        Route::Delete("/api/shops/{team}/products/{product}", "SellersController@removeProduct"),
        Route::Put("/api/shops/{team}/products/{product}", "SellersController@updateProduct"),
        /*
        * Categories
        */
        Route::Post("/api/shops/categories", "SellersController@addCategory"),
        Route::Delete("/api/shops/categories/{category}", "SellersController@removeCategory"),
        Route::Put("/api/shops/categories/{category}", "SellersController@updateCategory"),
        /*
        * Hazards
        */
        Route::Post("/api/shops/hazards", "SellersController@addHazard"),
        Route::Delete("/api/shops/hazards/{hazard}", "SellersController@removeHazard"),
        Route::Put("/api/shops/hazards/{hazard}", "SellersController@updateHazard"),
        /*
        * Ingredients
        */
        Route::Post("/api/shops/{team}/products", "SellersController@addIngredient"),
        Route::Delete("/api/shops/{team}/products/{ingredient}", "SellersController@removeIngredient"),
        Route::Put("/api/shops/{team}/products/{ingredient}", "SellersController@updateIngredient"),

        Route::Get("/api/shops", "ShopsController@indexOwnedShops"),
        Route::Get("/api/shops/{team}/products/get", "ShopsController@indexOwnedShops"),
    ]),
];
