<?php

namespace App\Services;

use App\Session;
use App\Models\Product;
use App\Models\Team;
use App\Router\Request;
use App\Models\ProductCategory;
use App\Models\ProductHazard;
use App\Models\ProductIngredient;
use Exception;

class ProductService
{
    public static function indexProducts(Team $team)
    {
        $products = Product::where([['team_id', '=', $team->id]])->get();

        $products = array_map(function ($product) {
            $productAttributes = $product;
            $productModel = new Product($productAttributes);
            $productAttributes->category = $productModel->category()->getAttributes();
            $productAttributes->ingredients = array_map(function ($ingredient) {
                return $ingredient->getAttributes();
            }, $productModel->ingredients());
            $productAttributes->hazards = array_map(function ($hazard) {
                return $hazard->getAttributes();
            }, $productModel->hazards());
            return $productAttributes;
        }, $products);

        return $products;
    }

    public static function getProduct($productId)
    {
        $product = Product::find($productId);
        return $product ? $product->getAttributes() : null;
    }

    public static function removeProduct(Product $product)
    {

        if (!$product)
            throw new Exception("No product specified");

        $productCategory = $product->category();
        $productHazards = $product->hazards();
        $productIngredients = $product->ingredients();

        if ($productCategory?->id) {
            ProductCategory::delete($productCategory->id);
        }

        array_walk($productHazards, function ($hazard) {
            ProductHazard::delete($hazard->id);
        });

        array_walk($productIngredients, function ($ingredient) {
            ProductIngredient::delete($ingredient->id);
        });

        return $product ? $product->remove() : false;
    }

    public static function updateProduct(Product $product, $attributes)
    {
        if (!$product)
            throw new Exception("No product specified");

        $photo = $attributes['photo'][0] ?? null;

        $updateCategoryId = $attributes['category'][0] && $attributes['category'][0] != 'none' ? $attributes['category'][0] : null;
        $updateHazardsIds = $attributes['hazards'];
        $updateIngredientsIds = $attributes['ingredients'];

        $productCategory = $product->category();
        $productCategoryId = $productCategory?->category_id;

        $productHazards = $product->hazards();
        $productIngredients = $product->ingredients();

        $productHazardsIds = array_map(function ($data) {
            return $data->hazard_id;
        }, $productHazards);

        $productIngredientsIds = array_map(function ($data) {
            return $data->ingredient_id;
        }, $productIngredients);

        $hazardsToDelete = array_diff($productHazardsIds, $updateHazardsIds);
        $ingredientsToDelete = array_diff($productIngredientsIds, $productIngredientsIds);

        /*
        * Delete unselected hazards and ingredients
        */
        array_walk($hazardsToDelete, function ($hazardId) use ($product) {
            $hazardObject = ProductHazard::where([
                ['product_id', '=', $product->id],
                ['hazard_id', '=', $hazardId],
            ])->first();

            if (sizeof($hazardObject) > 0) {
                ProductHazard::delete($hazardObject['id']);
            }
        });

        array_walk($ingredientsToDelete, function ($ingredientId) use ($product) {
            $ingredientObject = ProductHazard::where([
                ['product_id', '=', $product->id],
                ['ingredient_id', '=', $ingredientId],
            ])->first();

            if (sizeof($ingredientObject) > 0) {
                ProductHazard::delete($ingredientObject['id']);
            }
        });

        /*
        * Add any new ingredients or hazards if any
        */
        $newHazardsIds = array_diff($updateHazardsIds, $productHazardsIds);
        $newIngredientsIds = array_diff($updateIngredientsIds, $productIngredientsIds);

        /*
        * Create new hazards and ingredients
        */
        array_walk($newHazardsIds, function ($hazardId) use ($product) {
            ProductHazard::firstOrCreate([
                ['product_id', '=', $product->id],
                ['hazard_id', '=', $hazardId],
            ], [
                'product_id' => $product->id,
                'hazard_id' => $hazardId
            ]);
        });
        array_walk($newIngredientsIds, function ($ingredientId) use ($product) {
            ProductIngredient::firstOrCreate([
                ['product_id', '=', $product->id],
                ['ingredient_id', '=', $ingredientId],
            ], [
                'product_id' => $product->id,
                'ingredient_id' => $ingredientId
            ]);
        });

        /*
        * Update or add category
        */
        if ($updateCategoryId && $updateCategoryId != $productCategoryId) {
            if ($productCategoryId) {
                $productCategory->update([
                    "product_id" => $product->id,
                    "category_id" => $updateCategoryId,
                ]);
            } else {
                ProductCategory::firstOrCreate([
                    ["product_id", '=', $product->id],
                    ["category_id", '=', $updateCategoryId],
                ], [
                    "product_id" => $product->id,
                    "category_id" => $updateCategoryId,
                ]);
            }
        }

        /*
        * Update photo if inputted
        */
        $newPhotoFilePath = null;
        if ($photo) {
            $realPhotoPath = ltrim($product->photo_url, 'storage/');
            filestorage()->deleteFile(basename($realPhotoPath), $realPhotoPath);

            $activeUserId = Session::get('user')['id'];
            $fileName = filestorage()->saveBase64Image($photo, "shops_{$activeUserId}");
            $newPhotoFilePath = filestorage()->getFilePath($fileName, "shops_{$activeUserId}");
        }

        $sanitizedAttributes = [];

        if (isset($attributes['name']))
            $sanitizedAttributes['name'] = $attributes['name'];
        if (isset($attributes['price']))
            $sanitizedAttributes['price'] = $attributes['price'];
        if (isset($newPhotoFilePath))
            $sanitizedAttributes['photo_url'] = $newPhotoFilePath;
        if (isset($attributes['available']))
            $sanitizedAttributes['available'] = $attributes['available'];
        if (isset($attributes['description']))
            $sanitizedAttributes['description'] = $attributes['description'];

        return sizeof($sanitizedAttributes) > 0 ? Product::find($product->id)->update($sanitizedAttributes) : throw new Exception("No update");
    }

    public static function createProduct(Request $request, Team $team)
    {

        $productName = $request->input("name");
        $productDescription = $request->input("description");
        $productPrice = $request->input("price");
        $productPhoto = $request->input("photo") ? $request->input("photo")[0] : null;
        $categoryId = $request->input("category");
        $productIngredients = $request->input("ingredients");
        $productHazards = $request->input("hazards");
        $filePath = null;

        if ($productPhoto) {
            $fileName = filestorage()->saveBase64Image($productPhoto, "team_{$team->id}");
            $filePath = filestorage()->getFilePath($fileName, "team_{$team->id}");
        }

        $categoryId = is_countable($categoryId) ? $categoryId[0] : $categoryId;

        try {
            $product = Product::create([
                'team_id' => $team->id,
                'name' => $productName,
                'description' => $productDescription,
                'price' => $productPrice,
                'photo_url' => $filePath,
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
