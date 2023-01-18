<?php

namespace App\Services;

use App\Models\Seller;
use App\Models\Team;
use App\Router\Request;
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
        $activeUserId = Session::get('user')['id'];
        $shops = Seller::find($activeUserId)->teams();
        return array_map(function ($team) {
            return $team->getAttributes();
        }, $shops);
    }

    public static function createShop(Request $request)
    {
        $activeUserId = Session::get('user')['id'];
        $shopName = $request->input('name');
        $shopPhoto = $request->input('photo')[0] ?? null;
        $deliveryPrice = $request->input('delivery_price');
        $filePath = null;

        if ($shopPhoto) {
            $fileName = filestorage()->saveBase64Image($shopPhoto, "shops_{$activeUserId}");
            $filePath = filestorage()->getFilePath($fileName, "shops_{$activeUserId}");
        }

        $shop = Team::create([
            'owner_id' => $activeUserId,
            'name' => $shopName,
            'photo_url' => $filePath,
            'delivery_price' => $deliveryPrice,
        ]);

        return $shop;
    }

    public static function updateShop(Request $request, Team $team)
    {
        $activeUserId = Session::get('user')['id'];
        $name = $request->input('name');
        $photo = $request->input('photo')[0] ?? null;
        $deliveryPrice = $request->input('delivery_price');
        $filePath = null;
        $changesMade = false;

        if ($team->photo_url) {
            $realPhotoPath = ltrim($team->photo_url, 'storage/');
            filestorage()->deleteFile(basename($realPhotoPath), $realPhotoPath);
        }

        if ($photo) {
            $fileName = filestorage()->saveBase64Image($photo, "shops_{$activeUserId}");
            $filePath = filestorage()->getFilePath($fileName, "shops_{$activeUserId}");
            $team->photo_url = $filePath;
            $changesMade = true;
        }

        if ($name) {
            $team->name = $name;
            $changesMade = true;
        }

        if ($deliveryPrice) {
            $team->delivery_price = $deliveryPrice;
            $changesMade = true;
        }

        if ($changesMade)
            return $team->save();

        return false;
    }
}
