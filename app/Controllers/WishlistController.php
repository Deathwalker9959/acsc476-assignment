<?php


namespace App\Controllers;

use App\Controller;
use App\HttpStatusCodes;
use App\Models\User;
use App\Models\Team;
use App\Models\Wishlist;
use App\Models\WishlistProduct;
use App\Router\Request;
use App\Router\RequestValidator;
use App\Session;
use Exception;

class WishlistController extends Controller
{
    public static function indexWishlist(Team $team)
    {
        $activeUserId = Session::get('user')['id'];

        $wishlist = User::find($activeUserId)->wishlist($team->id)->products();
        response()->json($wishlist)->status(HttpStatusCodes::HTTP_OK)->send();
    }
    public static function updateWishlist(Request $request, Team $team)
    {
        $activeUserId = Session::get('user')['id'];
        $products = $request->input('products') ?? [];

        try {
            $wishlist = Wishlist::firstOrCreate([
                ['user_id', '=', $activeUserId],
                ['team_id', '=', $team->id]
            ], [
                'user_id' => $activeUserId,
                'team_id' => $team->id
            ]);

            $wishlistId = $wishlist?->attributes['id'] ?? $wishlist;

            if (sizeof($products) > 0) {
                WishListProduct::where([
                    ['wishlist_id', '=', $wishlistId],
                ])->delete();
            }

            array_walk($products, function ($product) use ($wishlistId) {
                WishlistProduct::create([
                    'product_id' => $product['id'],
                    'wishlist_id' => $wishlistId
                ]);
            });

            response()->status(HttpStatusCodes::HTTP_OK)->send();
        } catch (Exception $e) {
            response()->status(HttpStatusCodes::HTTP_I_AM_A_TEAPOT)->body($e)->send();
        }
    }

    public static function deleteWishlist(Request $request, Team $team)
    {

        $activeUserId = Session::get('user')['id'];

        $wishlist = Wishlist::where([
            ['user_id', '=', $activeUserId],
            ['team_id', '=', $team->id]
        ])->first();

        if (!$wishlist) {
            return response()->body("Wishlist not found")->status(HttpStatusCodes::HTTP_BAD_REQUEST)->send();
        }

        try {
            $wishlistModel = Wishlist::find($wishlist['id']);
            $products = $wishlistModel->products();
            if (sizeof($products) > 0)
                array_walk($products, function ($product) {
                    WishlistProduct::delete($product->id);
                });

            response()->status(HttpStatusCodes::HTTP_OK)->send();
        } catch (Exception $e) {
            response()->status(HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR)->body($e)->send();
        }
    }
}
