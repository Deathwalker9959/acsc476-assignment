<?php


namespace App\Controllers;

use App\Controller;
use App\Models\Team;
use App\Router\Request;
use App\HttpStatusCodes;
use App\Router\RequestValidator;
use App\Services\ShopService;

class ShopsController extends Controller
{
    private static function validate(Request $request, array $keys)
    {
        if (!RequestValidator::validateInputKeys($request, $keys)) {
            return response()->status(HttpStatusCodes::HTTP_BAD_REQUEST)->body("One or more form inputs are invalid");
        }

        return true;
    }

    public static function index(Request $queryParams)
    {
        return response()->view('shops.Shops');
    }

    public static function indexShops(Request $request)
    {
        return response()->json(ShopService::indexShops())->send();
    }

    public static function indexOwnedShops(Request $request)
    {
        return response()->json(ShopService::getOwnedShops())->send();
    }

    public static function createShop(Request $request)
    {
        $validate = static::validate($request, ['name']);
        if (!$validate)
            return $validate;

        return response()->json(ShopService::createShop($request))->send();
    }

    public static function updateShop(Request $request, Team $team)
    {

        $result = ShopService::updateShop($request, $team);

        return response()->status($result ? HttpStatusCodes::HTTP_OK : HttpStatusCodes::HTTP_BAD_REQUEST)->send();
    }
}
