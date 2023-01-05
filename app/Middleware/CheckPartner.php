<?php

namespace App\Middleware;

use App\Session;
use App\Router\Request;
use App\Interfaces\MiddlewareInterface;
use App\Router\MiddlewareResponse;

class CheckPartner implements MiddlewareInterface
{

    public function handle(Request $request)
    {
        return $this->checkAuth();
    }

    protected function checkAuth()
    {
        $user = Session::get('user');
        if ($user && $user["is_partner"])
            return true;
    }
}
