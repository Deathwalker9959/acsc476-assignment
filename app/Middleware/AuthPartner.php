<?php

namespace App\Middleware;

use App\HttpStatusCodes;
use App\Session;
use App\Router\Request;
use App\Interfaces\MiddlewareInterface;
use App\Router\MiddlewareResponse;
use App\Models\Seller;

class AuthPartner implements MiddlewareInterface
{

    public function handle(Request $request, $models)
    {
        return $this->authenticate($models);
    }

    protected function authenticate($models = null)
    {
        $user = Session::get('user');
        if (!isset($user) || !$user['id'] || !$user['is_partner'])
            return new MiddlewareResponse(false, response()->status(HttpStatusCodes::HTTP_UNAUTHORIZED)->redirect("/"));

        if (isset($models['team']) && !in_array($models['team']->id,Seller::find($user['id'])->teamIds())) 
            return new MiddlewareResponse(false, response()->status(HttpStatusCodes::HTTP_UNAUTHORIZED));

        return new MiddlewareResponse(true);
    }
}
