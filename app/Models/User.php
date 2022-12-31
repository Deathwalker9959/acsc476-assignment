<?php

namespace App\Models;
use App\Models\Traits\SoftDeletes;

class User extends Model
{
    use SoftDeletes;
    protected static $table = 'users';
    protected $hidden = ['password', 'remember_token'];
}
