<?php

namespace App\Models;

use App\Models\Traits\SoftDeletes;
use App\Models\Traits\Timestamps;

class Product extends Model
{
    use Timestamps, SoftDeletes;

    public static $table = "products";

    public function category()
    {
        return $this->hasOneThrough(Category::class, ProductCategory::class, 'id', 'category_id', 'product_id');
    }

    public function hazards()
    {
        return $this->hasManyThrough(Hazard::class, ProductHazard::class, 'id', 'hazard_id', 'product_id');
    }

    public function ingredients()
    {
        return $this->hasManyThrough(Ingredient::class, ProductIngredient::class, 'id', 'ingredient_id', 'product_id');
    }
}
