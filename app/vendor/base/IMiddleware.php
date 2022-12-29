<?php

namespace App\Router;

use App\Router\Request;
use App\Router\Response;


/**
 * Interface Middleware
 *
 * A middleware interface that defines a method for handling a request.
 */
interface Middleware
{
    /**
     * Handle a request.
     *
     * @param Request $request The request to be handled.
     */
    public function handle(Request $request);
}
