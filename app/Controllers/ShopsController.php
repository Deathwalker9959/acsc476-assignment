<?php


namespace App\Controllers;

use App\Controller;
use App\Models\Team;
use App\Models\Product;
use App\Router\Request;
use App\Services\ShopService;

class ShopsController extends Controller
{
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
}
