<?php

namespace App\Http\Controllers;

use App\Helpers\WishlistHelper;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $userWishlistIds = WishlistHelper::getUserWishlistIds();

        $query = Wishlist::where('user_id', auth()->id())->with('product');

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'bestselling':
                    $query->orderBy('sales', 'desc');
                    break;
                case 'lowest_price':
                    $query->with(['product' => function ($query) {
                        $query->orderBy('price', 'asc');
                    }]);
                    break;
                case 'highest_price':
                    $query->with(['product' => function ($query) {
                        $query->orderBy('price', 'desc');
                    }]);
                    break;
            }
        }

        $wishlists = $query->paginate(12);

        return view('wishlist', compact('wishlists', 'userWishlistIds'));
    }

    public function addToWishlist(Request $request)
    {
        if (Auth::check()) {
            $user = auth()->user();
            $productId = $request->product_id;

            $wishlist = Wishlist::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($wishlist) {
                $wishlist->delete();
                $status = 'removed';
            } else {
                Wishlist::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                ]);
                $status = 'added';
            }

            $wishlistCount = Wishlist::where('user_id', $user->id)->count();

            return response()->json([
                'status' => $status,
                'wishlistCount' => $wishlistCount
            ], 200);
        } else {
            return response()->json([
                'status' => 'login_required'
            ], 401);
        }
    }
}
