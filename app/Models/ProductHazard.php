<?php

namespace App\Models;
use App\Models\Traits\Timestamps;

class ProductHazard extends Model
{
    use Timestamps;
    public static $table = "product_hazards";
}
