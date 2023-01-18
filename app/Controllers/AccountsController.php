<?php


namespace App\Controllers;

use App\Controller;
use App\HttpStatusCodes;
use App\Router\Request;
use App\Router\RequestValidator;
use App\Services\AccountService;

class AccountsController extends Controller
{
    public static function login(Request $request, $middlewareResponses = null)
    {
        if ($middlewareResponses && $middlewareResponses['CheckAuth'] === true) {
            return response()->redirect('/shops');
        }
        return response()->view('home.SignIn');
    }

    public static function logout(Request $request)
    {
        AccountService::logout();

        return response()->status(HttpStatusCodes::HTTP_TEMPORARY_REDIRECT)
            ->body("Logged out succesfully")
            ->redirect('/');
    }

    public static function register(Request $request, $middlewareResponses = null)
    {
        if ($middlewareResponses['CheckAuth'] === true) {
            return response()->redirect('/shops');
        }
        return response()->view('home.SignIn', ["register" => true]);
    }
    public static function handleRegister(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        if (!RequestValidator::validateInputKeys($request, ['email', 'name', 'password'])) {
            return response()->status(HttpStatusCodes::HTTP_BAD_REQUEST)->body("One or more form inputs are invalid {$name}{$email}{$password}");
        }
        
        $user = AccountService::createAccount(compact('name', 'email', 'password'));

        if (!$user) {
            return response()->body("Account already exists")->status(404);
        }

        return response()->redirect('/shops');
    }

    public static function handlePartnerRegister(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        if (!RequestValidator::validateInputKeys($request, ['email', 'name', 'password'])) {
            return response()->status(HttpStatusCodes::HTTP_BAD_REQUEST)->body("One or more form inputs are invalid {$name}{$email}{$password}");
        }
        
        $user = AccountService::createPartner(compact('name', 'email', 'password'));

        if (!$user) {
            return response()->body("Account already exists")->status(404);
        }

        return response()->redirect('/dashboard');
    }

    public static function handleLogin(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $loggedIn = AccountService::login(compact('email', 'password'));

        if ($loggedIn) {
            return response()->status(HttpStatusCodes::HTTP_OK)
                ->redirect("/shops");
        } else {
            return response()->status(HttpStatusCodes::HTTP_UNAUTHORIZED)
                ->body("The email or password may be incorrect");
        }
    }

    public static function handlePartnerLogin(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $loggedIn = AccountService::loginPartner(compact('email', 'password'));

        if ($loggedIn) {
            return response()->status(HttpStatusCodes::HTTP_OK)
                ->redirect("/dashboard");
        } else {
            return response()->status(HttpStatusCodes::HTTP_UNAUTHORIZED)
                ->body("The email or password may be incorrect");
        }
    }
}
