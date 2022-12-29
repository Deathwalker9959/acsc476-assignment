<?php

namespace App\Auth;

use Exception;

class AuthenticationException extends Exception
{
    /**
     * Create a new authentication exception.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = 'Unauthenticated.', array $guards = [])
    {
        parent::__construct($message);
    }
}
