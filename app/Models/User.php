<?php

namespace App\Models;

class User extends Model
{
    protected static $table = 'users';
    public static $hidden = ['password', 'remember_token'];

    public $id;
    public $name;
    public $password;
    public $remember_token;
    public $created_at;

    public $updated_at;

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
