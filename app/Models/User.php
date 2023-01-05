<?php

namespace App\Models;

use App\Models\Traits\TimeStamps;

class User extends Model
{
    use TimeStamps;
    public static $table = 'users';
    protected $hidden = ['password', 'remember_token'];
}
