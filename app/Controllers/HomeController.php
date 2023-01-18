<?php


namespace App\Controllers;

use App\Controller;
use App\Router\Request;

class HomeController extends Controller
{
    public static function index(Request $queryParams, $loggedIn)
    {
        return response()->view('home.Landing');
    }
}
