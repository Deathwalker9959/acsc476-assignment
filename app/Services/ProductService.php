<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Team;
use App\Router\Request;
use App\Facades\Image;
use App\Models\ProductCategory;
use App\Models\ProductHazard;
use App\Models\ProductIngredient;
use Exception;

class ProductService
{
    public static function indexProducts(Team $team)
    {
        $products = array_map(function ($product) {
            return $product->getAttributes();
        }, Product::where(['team_id', '=', $team->id])->get());

        return $products;
    }

    public static function getProduct($productId)
    {
        $product = Product::find($productId);
        return $product ? $product->getAttributes() : null;
    }

    public static function removeProduct($productId)
    {
        return $productId ? Product::delete($productId) : false;
    }

    public static function updateProduct($productId, $attributes) {
        return $productId ? Product::find($productId)->update($attributes) : false;
    }

    public static function createProduct(Request $request, Team $team)
    {

        $productName = $request->input("name");
        $productDescription = $request->input("description");
        $productPrice = $request->input("price");
        $productPhoto = $request->file("photo") ? Image::encode($request->file("photo")) : null;
        $categoryId = $request->input("category");
        $productIngredients = $request->input("ingredients");
        $productHazards = $request->input("hazards");

        try {
            $product = Product::create([
                'team_id' => $team->id,
                'name' => $productName,
                'description' => $productDescription,
                'price' => $productPrice,
                'photo_url' => $productPhoto,
                'available' => 1,
            ]);

            $productId = $product->getAttributes()['id'];

            if ($categoryId) {
                ProductCategory::create([
                    'product_id' => $productId,
                    'category_id' => $categoryId
                ]);
            }

            if ($productIngredients) {
                array_map(function ($ingredient) use ($productId) {
                    ProductIngredient::create([
                        'product_id' => $productId,
                        'ingredient_id' => $ingredient,
                    ]);
                }, $productIngredients);
            }

            if ($productHazards) {
                array_map(function ($hazard) use ($productId) {
                    ProductHazard::create([
                        'product_id' => $productId,
                        'hazard_id' => $hazard,
                    ]);
                }, $productHazards);
            }

            return true;
        } catch (Exception $err) {
            return $err;
        }
    }

}
