<?php


namespace App\Controllers;

use App\Request;

class HomeController {
    public static function index(Request $queryParams)
    {
        echo "homecontroller";
        dd($queryParams);
    }
    public static function dam(Request $queryParams)
    {
        echo "homecontroller";
        dd($queryParams);
    }
    public static function kek(Request $queryParams)
    {
        echo "homecontroller";
        dd($queryParams);
    }
    public static function inexist(Request $queryParams)
    {
        echo "homecontroller";
        dd($queryParams);
    }
}