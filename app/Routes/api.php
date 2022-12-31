<?php

use App\Router\Route;

return [
    Route::Group([
        'prefix' => 'api'
    ], [
        Route::Post("/register", "AccountsController@handleRegister"),
    ]),
];
