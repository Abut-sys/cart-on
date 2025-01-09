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
use Illuminate\Support\Facades\DB;
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

        // $vouchers = DB::table('claim_voucher')
        //     ->where('user_id', $user->id)
        //     ->join('vouchers', 'claim_voucher.voucher_id', '=', 'vouchers.id')
        //     ->select('vouchers.*')
        //     ->get();
        
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
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:sub_variants,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'voucher_code' => 'nullable|string',
            'address_id' => 'required|exists:addresses,id',
            'shipping_method' => 'required|string|in:standard,express'
        ]);

        $voucher = $this->handleVoucher($validated);

        $product = Product::findOrFail($validated['product_id']);
        $variant = SubVariant::findOrFail($validated['variant_id']);
        $this->validateStock($variant, $validated['quantity']);

        $totalPrice = $this->calculateTotalPrice($product, $validated['quantity'], $voucher);

        $checkout = $this->createCheckout($validated, $voucher, $totalPrice);

        $order = $this->createOrder($checkout, $totalPrice);

        $addresses = Address::whereHas('profile', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        $snapToken = $this->paymentService->generateSnapToken($product, $variant, $validated['quantity'], auth()->user(), $order->unique_order_id, $totalPrice, $voucher);

        $quantity = $validated['quantity'];

        return view('checkout.form', compact('snapToken', 'order', 'product', 'variant', 'validated', 'totalPrice', 'checkout', 'voucher', 'quantity', 'addresses'));
    }

    private function handleVoucher($validated)
    {
        $voucherCode = $validated['voucher_code'];
        $voucher = null;

        if ($voucherCode) {
            $voucher = Voucher::where('code', $voucherCode)->first();

            if (!$voucher) {
                throw new \Exception('Voucher tidak ditemukan.');
            }

            if ($voucher->status === 'inactive' || $voucher->usage_limit <= 0) {
                throw new \Exception('Voucher tidak aktif atau sudah kadaluarsa.');
            }

            if ($voucher->isUsedByUser(auth()->user())) {
                throw new \Exception('Anda sudah menggunakan voucher ini.');
            }
        }

        return $voucher;
    }

    private function validateStock($variant, $quantity)
    {
        if ($quantity > $variant->stock) {
            throw new \Exception('Insufficient stock for the selected variant.');
        }
    }

    private function calculateTotalPrice($product, $quantity, $voucher = null)
    {
        $totalPrice = $product->price * $quantity;

        if ($voucher) {
            $totalPrice -= $voucher->discount_value;
            $totalPrice = max(0, $totalPrice);
        }

        return $totalPrice;
    }

    private function createCheckout($validated, $voucher, $totalPrice)
    {
        return Checkout::create([
            'user_id' => auth()->id(),
            'product_id' => $validated['product_id'],
            'address_id' => $validated['address_id'],
            'voucher_code' => $validated['voucher_code'],
            'quantity' => $validated['quantity'],
            'shipping_method' => $validated['shipping_method'],
            'amount' => $totalPrice,
        ]);
    }

    private function createOrder($checkout, $totalPrice)
    {
        return Order::create([
            'checkout_id' => $checkout->id,
            'order_date' => now(),
            'unique_order_id' => 'ORDER-' . uniqid('', true) . '-' . Str::random(6),
            'address' => $checkout->address->address_line1,
            'amount' => $totalPrice,
            'payment_status' => 'pending',
            'order_status' => 'pending',
        ]);
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

        if ($voucher->status === 'inactive' || $voucher->usage_limit <= 0) {
            return response()->json(['success' => false, 'message' => 'This voucher is inactive, expired, or has no more usage left.']);
        }

        $isClaimed = DB::table('claim_voucher')
            ->where('user_id', auth()->id())
            ->where('voucher_id', $voucher->id)
            ->exists();

        if (!$isClaimed) {
            return response()->json(['success' => false, 'message' => 'Voucher belum diklaim']);
        }

        $userVoucher = UserVoucher::where('user_id', auth()->id())
            ->where('voucher_id', $voucher->id)
            ->first();

        if ($userVoucher) {
            return response()->json(['success' => false, 'message' => 'You have already used this voucher.']);
        }

        $newTotal = max(0, $request->total_price - $voucher->discount_value);

        return response()->json([
            'success' => true,
            'new_total' => $newTotal,
        ]);
    }

    public function updateVoucherUsage(Request $request)
    {
        $voucher = Voucher::where('code', $request->voucher_code)->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Voucher tidak ditemukan.']);
        }

        if ($voucher->status === 'inactive' || $voucher->usage_limit <= 0) {
            return response()->json(['success' => false, 'message' => 'Voucher tidak aktif atau sudah kadaluarsa.']);
        }

        UserVoucher::create([
            'user_id' => auth()->id(),
            'voucher_id' => $voucher->id,
        ]);

        $voucher->decrementUsage();

        return response()->json(['success' => true]);
    }
}
