<?php


namespace App\Controllers;

use App\Models\User;
use App\Router\Request;
use App\Router\Response;

class HomeController {
    public static function index(Request $queryParams)
    {
        return (new Response())->view('home.landing');
        // dd($queryParams);
    }
    public static function dam(Request $queryParams , User $user)
    {
        echo "homecontroller@dam";
        return (new Response())->view("error.404");
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