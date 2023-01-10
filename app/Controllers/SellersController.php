<?php


namespace App\Controllers;

use App\Controller;
use App\Router\Request;
use App\Models\Team;
use App\Models\Product;
use App\Router\RequestValidator;
use App\Facades\Image;
use App\HttpStatusCodes;
use App\Models\ProductCategory;
use App\Models\ProductHazard;
use App\Models\ProductIngredient;
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

    public static function indexProducts(Request $request, Team $team)
    {
        return response()->json(ProductService::indexProducts($team))->send();
    }

    public static function removeProduct(Request $request, Team $team, Product $product)
    {
        try {
            ProductService::removeProduct($product->id);

            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body($err)->send();
        }
    }


    public static function updateProduct(Request $request, Team $team, Product $product)
    {

        if ($validate = static::validate($request, ['attributes']) !== true) {
            return $validate;
        }

        $attributes = $request->input("attributes");

        try {
            ProductService::updateProduct($product->id, $attributes);
            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body($err)->send();
        }
    }

    public static function addProduct(Request $request, Team $team)
    {
        if ($validate = static::validate($request, ['name']) !== true) {
            return $validate;
        }

        $ret = ProductService::createProduct($request, $team);
        if ($ret !== true) {
            return response()->status(HttpStatusCodes::HTTP_BAD_REQUEST)->body($ret)->send();
        }

        return response()->status(HttpStatusCodes::HTTP_OK)->send();
    }

    public static function addIngredient(Request $request, Team $team)
    {

        if ($validate = static::validate($request, ['name']) !== true) {
            return $validate;
        }

        $ret = IngredientsService::addIngredient($request, $team);
        if ($ret !== true) {
            return response()->status(HttpStatusCodes::HTTP_BAD_REQUEST)->body($ret)->send();
        }

        return response()->status(HttpStatusCodes::HTTP_OK)->send();
    }

    public static function removeIngredient(Request $request, Team $team, Product $product)
    {
        try {
            IngredientsService::removeIngredient($product->id);

            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body($err)->send();
        }
    }


    public static function updateIngredient(Request $request, Team $team, Product $product)
    {

        if ($validate = static::validate($request, ['attributes']) !== true) {
            return $validate;
        }

        $attributes = $request->input("attributes");

        try {
            IngredientsService::updateIngredient($product->id, $attributes);
            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body($err)->send();
        }
    }

    public static function addHazard(Request $request, Team $team)
    {

        if ($validate = static::validate($request, ['name']) !== true) {
            return $validate;
        }

        $ret = HazardsService::addHazard($request);
        if ($ret !== true) {
            return response()->status(HttpStatusCodes::HTTP_BAD_REQUEST)->body($ret)->send();
        }

        return response()->status(HttpStatusCodes::HTTP_OK)->send();
    }

    public static function removeHazard(Request $request, Team $team, Product $product)
    {
        try {
            HazardsService::removeHazard($product->id);

            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body($err)->send();
        }
    }

    public static function updateHazard(Request $request, Team $team, Product $product)
    {

        if ($validate = static::validate($request, ['attributes']) !== true) {
            return $validate;
        }

        $attributes = $request->input("attributes");

        try {
            HazardsService::updateHazard($product->id, $attributes);
            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body($err)->send();
        }
    }

    public static function addCategory(Request $request, Team $team)
    {

        if ($validate = static::validate($request, ['name']) !== true) {
            return $validate;
        }

        $ret = CategoriesService::addCategory($request);
        if ($ret !== true) {
            return response()->status(HttpStatusCodes::HTTP_BAD_REQUEST)->body($ret)->send();
        }

        return response()->status(HttpStatusCodes::HTTP_OK)->send();
    }

    public static function removeCategory(Request $request, Team $team, Product $product)
    {
        try {
            CategoriesService::removeCategory($product->id);

            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body($err)->send();
        }
    }

    public static function updateCategory(Request $request, Team $team, Product $product)
    {

        if ($validate = static::validate($request, ['attributes']) !== true) {
            return $validate;
        }

        $attributes = $request->input("attributes");

        try {
            CategoriesService::updateCategory($product->id, $attributes);
            response()->status(200)->send();
        } catch (Exception $err) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body($err)->send();
        }
    }
}
