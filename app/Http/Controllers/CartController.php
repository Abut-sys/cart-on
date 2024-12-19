<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index(Request $request)
    {
        $userCartIds = CartHelper::getUserCartIds();

        $query = Cart::where('user_id', auth()->id())->with('product');

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

        $carts = $query->paginate(12);

        return view('cart', compact('carts', 'userCartIds'));
    }


    public function addToCart(Request $request)
    {
        if (Auth::check()) {
            $user = auth()->user();
            $productId = $request->product_id;

            $cart = Cart::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($cart) {
                $cart->delete();
                $status = 'removed';
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                ]);
                $status = 'added';
            }

            $cartCount = Cart::where('user_id', $user->id)->count();

            return response()->json([
                'status' => $status,
                'cartCount' => $cartCount
            ], 200);
        } else {
            return response()->json([
                'status' => 'login_required'
            ], 401);
        }
    }


    public function destroy($id)
    {
        $cartItem = Cart::find($id);

        if (!$cartItem) {
            return redirect()->route('cart.index')->with('error', 'Item not found');
        }
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Item deleted successfully');
    }


}
