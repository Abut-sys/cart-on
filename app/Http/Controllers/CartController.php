<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Models\Product;
use App\Models\Cart;
use App\Models\SubVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index(Request $request)
    {
        $userCartIds = CartHelper::getUserCartIds();
        $query = Cart::where('user_id', auth()->id())->with('product');
        $carts = $query->select('carts.*')->paginate(12);
        $totalPrice = $carts->sum(function ($cart) {
            return $cart->product ? $cart->product->price * $cart->quantity : 0;
        });

        return view('cart', compact('carts', 'userCartIds', 'totalPrice'));
    }

    public function addToCart(Request $request)
    {
        if (Auth::check()) {
            $user = auth()->user();
            $productId = $request->product_id;
            $quantity = $request->quantity ?? 1;
            $size = $request->size;
            $color = $request->color;

            $cart = Cart::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->where('size', $size)
                ->where('color', $color)
                ->first();

            if ($cart) {
                $cart->quantity += $quantity;
                $cart->save();
                $status = 'updated';
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'size' => $size,
                    'color' => $color
                ]);
                $status = 'added';
            }

            return redirect()->route('cart.index')->with('status', 'Product added to cart!');
        } else {
            return redirect()->route('login')->with('error', 'Please log in to add items to your cart.');
        }
    }

    public function increase($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->quantity += 1;
        $cart->save();

        return redirect()->back();
    }

    public function decrease($id)
    {
        $cart = Cart::findOrFail($id);
        if ($cart->quantity > 1) {
            $cart->quantity -= 1;
            $cart->save();
        }

        return redirect()->back();
    }

    public function remove($id)
    {
        Cart::findOrFail($id)->delete();
        return back();
    }
}
