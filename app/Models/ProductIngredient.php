<?php

namespace App\Models;
use App\Models\Traits\Timestamps;

class ProductIngredient extends Model
{
    use Timestamps;
    public static $table = "product_ingredients";
}
