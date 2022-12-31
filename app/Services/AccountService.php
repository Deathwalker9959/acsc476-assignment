<?php

namespace App\Services;

use App\Models\User;

class AccountService
{
    public static function createAccount($email, $password)
    {
        transaction()->begin();
        $user = User::where([
            [
                'email',
                '=',
                "{$email}"
            ]
        ]);
        transaction()->commit();

        return $user;
    }
}
