<?php

namespace App\Services;

use App\Router\Request;
use App\Models\Category;
use App\Models\Team;

class CategoriesService
{
    public static function indexCategories(Team $team)
    {
        $products = Category::where([['team_id', '=', $team->id]])->get();

        return $products;
    }

    public static function getCategory($productId)
    {
        $product = Category::find($productId);
        return $product ? $product->getAttributes() : null;
    }

    public static function removeCategory($productId, Team $team)
    {
        $product = Category::find($productId);

        if (!$product->team_id == $team->id)
            return false;

        return $product ? $product->delete() : false;
    }

    public static function updateCategory($categoryId, Team $team, $attributes)
    {
        $category = $categoryId ? Category::find($categoryId) : null;

        if (!$category->team_id == $team->id)
            return false;

        return $category ? $category->update($attributes) : false;
    }

    public static function addCategory(Request $request, Team $team)
    {
        $categoryName = $request->input('name');
        return Category::firstOrCreate([
            ['name', '=', strtolower($categoryName)],
            ['team_id', '=', $team->id],
        ], [
            'name' => strtolower($categoryName),
            'team_id' => $team->id
        ]);
    }
}
