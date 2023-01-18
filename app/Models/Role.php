<?php

namespace App\Models;
use App\Models\Traits\Timestamps;

class Role extends Model
{
    use Timestamps;
    public static $table = "roles";
}
