<?php

namespace App\Services;

use App\Models\Seller;
use App\Models\Team;
use App\Session;

class ShopService
{
    public static function indexShops()
    {
        $teams = array_map(function ($team) {
            return $team->getAttributes();
        }, Team::all());

        return $teams;
    }

    public static function getShop($shopId)
    {
        $shop = Team::find($shopId);
        return $shop ? $shop->getAttributes() : null;
    }

    public static function getOwnedShops()
    {
        $activeUser = Session::get('user')['id'];
        $shops = Seller::find($activeUser)->teams();
        return array_map(function ($team) {
            return $team->getAttributes();
        }, $shops);
    }
}
