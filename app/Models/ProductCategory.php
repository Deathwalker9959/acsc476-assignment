<?php

namespace App\Models;
use App\Models\Traits\Timestamps;

class ProductCategory extends Model
{
    use Timestamps;
    public static $table = "product_categories";
}
