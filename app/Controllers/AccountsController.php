<?php


namespace App\Controllers;

use App\Controller;
use App\HttpStatusCodes;
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
    public static function handleRegister(Request $request)
    {
        $name = $request->input("name");
        $email = $request->input('email');
        $password = $request->input('password');

        $user = AccountService::createAccount(compact('name', 'email', 'password'));

        if (!$user) {
            return response()->body("Account already exists")->status(404);
        }
    }

    public static function handleLogin(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $loggedIn = AccountService::login(compact('email', 'password'));

        if ($loggedIn) {
            return response()->status(HttpStatusCodes::HTTP_OK)
                ->redirect("/dashboard");
        } else {
            return response()->status(HttpStatusCodes::HTTP_UNAUTHORIZED)
                ->body("The email or password may be incorrect");
        }
    }
}
