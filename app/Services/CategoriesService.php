<?php

namespace App\Services;

use App\Router\Request;
use App\Models\Category;
use App\Models\Team;

class CategoriesService
{
    public static function indexCategories(Team $team)
    {
        $products = array_map(function ($product) {
            return $product->getAttributes();
        }, Category::where(['team_id', '=', $team->id])->get());

        return $products;
    }

    public static function getCategory($productId)
    {
        $product = Category::find($productId);
        return $product ? $product->getAttributes() : null;
    }

    public static function removeCategory($productId)
    {
        $product = Category::find($productId);
        return $product ? $product->delete() : false;
    }

    public static function updateCategory($categoryId, $attributes)
    {
        return $categoryId ? Category::find($categoryId)->update($attributes) : false;
    }

    public static function addCategory(Request $request)
    {
        $categoryName = $request->input('name');
        return Category::firstOrCreate('name', strtolower($categoryName), ['name' => strtolower($categoryName)]);
    }
}
