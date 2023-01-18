<?php

use App\Router\Route;

return [
    Route::Group([
        "prefix" => "api"
    ], [
        Route::Post("/register", "AccountsController@handleRegister"),
        Route::Post("/login", "AccountsController@handleLogin"),
        Route::Post("/logout", "AccountsController@logout"),
    ]),
    Route::Group([
        "prefix" => "partner/api"
    ], [
        Route::Post("/register", "AccountsController@handlePartnerRegister"),
        Route::Post("/login", "AccountsController@handlePartnerLogin"),
    ]),
    Route::Group([
        "prefix" => "api",
        "middleware" => ["auth"]
    ], [
        Route::Get("/shops", "ShopsController@indexShops"),
        Route::Get("/shops/{team}/products", "SellersController@indexProducts"),
        Route::Get("/shops/{team}/wishlist", "WishlistController@indexWishlist"),
        Route::Put("/shops/{team}/wishlist", "WishlistController@updateWishlist"),
        Route::Delete("/shops/{team}/wishlist", "WishlistController@deleteWishlist"),
    ]),
    Route::Group([
        "prefix" => "partner/api",
        "middleware" => ["authPartner"]
    ], [

        Route::Get("/shops/{team}", "SellersController@indexAll"),
        /*
        * Products
        */
        Route::Get("/shops/{team}/products", "SellersController@indexProducts"),
        Route::Post("/shops/{team}/products", "SellersController@addProduct"),
        Route::Delete("/shops/{team}/products/{product}", "SellersController@removeProduct"),
        Route::Put("/shops/{team}/products/{product}", "SellersController@updateProduct"),
        /*
        * Categories
        */
        Route::Post("/shops/{team}/categories", "SellersController@addCategory"),
        Route::Delete("/shops/{team}/categories/{category}", "SellersController@removeCategory"),
        Route::Put("/shops/{team}/categories/{category}", "SellersController@updateCategory"),
        /*
        * Hazards
        */
        Route::Post("/shops/{team}/hazards", "SellersController@addHazard"),
        Route::Delete("/shops/{team}/hazards/{hazard}", "SellersController@removeHazard"),
        Route::Put("/shops/{team}/hazards/{hazard}", "SellersController@updateHazard"),
        /*
        * Ingredients
        */
        Route::Post("/shops/{team}/products/ingredients", "SellersController@addIngredient"),
        Route::Delete("/shops/{team}/products/{ingredient}", "SellersController@removeIngredient"),
        Route::Put("/shops/{team}/products/{ingredient}", "SellersController@updateIngredient"),

        /*
        * Shops
        */
        Route::Get("/shops", "ShopsController@indexOwnedShops"),
        Route::Post("/shops", "ShopsController@createShop"),
        Route::Put("/shops/{team}", "ShopsController@updateShop"),
    ]),
];
