<?php

namespace App\Models;
use App\Models\Traits\Timestamps;

class Category extends Model
{
    use Timestamps;
    public static $table = "categories";
}
