<?php

namespace App\Models;

use App\Models\Traits\Timestamps;

class User extends Model
{
    use Timestamps;
    public static $table = 'users';
    protected $hidden = ['password', 'remember_token'];
}
