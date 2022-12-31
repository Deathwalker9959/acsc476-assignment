<?php

namespace App\Middleware;

use App\Interfaces\MiddlewareInterface;
use App\Router\Request;

class Auth implements MiddlewareInterface
{

    public function handle(Request $request)
    {
        return $this->authenticate();
    }

    protected function authenticate()
    {
        if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['user_id']))
            return true;

        return response()->view("error.NotLoggedIn");
    }
}
