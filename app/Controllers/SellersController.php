<?php


namespace App\Controllers;

use App\Controller;
use App\Models\User;
use App\Router\Request;
use App\Router\Response;

class SellersController extends Controller
{
    public static function index(Request $queryParams, $loggedIn)
    {
        return response()->view('dashboard.Dashboard');
    }
}
