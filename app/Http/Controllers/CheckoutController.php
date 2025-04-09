<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
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
        $addresses = Address::whereHas('profile', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $vouchers = DB::table('claim_voucher')
            ->where('user_id', $user->id)
            ->join('vouchers', 'claim_voucher.voucher_id', '=', 'vouchers.id')
            ->select('vouchers.*')
            ->get();

        if ($request->has('quantity')) {
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

            $totalPrice = $product->price * $quantity;
            $orderId = 'ORDER-' . uniqid('', true) . '-' . Str::random(6);
            $snapToken = $this->paymentService->generateSnapToken($product, $variant, $quantity, $user, $orderId, $totalPrice);

            return view('checkout.form', compact('product', 'variant', 'quantity', 'totalPrice', 'addresses', 'vouchers', 'snapToken'));
        }

        if ($request->has('selected-products')) {
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
            $snapToken = $this->paymentService->generateSnapTokenFromCart($carts, $user);

            return view('checkout.form', compact('carts', 'totalPrice', 'addresses', 'vouchers', 'snapToken'));
        }

        return redirect()->route('cart.index')->with('error', 'Invalid request.');
    }

    public function processPayment(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'selected-products' => 'nullable|array',
            'selected-products.*' => 'exists:carts,id',
            'product_id' => 'nullable|exists:products,id',
            'variant_id' => 'nullable|exists:sub_variants,id',
            'quantity' => 'nullable|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'voucher_code' => 'nullable|string',
            'address_id' => 'required|exists:addresses,id',
            'shipping_method' => 'required|string|in:standard,express'
        ]);

        $voucher = $this->handleVoucher($validated);
        $checkoutItems = [];
        $totalPrice = 0;

        DB::beginTransaction();

        if ($request->has('selected-products') && is_array($validated['selected-products'])) {
            list($checkoutItems, $totalPrice) = $this->handleCartCheckout($validated, $voucher, $user);
        } else {
            list($checkoutItems, $totalPrice) = $this->handleSingleProductCheckout($validated, $voucher, $user);
        }

        $order = $this->createOrder($checkoutItems, $totalPrice);

        DB::commit();

        $snapToken = $this->generateSnapToken($checkoutItems, $user, $order->id, $totalPrice);

        return view('checkout.form', compact('snapToken', 'order', 'checkoutItems', 'totalPrice'));
    }

    private function handleCartCheckout($validated, $voucher, $user)
    {
        $selectedCartIds = $validated['selected-products'];
        $carts = Cart::where('user_id', $user->id)
            ->whereIn('id', $selectedCartIds)
            ->with(['product', 'product.subVariant'])
            ->get();

        if ($carts->isEmpty()) {
            throw new \Exception('Produk dalam keranjang tidak ditemukan.');
        }

        $checkoutItems = [];
        $totalPrice = 0;

        foreach ($carts as $cart) {
            $subVariant = $cart->product->subVariant
                ->where('color', $cart->color)
                ->where('size', $cart->size)
                ->first();

            $this->validateStock($subVariant, $cart->quantity);
            $totalPrice += $cart->product->price * $cart->quantity;

            $product = Product::find($cart->product_id);
            if ($product) {
                $product->sales += $cart->quantity;
                $product->save();
            }
        }

        $discountPerItem = 0;
        if ($voucher && $totalPrice > 0) {
            $discountPerItem = $voucher->discount_value / count($carts);
        }

        foreach ($carts as $cart) {
            $subVariant = $cart->product->subVariant
                ->where('color', $cart->color)
                ->where('size', $cart->size)
                ->first();
            $itemTotal = $cart->product->price * $cart->quantity;
            $discountedItemTotal = max(0, $itemTotal - $discountPerItem);
            $checkout = $this->createCheckout([
                'user_id' => $user->id,
                'product_id' => $cart->product_id,
                'address_id' => $validated['address_id'],
                'voucher_code' => $validated['voucher_code'],
                'quantity' => $cart->quantity,
                'shipping_method' => $validated['shipping_method'],
                'amount' => $discountedItemTotal,
            ], $voucher, $discountedItemTotal);

            $checkoutItems[] = $checkout;
            $subVariant->decrement('stock', $cart->quantity);
        }

        Cart::whereIn('id', $selectedCartIds)->delete();

        return [$checkoutItems, $totalPrice];
    }

    private function handleSingleProductCheckout($validated, $voucher, $user)
    {
        $product = Product::findOrFail($validated['product_id']);
        $variant = SubVariant::where('id', $validated['variant_id'])
            ->where('product_id', $product->id)
            ->firstOrFail();

        $this->validateStock($variant, $validated['quantity']);

        $totalPrice = $this->calculateTotalPrice($product, $validated['quantity'], $voucher);

        $checkout = $this->createCheckout($validated, $voucher, $totalPrice);
        $variant->decrement('stock', $validated['quantity']);

        $product->sales += $validated['quantity'];
        $product->save();

        return [[$checkout], $totalPrice];
    }

    private function generateSnapToken($checkoutItems, $user, $orderId, $totalPrice)
    {
        if (count($checkoutItems) > 1) {
            return $this->paymentService->generateSnapTokenFromCart($checkoutItems, $user);
        }

        $checkout = $checkoutItems[0];
        $product = Product::find($checkout->product_id);

        $cart = Cart::where('product_id', $product->id)->where('user_id', $user->id)->first();
        $variant = SubVariant::where('product_id', $product->id)->where('color', $cart->color)->where('size', $cart->size)->first();

        return $this->paymentService->generateSnapToken($product, $variant, $checkout->quantity, $user, $orderId, $totalPrice);
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
        if ($variant && $quantity > $variant->stock) {
            throw new \Exception('Insufficient stock for the selected variant.');
        }
        if (!$variant) {
            throw new \Exception('Variant not found.');
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
            'voucher_code' => $validated['voucher_code'] ?? '',
            'quantity' => $validated['quantity'],
            'shipping_method' => $validated['shipping_method'],
            'amount' => $totalPrice,
        ]);
    }

    private function createOrder($checkout, $totalPrice)
    {
        $order = Order::create([
            'order_date' => now(),
            'unique_order_id' => 'ORDER-' . uniqid('', true) . '-' . Str::random(6),
            'address' => $checkout[0]->address->address_line1,
            'amount' => $totalPrice,
            'payment_status' => 'pending',
            'order_status' => 'pending',
        ]);

        foreach ($checkout as $checkoutItem) {
            DB::table('order_checkouts')->insert([
                'order_id' => $order->id,
                'checkout_id' => $checkoutItem->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $order;
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

        $voucherCode = UserVoucher::create([
            'user_id' => auth()->id(),
            'voucher_id' => $voucher->id,
        ]);

        $voucher->decrementUsage();

        return response()->json(['success' => true]);
    }
}
