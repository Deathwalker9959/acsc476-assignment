<?php


namespace App\Controllers;

use App\Controller;
use App\Router\Request;
use App\Models\Team;
use App\Models\Product;
use App\Router\RequestValidator;
use App\HttpStatusCodes;
use App\Models\Category;
use App\Models\Hazard;
use App\Models\Ingredient;
use App\Services\CategoriesService;
use App\Services\HazardsService;
use App\Services\IngredientsService;
use App\Services\ProductService;
use Exception;

class SellersController extends Controller
{
    private static function validate(Request $request, array $keys)
    {
        if (!RequestValidator::validateInputKeys($request, $keys)) {
            return response()->status(HttpStatusCodes::HTTP_BAD_REQUEST)->body("One or more form inputs are invalid");
        }

        return true;
    }

    public static function index(Request $queryParams, $loggedIn)
    {
        return response()->view('dashboard.Dashboard');
    }

    public static function indexAll(Request $queryParams, Team $team)
    {
        $respObject = [
            "products" => ProductService::indexProducts($team),
            "categories" => CategoriesService::indexCategories($team),
            "hazards" => HazardsService::indexHazards($team),
            "ingredients" => IngredientsService::indexIngredients($team)
        ];

        return response()->json($respObject)->send();
    }

    public static function indexProducts(Request $request, Team $team)
    {
        return response()->json(ProductService::indexProducts($team))->send();
    }

    public static function removeProduct(Request $request, Team $team, Product $product)
    {
        try {
            ProductService::removeProduct($product);
            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body($err)->send();
        }
    }


    public static function updateProduct(Request $request, Team $team, Product $product)
    {

        $validate = static::validate($request, ['attributes']);
        if ($validate !== true)
            return $validate;

        $attributes = json_decode($request->input("attributes"));

        try {
            ProductService::updateProduct($product, (array)$attributes);
            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body($err)->send();
        }
    }

    public static function addProduct(Request $request, Team $team)
    {
        $validate = static::validate($request, ['name', 'price']);
        if ($validate !== true)
            return $validate;

        try {
            $ret = ProductService::createProduct($request, $team);
        } catch (Exception $err) {
            return response()->status(HttpStatusCodes::HTTP_BAD_REQUEST)->body($err->getMessage())->send();
        }
        return response()->status(HttpStatusCodes::HTTP_OK)->send();
    }

    public static function addIngredient(Request $request, Team $team)
    {

        $validate = static::validate($request, ['name']);
        if ($validate !== true)
            return $validate;

        try {
            $ret = IngredientsService::addIngredient($request, $team);
        } catch (Exception $err) {
            return response()->status(HttpStatusCodes::HTTP_BAD_REQUEST)->body($err->getMessage())->send();
        }
        return response()->status(HttpStatusCodes::HTTP_OK)->send();
    }

    public static function removeIngredient(Request $request, Ingredient $product)
    {
        try {
            IngredientsService::removeIngredient($product->id);

            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body("Could not remove")->send();
        }
    }


    public static function updateIngredient(Request $request, Ingredient $product)
    {

        $validate = static::validate($request, ['attributes']);
        if ($validate !== true)
            return $validate;

        $attributes = $request->input("attributes");

        try {
            IngredientsService::updateIngredient($product->id, $attributes);
            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body("Could not update")->send();
        }
    }

    public static function addHazard(Request $request, Team $team)
    {

        $validate = static::validate($request, ['name']);
        if ($validate !== true)
            return $validate;

        try {
            $ret = HazardsService::addHazard($request, $team);
        } catch (Exception $err) {
            return response()->status(HttpStatusCodes::HTTP_BAD_REQUEST)->body("Could not create")->send();
        }
        return response()->status(HttpStatusCodes::HTTP_OK)->send();
    }

    public static function removeHazard(Request $request, Team $team, Hazard $product)
    {
        try {
            HazardsService::removeHazard($product->id, $team);

            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body("Could not remove")->send();
        }
    }

    public static function updateHazard(Request $request, Team $team, Hazard $product)
    {

        $validate = static::validate($request, ['attributes']);
        if ($validate !== true)
            return $validate;

        $attributes = $request->input("attributes");

        try {
            HazardsService::updateHazard($product->id, $attributes, $team);
            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body("Could not update")->send();
        }
    }

    public static function addCategory(Request $request, Team $team)
    {

        $validate = static::validate($request, ['name']);
        if ($validate !== true)
            return $validate;

        try {
            $ret = CategoriesService::addCategory($request, $team);
        } catch (Exception $err) {
            return response()->status(HttpStatusCodes::HTTP_BAD_REQUEST)->body($err->getMessage())->send();
        }
        return response()->status(HttpStatusCodes::HTTP_OK)->send();
    }

    public static function removeCategory(Request $request, Team $team, Category $product)
    {
        try {
            CategoriesService::removeCategory($product->id, $team);

            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body("Could not remove")->send();
        }
    }

    public static function updateCategory(Request $request, Team $team, Category $product)
    {

        $validate = static::validate($request, ['attributes']);
        if ($validate !== true)
            return $validate;

        $attributes = $request->input("attributes");

        try {
            CategoriesService::updateCategory($product->id, $team, $attributes);
            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body("Could not update")->send();
        }
    }
}
