<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Models\Address;
use App\Models\Product;
use App\Models\Cart;
use App\Models\SubVariant;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;

class CartController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;

        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.sanitization');
        Config::$is3ds = config('midtrans.validation');
    }

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
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to add items to your cart.');
        }

        $user = auth()->user();
        $productId = $request->product_id;
        $quantity = max(1, intval($request->quantity ?? 1));
        $size = $request->size;
        $color = $request->color;

        $product = Product::findOrFail($productId);

        if ($size || $color) {
            $variant = SubVariant::where('product_id', $productId)
                ->where('size', $size)
                ->where('color', $color)
                ->first();

            if (!$variant) {
                return redirect()->back()->with('error', 'Selected variant does not exist.');
            }
        }

        $cart = Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('size', $size)
            ->where('color', $color)
            ->first();

        if ($cart) {
            $cart->quantity += $quantity;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'size' => $size,
                'color' => $color
            ]);
        }

        return redirect()->route('cart.index')->with('status', 'Product added to cart!');
    }

    public function checkoutSelected(Request $request)
    {
        $user = auth()->user();
        $selectedCartIds = explode(',', $request->input('selected-products', ''));

        if (empty($selectedCartIds)) {
            return redirect()->route('cart.index')->with('error', 'No products selected.');
        }

        $carts = Cart::where('user_id', $user->id)
            ->whereIn('id', $selectedCartIds)
            ->with(['product', 'product.subVariant'])
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Selected products not found.');
        }

        $totalPrice = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);

        $addresses = Address::whereHas('profile', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $snapToken = app(PaymentService::class)->generateSnapTokenFromCart($carts, $user);

        return view('checkout.form', compact('carts', 'totalPrice', 'addresses', 'snapToken'));
    }

    public function increase($id)
    {
        $cart = Cart::findOrFail($id);

        if ($cart->size || $cart->color) {
            $variant = SubVariant::where('product_id', $cart->product_id)
                ->where('size', $cart->size)
                ->where('color', $cart->color)
                ->first();

            if (!$variant) {
                return redirect()->back()->with('error', 'Variant not found.');
            }

            if ($variant->stock > $cart->quantity) {
                $cart->quantity += 1;
                $cart->save();
            } else {
                return redirect()->back()->with('error', 'Stock not sufficient.');
            }
        } else {
            $product = $cart->product;
            if ($product->stock > $cart->quantity) {
                $cart->quantity += 1;
                $cart->save();
            } else {
                return redirect()->back()->with('error', 'Stock not sufficient.');
            }
        }

        return redirect()->back();
    }

    public function decrease($id)
    {
        $cart = Cart::findOrFail($id);

        if ($cart->quantity > 1) {
            $cart->quantity -= 1;
            $cart->save();
        } else {
            $cart->delete();
        }

        return redirect()->back();
    }

    public function remove($id)
    {
        Cart::findOrFail($id)->delete();
        return back();
    }
}
