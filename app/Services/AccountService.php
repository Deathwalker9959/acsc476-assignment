<?php

namespace App\Services;

use App\Session;
use App\Models\User;
use App\Facades\Password;
use App\Models\Seller;

class AccountService
{
    public static function createAccount(array $values)
    {
        if (User::where([
            ['email', '=', $values['email']]
        ])->first()) {
            return false;
        }

        $values['password'] = Password::hash($values['password']);

        return User::create($values);
    }

    public static function createPartner(array $values)
    {
        if (Seller::where([
            ['email', '=', $values['email']]
        ])->first()) {
            return false;
        }

        $values['password'] = Password::hash($values['password']);

        return Seller::create($values);
    }

    public static function authenticate($user, $isPartner = false)
    {
        Session::start();
        $user['is_partner'] = $isPartner;
        Session::set('user', $user);
    }

    public static function login(array $values)
    {
        extract($values, EXTR_SKIP);
        $user = User::where([
            ['email', '=', $email]
        ])->first();

        if ($user && password_verify($password, $user['password'])) {
            static::authenticate($user);
            return true;
        }

        return false;
    }

    public static function loginPartner(array $values)
    {
        extract($values, EXTR_SKIP);
        $user = Seller::where([
            ['email', '=', $email]
        ])->first();

        if ($user && password_verify($password, $user['password'])) {
            static::authenticate($user, true);
            return true;
        }

        return false;
    }

    public static function logout() {
        Session::destroy();
    }
}
