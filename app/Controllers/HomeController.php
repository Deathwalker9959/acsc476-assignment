<?php


namespace App\Controllers;
use App\Controllers\BaseController;

class HomeController implements BaseController {
    public static function index($queryParams)
    {
        echo "homecontroller";
        dd($queryParams);
    }
}