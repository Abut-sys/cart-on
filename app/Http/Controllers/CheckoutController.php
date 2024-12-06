<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Checkout;
use App\Models\Order;
use App\Models\Product;
use App\Models\SubVariant;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Midtrans\Config;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
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

    public function show($id, Request $request)
    {
        $user = auth()->user();
        $product = Product::with(['subVariant'])->findOrFail($id);
        $quantity = $request->input('quantity', 1);
        $selectedColor = $request->input('color');
        $selectedSize = $request->input('size');

        $variant = SubVariant::where('product_id', $product->id)
            ->where('color', $selectedColor)
            ->where('size', $selectedSize)
            ->first();

        if (!$variant || $quantity > $variant->stock) {
            return redirect()->back()->with('error', 'Selected variant not available or insufficient stock.');
        }

        $addresses = Address::whereHas('profile', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $vouchers = Voucher::valid()->get();

        $totalPrice = $product->price * $quantity;

        $snapToken = $this->paymentService->generateSnapToken($product, $variant, $quantity, $user);

        return view('checkout.form', compact('product', 'variant', 'quantity', 'totalPrice', 'addresses', 'vouchers', 'snapToken'));
    }

    public function processPayment(Request $request)
    {
        $user = auth()->user();
        $product = Product::findOrFail($request->input('product_id'));
        $variant = SubVariant::findOrFail($request->input('variant_id'));

        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:sub_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($request->input('quantity') > $variant->stock) {
            return redirect()->back()->with('error', 'Insufficient stock for the selected variant.');
        }

        $checkout = Checkout::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'address_id' => $request->input('address_id'),
            'voucher_code' => $request->input('voucher_code'),
            'quantity' => $request->input('quantity'),
            'shipping_method' => $request->input('shipping_method', 'standard'),
            'amount' => $product->price * $request->input('quantity'),
        ]);

        $order = Order::create([
            'checkout_id' => $checkout->id,
            'order_date' => now(),
            'unique_order_id' => uniqid('ORDER-'),
            'address' => $checkout->address->address_line1,
            'amount' => $checkout->amount,
            'payment_status' => 'pending',
            'order_status' => 'pending',
        ]);

        $snapToken = $this->paymentService->generateSnapToken($product, $variant, $request->input('quantity'), $user, $order->unique_order_id, $checkout->amount);

        return view('checkout.form', [
            'snapToken' => $snapToken,
            'order' => $order,
            'product' => $product,
            'variant' => $variant,
            'quantity' => $request->input('quantity'),
            'totalPrice' => $checkout->amount,
            'addresses' => Address::all(),
        ]);
    }

    public function paymentSuccess(Request $request)
    {
        $order = Order::find($request->get('order_id'));
        if ($order) {
            $order->update(['status' => 'completed']);
        }

        return view('checkout.success', compact('order'));
    }

    public function paymentFailed(Request $request)
    {
        $order = Order::find($request->get('order_id'));
        if ($order) {
            $order->update(['status' => 'failed']);
        }

        return view('checkout.failed', compact('order'));
    }
}
