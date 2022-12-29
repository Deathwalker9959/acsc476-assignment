<?php

namespace App\Middleware;

use App\Router\Middleware;
use App\Auth\AuthenticationException;
use App\Router\Request;
use App\Router\Response;

class Authenticate implements Middleware
{

    public function handle(Request $request)
    {
        return $this->authenticate();
    }

    protected function authenticate()
    {
        if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['user_id']))
            return true;

       return (new Response())->view("Error.NotLoggedIn");
    }
}
