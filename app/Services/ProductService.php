<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public static function indexProducts()
    {
        $products = array_map(function ($team) {
            return $team->getAttributes();
        }, Product::all());

        return $products;
    }

    public static function getProduct($productId)
    {
        $product = Product::find($productId);
        return $product ? $product->getAttributes() : null;
    }

    public static function removeProduct($productId) {
        $product = Product::find($productId);
        return $product ? $product->delete() : false;
    }

    public static function addProduct($attributes) {
        Product::create($attributes);
    }
}
