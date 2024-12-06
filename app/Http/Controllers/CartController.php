<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $userCartIds = Cart::where('user_id', auth()->id())
            ->pluck('product_id')->toArray();

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
                    $query->join('products', 'carts.product_id', '=', 'products.id')
                        ->orderBy('products.price', 'asc');
                    break;
                case 'highest_price':
                    $query->join('products', 'carts.product_id', '=', 'products.id')
                        ->orderBy('products.price', 'desc');
                    break;
            }
        }

        $carts = $query->paginate(12);

        return view('cart', compact('carts', 'userCartIds'));
    }

    public function addToCart(Request $request)
    {
        $productId = $request->product_id;

        if (Auth::check()) {
            $user = auth()->user();

            $cart = Cart::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($cart) {
                $cart->delete();
                $cartCount = Cart::where('user_id', $user->id)->count();
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                ]);
                $cartCount = Cart::where('user_id', $user->id)->count();
            }

            return response()->json([
                'status' => $cart ? 'removed' : 'added',
                'cartCount' => $cartCount,
            ], 200);
        } else {
            $cart = session('cart', []);

            if (in_array($productId, $cart)) {
                $cart = array_diff($cart, [$productId]);
            } else {
                $cart[] = $productId;
            }

            session(['cart' => $cart]);

            return response()->json([
                'status' => in_array($productId, $cart) ? 'added' : 'removed',
                'cartCount' => count($cart),
            ], 200);
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
