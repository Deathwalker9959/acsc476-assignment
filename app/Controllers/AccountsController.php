<?php


namespace App\Controllers;

use App\Controller;
use App\Router\Request;
use App\Services\AccountService;

class AccountsController extends Controller
{
    public static function login(Request $request)
    {
        return response()->view('home.signin');
    }
    public static function register(Request $request)
    {
        return response()->view('home.signin', ["register" => true]);
    }
    public static function handleRegister(Request $request) {
        dd( AccountService::createAccount($request->input('email'), $request->input('password')));
    }
}
