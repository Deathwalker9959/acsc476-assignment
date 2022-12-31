<?php

namespace App\Middleware;

use App\Interfaces\MiddlewareInterface;
use App\Router\Request;


class CheckAuth implements MiddlewareInterface
{

    public function handle(Request $request)
    {
        return $this->checkAuth();
    }

    protected function checkAuth()
    {
        if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['user_id']))
            return true;

        return;
    }
}
