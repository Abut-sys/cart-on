<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $userWishlistIds = Wishlist::where('user_id', auth()->id())
            ->pluck('product_id')->toArray();

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
                    $query->join('products', 'wishlists.product_id', '=', 'products.id')
                        ->orderBy('products.price', 'asc');
                    break;
                case 'highest_price':
                    $query->join('products', 'wishlists.product_id', '=', 'products.id')
                        ->orderBy('products.price', 'desc');
                    break;
            }
        }

        $wishlists = $query->paginate(12);

        return view('wishlist', compact('wishlists', 'userWishlistIds'));
    }

    public function addToWishlist(Request $request)
    {
        $productId = $request->product_id;

        if (Auth::check()) {
            $user = auth()->user();

            $wishlist = Wishlist::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($wishlist) {
                $wishlist->delete();
                $wishlistCount = Wishlist::where('user_id', $user->id)->count();
            } else {
                Wishlist::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                ]);
                $wishlistCount = Wishlist::where('user_id', $user->id)->count();
            }

            return response()->json([
                'status' => $wishlist ? 'removed' : 'added',
                'wishlistCount' => $wishlistCount,
            ], 200);
        } else {
            $wishlist = session('wishlist', []);

            if (in_array($productId, $wishlist)) {
                $wishlist = array_diff($wishlist, [$productId]);
            } else {
                $wishlist[] = $productId;
            }

            session(['wishlist' => $wishlist]);

            return response()->json([
                'status' => in_array($productId, $wishlist) ? 'added' : 'removed',
                'wishlistCount' => count($wishlist),
            ], 200);
        }
    }
}
