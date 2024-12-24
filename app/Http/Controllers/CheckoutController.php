<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Checkout;
use App\Models\Order;
use App\Models\Product;
use App\Models\SubVariant;
use App\Models\UserVoucher;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Midtrans\Config;
use App\Services\PaymentService;
use Illuminate\Support\Str;

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

        $orderId = 'ORDER-' . uniqid('', true) . '-' . Str::random(6);

        $snapToken = $this->paymentService->generateSnapToken($product, $variant, $quantity, $user, $orderId, $totalPrice);

        return view('checkout.form', compact('product', 'variant', 'quantity', 'totalPrice', 'addresses', 'vouchers', 'snapToken'));
    }

    public function processPayment(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:sub_variants,id',
            'quantity' => 'required|integer|min:1',
            'voucher_code' => 'nullable|string|exists:vouchers,code',
        ]);

        $voucherCode = $validated['voucher_code'];
        $voucher = Voucher::where('code', $voucherCode)->first();

        if ($voucher && $voucher->isUsedByUser($user)) {
            return redirect()->back()->with('error', 'You have already used this voucher.');
        }

        if ($voucher && $voucher->status === 'inactive') {
            return redirect()->back()->with('error', 'This voucher is inactive or expired.');
        }

        $product = Product::findOrFail($validated['product_id']);
        $variant = SubVariant::findOrFail($validated['variant_id']);

        if ($validated['quantity'] > $variant->stock) {
            return redirect()->back()->with('error', 'Insufficient stock for the selected variant.');
        }

        $totalPrice = $product->price * $validated['quantity'];
        if ($voucher) {
            $totalPrice -= $voucher->discount_value;
            $totalPrice = max(0, $totalPrice);
        }

        $checkout = Checkout::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'address_id' => $validated['address_id'],
            'voucher_code' => $voucherCode,
            'quantity' => $validated['quantity'],
            'shipping_method' => $request->input('shipping_method', 'standard'),
            'amount' => $totalPrice,
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

        if ($voucher) {
            UserVoucher::create([
                'user_id' => $user->id,
                'voucher_id' => $voucher->id,
            ]);
            $voucher->decrementUsage();
        }

        $snapToken = $this->paymentService->generateSnapToken($product, $variant,  $validated['quantity'], $user, $order->unique_order_id, $checkout->amount);

        return view('checkout.form', compact(
            'snapToken',
            'order',
            'product',
            'variant',
            'validated',
            'totalPrice',
            'checkout',
            'voucher'
        ));
    }

    public function checkVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'total_price' => 'required|numeric',
        ]);

        $voucher = Voucher::where('code', $request->voucher_code)->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Voucher not found.']);
        }

        if ($voucher->status === 'inactive') {
            return response()->json(['success' => false, 'message' => 'This voucher is inactive or expired.']);
        }

        if ($voucher->usage_limit <= 0) {
            return response()->json(['success' => false, 'message' => 'Voucher has no more usage left.']);
        }

        $userVoucher = UserVoucher::where('user_id', auth()->id())->where('voucher_id', $voucher->id)->first();

        if ($userVoucher) {
            return response()->json(['success' => false, 'message' => 'You have already used this voucher.']);
        }

        $voucher->decrementUsage();

        if ($voucher->usage_limit == 0) {
            $voucher->status = 'inactive';
            $voucher->save();
        }

        $newTotal = max(0, $request->total_price - $voucher->discount_value);

        UserVoucher::create([
            'user_id' => auth()->id(),
            'voucher_id' => $voucher->id,
        ]);

        return response()->json([
            'success' => true,
            'new_total' => $newTotal,
        ]);
    }
}
