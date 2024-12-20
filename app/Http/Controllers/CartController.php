<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Models\Product;
use App\Models\Cart;
use App\Models\SubVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    public function index(Request $request)
    {
    $userCartIds = CartHelper::getUserCartIds();
    $query = Cart::where('user_id', auth()->id())->with('product');
    $carts = $query->select('carts.*')->paginate(12);
    $totalPrice = $carts->sum(function ($cart) {
        return $cart->product ? $cart->product->price : 0;
    });

    return view('cart', compact('carts', 'userCartIds', 'totalPrice'));
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

    public function store(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'size' => 'nullable|string',
        'color' => 'nullable|string',
    ]);

    $cart = Cart::updateOrCreate(
        [
            'product_id' => $validated['product_id'],
            'user_id' => auth()->id(),
            'size' => $validated['size'],
            'color' => $validated['color'],
        ],
        ['quantity' => DB::raw('quantity + ' . $validated['quantity'])]
    );

    return redirect()->route('cart.index')->with('success', 'Product added to cart!');
}


}
