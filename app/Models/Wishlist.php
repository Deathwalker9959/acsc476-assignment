<?php

namespace App\Models;
use App\Models\Product;
use App\Models\WishlistProduct;
use App\Models\Traits\Timestamps;

class Wishlist extends Model
{
    use Timestamps;

    public function products() {
        return $this->hasManyThrough(Product::class, WishlistProduct::class, 'id', 'product_id', 'wishlist_id');
    }
}
