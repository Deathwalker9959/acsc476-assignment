<?php

namespace App\Middleware;

use App\HttpStatusCodes;
use App\Session;
use App\Router\Request;
use App\Interfaces\MiddlewareInterface;
use App\Router\MiddlewareResponse;

class Auth implements MiddlewareInterface
{

    public function handle(Request $request, $models)
    {
        return $this->authenticate();
    }

    protected function authenticate()
    {
        $user = Session::get('user');
        if (isset($user) && $user['id'] && !$user['is_partner'])
            return new MiddlewareResponse(true);

        return new MiddlewareResponse(false, response()->status(HttpStatusCodes::HTTP_UNAUTHORIZED)->redirect("/"));
    }
}
