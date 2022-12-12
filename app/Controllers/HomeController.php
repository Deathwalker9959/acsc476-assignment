<?php


namespace App\Controllers;
use App\Controllers\BaseController;

class HomeController {
    public static function index($queryParams)
    {
        echo "homecontroller";
        dd($queryParams);
    }
}