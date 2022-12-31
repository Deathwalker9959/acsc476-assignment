<?php

namespace App\Services;

use App\Session;
use App\Models\User;
use App\Facades\Password;

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

    public static function authenticate(int $userId)
    {
        Session::start();
        Session::set('user_id', $userId);
    }

    public static function login(array $values)
    {
        extract($values, EXTR_SKIP);
        $user = User::where([
            ['email', '=', $email]
        ])->first();

        if ($user && password_verify($password, $user['password'])) {
            static::authenticate($user['id']);
            return true;
        }

        return false;
    }
}
