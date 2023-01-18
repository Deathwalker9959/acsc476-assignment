<?php

namespace App\Services;

use App\Router\Request;
use App\Models\Ingredient;
use App\Models\Team;

class IngredientsService
{
    public static function indexIngredients(Team $team)
    {
        $products = Ingredient::where([['team_id', '=', $team->id]])->get();

        return $products;
    }

    public static function getIngredient($ingredientId)
    {
        $ingredient = Ingredient::find($ingredientId);
        return $ingredient ? $ingredient->getAttributes() : null;
    }

    public static function removeIngredient($ingredientId)
    {
        $ingredient = Ingredient::find($ingredientId);
        return $ingredientId ? $ingredient->delete() : false;
    }

    public static function updateIngredient($ingredientId, $attributes)
    {
        return $ingredientId ? Ingredient::find($ingredientId)->update($attributes) : false;
    }

    public static function addIngredient(Request $request, Team $team)
    {

        $ingredientName = $request->input('name');
        $ingredientDescription = $request->input('description');
        $ingredientPrice = $request->input('price');

        return Ingredient::create([
            'team_id' => $team->id,
            'name' => $ingredientName,
            'description' => $ingredientDescription,
            'price' => $ingredientPrice,
        ]);
    }
}
