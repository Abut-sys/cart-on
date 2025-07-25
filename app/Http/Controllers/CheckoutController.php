<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Checkout;
use App\Models\ClaimVoucher;
use App\Models\Order;
use App\Models\Product;
use App\Models\SubVariant;
use App\Models\User;
use App\Models\UserVoucher;
use App\Models\Voucher;
use App\Notifications\AdminOrderNotification;
use App\Notifications\OrderPlacedNotification;
use App\Notifications\OutOfStockNotification;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Services\RajaOngkirService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CheckoutController extends Controller
{
    protected $paymentService;
    protected $rajaOngkirService;

    public function __construct(PaymentService $paymentService, RajaOngkirService $rajaOngkirService)
    {
        $this->paymentService = $paymentService;
        $this->rajaOngkirService = $rajaOngkirService;
    }

    public function show($id = null, Request $request)
    {
        $user = auth()->user();
        $addresses = Address::whereHas('profile', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        // voucher claimed & not used
        $vouchers = DB::table('claim_voucher')
            ->join('vouchers', 'claim_voucher.voucher_id', '=', 'vouchers.id')
            ->where('claim_voucher.user_id', $user->id)
            ->where('claim_voucher.quantity', '>', 0)
            ->where('vouchers.status', 'active')
            ->whereColumn('vouchers.used_count', '<', 'vouchers.usage_limit')
            ->where(function ($query) {
                $query->whereNull('vouchers.end_date')
                    ->orWhere('vouchers.end_date', '>', now());
            })
            ->whereRaw('
        claim_voucher.quantity > (
            SELECT COUNT(*)
            FROM user_voucher
            WHERE user_voucher.user_id = claim_voucher.user_id
            AND user_voucher.voucher_id = claim_voucher.voucher_id
        )
    ')
            ->select('vouchers.*')
            ->get();

        $selectedAddressId = $request->input('address_id') ?? ($addresses->first()->id ?? null);
        $shippingOptions = [];
        $rawProductTotal = 0;

        if ($request->has('quantity') && $id) {
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

            $rawProductTotal = round($product->price * $quantity);

            return view('checkout.form', compact('product', 'variant', 'quantity', 'rawProductTotal', 'addresses', 'vouchers', 'shippingOptions', 'selectedAddressId'));
        }

        if ($request->has('selected-products')) {
            $selectedCartIds = explode(',', $request->input('selected-products', ''));
            if (empty($selectedCartIds) || count($selectedCartIds) === 1 && $selectedCartIds[0] === '') {
                return redirect()->route('cart.index')->with('error', 'No products selected.');
            }

            $carts = Cart::where('user_id', $user->id)
                ->whereIn('id', $selectedCartIds)
                ->with(['product', 'product.subVariant'])
                ->get();

            if ($carts->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Selected products not found.');
            }

            foreach ($carts as $cartItem) {
                $subVariant = $cartItem->product->subVariant
                    ->where('color', $cartItem->color)
                    ->where('size', $cartItem->size)
                    ->first();
                if (!$subVariant || $cartItem->quantity > $subVariant->stock) {
                    return redirect()->route('cart.index')->with('error', 'Stok tidak mencukupi untuk beberapa produk di keranjang.');
                }
            }

            $rawProductTotal = round($carts->sum(fn($cart) => $cart->product->price * $cart->quantity));

            return view('checkout.form', compact('carts', 'rawProductTotal', 'addresses', 'vouchers', 'shippingOptions', 'selectedAddressId'));
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
            'voucher_code' => 'nullable|string',
            'address_id' => 'required|exists:addresses,id',
            'courier' => 'required|string',
            'shipping_service' => 'required|string',
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $voucher = $this->getAndValidateVoucher($validated['voucher_code'] ?? null);

            $rawProductTotal = 0;
            $checkoutSourceItems = collect();
            $totalQuantity = 0;
            $isFromCart = false;

            // --- untuk checkoutSourceItems dan menghitung total awal ---
            if (isset($validated['selected-products']) && is_array($validated['selected-products'])) {
                $isFromCart = true;
                $selectedCartIds = $validated['selected-products'];
                $carts = Cart::where('user_id', $user->id)
                    ->whereIn('id', $selectedCartIds)
                    ->with(['product.subVariant'])
                    ->get();

                if ($carts->isEmpty()) {
                    DB::rollBack();
                    throw new Exception('Produk dalam keranjang tidak ditemukan.');
                }
                $checkoutSourceItems = $carts;
                $rawProductTotal = round($carts->sum(fn($cart) => $cart->product->price * $cart->quantity));
                $totalQuantity = $carts->sum('quantity');
            } else if (isset($validated['product_id']) && isset($validated['quantity'])) {
                $product = Product::findOrFail($validated['product_id']);
                $variant = SubVariant::where('id', $validated['variant_id'])
                    ->where('product_id', $product->id)
                    ->firstOrFail();

                $tempCartItem = (object) [
                    'product_id' => $product->id,
                    'quantity' => $validated['quantity'],
                    'product' => $product,
                    'color' => $variant->color,
                    'size' => $variant->size,
                ];
                $checkoutSourceItems->push($tempCartItem);
                $rawProductTotal = round($product->price * $validated['quantity']);
                $totalQuantity = $validated['quantity'];
            } else {
                DB::rollBack();
                return response()->json(['error' => 'Tidak ada produk yang dipilih untuk checkout.'], 400);
            }

            // --- Validasi stok untuk SEMUA item di checkoutSourceItems ---
            foreach ($checkoutSourceItems as $sourceItem) {
                $product = $sourceItem->product;
                $subVariant = null;
                if (isset($sourceItem->color) && isset($sourceItem->size)) {
                    $subVariant = $product->subVariant
                        ->where('color', $sourceItem->color)
                        ->where('size', $sourceItem->size)
                        ->first();
                } else if (isset($validated['variant_id'])) {
                    $subVariant = SubVariant::where('id', $validated['variant_id'])
                        ->where('product_id', $product->id)
                        ->first();
                }

                if (!$subVariant) {
                    DB::rollBack();
                    throw new Exception('Varian produk tidak ditemukan untuk item: ' . ($sourceItem->product->name ?? 'N/A'));
                }
                $this->validateStock($subVariant, $sourceItem->quantity);
            }

            $shippingCost = round((float) $validated['shipping_cost']);

            $discountAmount = 0;
            if ($voucher) {
                if ($voucher->type === 'percentage') {
                    $discountAmount = ($rawProductTotal * $voucher->discount_value) / 100;
                } elseif ($voucher->type === 'fixed') {
                    $discountAmount = $voucher->discount_value;
                }
                $discountAmount = round(min($discountAmount, $rawProductTotal));
            }

            $finalPrice = max(0, $rawProductTotal - $discountAmount + $shippingCost);
            $finalPrice = round($finalPrice);

            $checkoutRecords = [];

            // --- untuk membuat record Checkout ---
            if ($isFromCart) {
                foreach ($checkoutSourceItems as $cart) {
                    $subVariant = $cart->product->subVariant
                        ->where('color', $cart->color)
                        ->where('size', $cart->size)
                        ->first();

                    $itemBasePrice = round($cart->product->price * $cart->quantity);

                    $itemShippingAllocation = 0;
                    if ($totalQuantity > 0) {
                        $itemShippingAllocation = ($shippingCost * ($cart->quantity / $totalQuantity));
                    }
                    $itemShippingAllocation = round($itemShippingAllocation);

                    $itemAmountForCheckout = max(0, $itemBasePrice);
                    $itemAmountForCheckout = round($itemAmountForCheckout);

                    $checkout = Checkout::create([
                        'user_id' => $user->id,
                        'product_id' => $cart->product_id,
                        'address_id' => $validated['address_id'],
                        'voucher_code' => $validated['voucher_code'] ?? null,
                        'quantity' => $cart->quantity,
                        'courier' => $validated['courier'],
                        'shipping_service' => $validated['shipping_service'],
                        'shipping_cost' => $itemShippingAllocation,
                        'amount' => $itemAmountForCheckout,
                    ]);
                    $checkout->load('product');
                    $checkoutRecords[] = $checkout;

                    $subVariant->decrement('stock', $cart->quantity);
                    if ($subVariant->stock <= 0) {
                        Notification::send(User::where('role', 'admin')->get(), new OutOfStockNotification($subVariant));
                    }

                    $product = Product::find($cart->product_id);
                    if ($product) {
                        $product->increment('sales', $cart->quantity);
                    }
                }
                Cart::whereIn('id', $validated['selected-products'])->delete();
            }
            // --- untuk checkout single product ---
            else {
                $sourceItem = $checkoutSourceItems->first();
                $product = $sourceItem->product;
                $variant = SubVariant::where('id', $validated['variant_id'])
                    ->where('product_id', $product->id)
                    ->firstOrFail();

                $itemBasePrice = round($product->price * $sourceItem->quantity);

                $itemAmountForCheckout = max(0, $itemBasePrice);
                $itemAmountForCheckout = round($itemAmountForCheckout);

                $checkout = Checkout::create([
                    'user_id' => $user->id,
                    'product_id' => $validated['product_id'],
                    'address_id' => $validated['address_id'],
                    'voucher_code' => $validated['voucher_code'] ?? null,
                    'quantity' => $validated['quantity'],
                    'courier' => $validated['courier'],
                    'shipping_service' => $validated['shipping_service'],
                    'shipping_cost' => $shippingCost,
                    'amount' => $itemAmountForCheckout,
                ]);
                $checkout->load('product');
                $checkoutRecords[] = $checkout;

                $variant->decrement('stock', $validated['quantity']);
                if ($variant->stock <= 0) {
                    Notification::send(User::where('role', 'admin')->get(), new OutOfStockNotification($variant));
                }

                $product->increment('sales', $validated['quantity']);
            }

            $order = $this->createOrder($checkoutRecords, $finalPrice, $validated['courier'], $validated['shipping_service'], $shippingCost, $user);

            if ($voucher) {
                $this->updateVoucherUsage($user, $voucher);
            }

            DB::commit();

            // --- Midtrans ---
            $midtransItems = [];
            foreach ($checkoutRecords as $item) {
                $unitPrice = $item->product->price;
                $midtransItems[] = [
                    'id' => $item->product_id,
                    'price' => (int) $unitPrice,
                    'quantity' => (int) $item->quantity,
                    'name' => $item->product->name ?? 'Produk Tidak Dikenal',
                ];
            }

            // shipping_cost
            if ($shippingCost > 0) {
                $midtransItems[] = [
                    'id' => 'SHIPPING-' . $order->unique_order_id,
                    'price' => (int) $shippingCost,
                    'quantity' => 1,
                    'name' => 'Biaya Pengiriman (' . $validated['courier'] . ' ' . $validated['shipping_service'] . ')',
                ];
            }

            // discount
            if ($discountAmount > 0) {
                $midtransItems[] = [
                    'id' => 'DISCOUNT-' . $order->unique_order_id,
                    'price' => (int) -$discountAmount,
                    'quantity' => 1,
                    'name' => 'Diskon Voucher (' . ($voucher->code ?? 'N/A') . ')',
                ];
            }

            $midtransCalculatedTotal = 0;
            foreach ($midtransItems as $mi) {
                $midtransCalculatedTotal += $mi['price'] * $mi['quantity'];
            }

            $snapToken = $this->paymentService->generateSnapTokenForOrder(
                $order->unique_order_id,
                (int) $finalPrice,
                $user,
                $midtransItems
            );

            $order->snap_token = $snapToken;
            $order->save();

            return response()->json([
                'success' => true,
                'snapToken' => $snapToken,
                'orderId' => $order->unique_order_id,
                'checkoutIds' => collect($checkoutRecords)->pluck('id'),
                'message' => 'Transaksi berhasil dibuat.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error during checkout process: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    private function getAndValidateVoucher(?string $voucherCode): ?Voucher
    {
        if (!$voucherCode) {
            return null;
        }

        $voucher = Voucher::where('code', $voucherCode)->first();

        if (!$voucher) {
            throw new Exception('Voucher tidak ditemukan.');
        }

        if ($voucher->status === 'inactive') {
            throw new Exception('Voucher tidak aktif.');
        }

        if ($voucher->usage_limit <= 0) {
            throw new Exception('Voucher sudah habis.');
        }

        if ($voucher->end_date && now()->greaterThan($voucher->end_date)) {
            throw new Exception('Voucher sudah kadaluarsa.');
        }

        $userId = auth()->id();

        $claim = DB::table('claim_voucher')
            ->where('user_id', $userId)
            ->where('voucher_id', $voucher->id)
            ->first();

        $usedCount = DB::table('user_voucher')
            ->where('user_id', $userId)
            ->where('voucher_id', $voucher->id)
            ->count();

        if ($claim) {
            if ($usedCount >= $voucher->max_per_user) {
                throw new Exception('Slot max per user sudah habis.');
            }
        }

        return $voucher;
    }

    private function validateStock(?SubVariant $variant, int $quantity): void
    {
        if (!$variant) {
            throw new Exception('Varian tidak ditemukan.');
        }
        if ($quantity > $variant->stock) {
            throw new Exception('Stok tidak mencukupi untuk varian yang dipilih.');
        }
    }

    private function createOrder(array $checkoutItems, float $finalPrice, string $courier, string $shippingService, float $shippingCost, User $user)
    {
        $address = Address::findOrFail($checkoutItems[0]->address_id);
        $order = Order::create([
            'order_date' => now(),
            'unique_order_id' =>  'ORDER-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'address' => $address->address_line1 . ', ' . $address->city . ', ' . $address->postal_code,
            'courier' => $courier,
            'shipping_service' => $shippingService,
            'shipping_cost' => round($shippingCost),
            'amount' => round($finalPrice),
            'payment_status' => 'pending',
            'order_status' => 'pending',
        ]);

        foreach ($checkoutItems as $checkoutItem) {
            DB::table('order_checkouts')->insert([
                'order_id' => $order->id,
                'checkout_id' => $checkoutItem->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $order;
    }

    public function notifyOrderCreated(Request $request)
    {
        $order = Order::with('checkouts.user')->where('unique_order_id', $request->order_id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->user) {
            $order->user->notify(new OrderPlacedNotification($order, $order->checkouts));
        }

        Notification::send(
            User::where('role', 'admin')->get(),
            new AdminOrderNotification($order, $order->checkouts, $order->user)
        );

        return response()->json(['message' => 'Notification sent']);
    }

    public function updateVoucherUsage(User $user, Voucher $voucher)
    {
        UserVoucher::create([
            'user_id' => $user->id,
            'voucher_id' => $voucher->id,
        ]);
        $voucher->decrementUsage();

        $claim = ClaimVoucher::where('user_id', $user->id)
            ->where('voucher_id', $voucher->id)
            ->first();

        $usedCount = UserVoucher::where('user_id', $user->id)
            ->where('voucher_id', $voucher->id)
            ->count();

        if (!$claim) {
            ClaimVoucher::create([
                'user_id' => $user->id,
                'voucher_id' => $voucher->id,
                'quantity' => 1,
            ]);
        } else {
            if ($usedCount > $claim->quantity && $claim->quantity < $voucher->max_per_user) {
                $claim->increment('quantity', 1);
            }
        }
    }

    public function checkVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'raw_product_total' => 'required|numeric|min:0',
        ]);

        $voucher = $this->getAndValidateVoucher($request->voucher_code);

        $rawProductTotal = round((float) $request->raw_product_total);

        $discountAmount = 0;
        if ($voucher->type === 'percentage') {
            $discountAmount = ($request->raw_product_total * $voucher->discount_value) / 100;
        } elseif ($voucher->type === 'fixed') {
            $discountAmount = $voucher->discount_value;
        }

        $discountAmount = round(min($discountAmount, $rawProductTotal));

        $newTotalAfterVoucher = max(0, $rawProductTotal - $discountAmount);
        $newTotalAfterVoucher = round($newTotalAfterVoucher);

        return response()->json([
            'success' => true,
            'discount_amount' => $discountAmount,
            'new_total_after_voucher' => $newTotalAfterVoucher,
            'voucher_type' => $voucher->type,
            'voucher_value' => $voucher->discount_value,
        ]);
    }

    public function getShippingCost(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'courier' => 'required|string',
            'service' => 'nullable|string',
        ]);

        $address = Address::findOrFail($request->address_id);
        $destinationCityId = $address->city_id;
        $weight = 100;
        $apiKey = config('rajaongkir.api_key');
        $originCity = config('rajaongkir.origin_city');

        if ($destinationCityId) {
            $costResponse = Http::withHeaders([
                'key' => $apiKey,
                'content-type' => 'application/x-www-form-urlencoded',
            ])->asForm()->post('https://api.rajaongkir.com/starter/cost', [
                'origin' => $originCity,
                'destination' => $destinationCityId,
                'weight' => $weight,
                'courier' => $request->courier,
            ]);

            if ($costResponse->successful()) {
                $results = $costResponse->json()['rajaongkir'];

                if ($results['status']['code'] == 200 && !empty($results['results'])) {
                    if ($request->filled('service')) {
                        $cost = collect($results['results'][0]['costs'])
                            ->firstWhere('service', $request->service);

                        if ($cost) {
                            return response()->json(['cost' => $cost['cost'][0]['value']]);
                        } else {
                            return response()->json(['error' => 'Service tidak ditemukan untuk kurir ini'], 404);
                        }
                    } else {
                        $formattedCosts = collect($results['results'][0]['costs'])->map(function ($item) {
                            return [
                                'service' => $item['service'],
                                'description' => $item['description'],
                                'cost' => $item['cost'][0]['value'],
                            ];
                        });
                        return response()->json($formattedCosts);
                    }
                } else {
                    return response()->json(['error' => $results['status']['description']], $results['status']['code']);
                }
            } else {
                return response()->json(['error' => 'Gagal mendapatkan biaya pengiriman dari API: ' . $costResponse->body()], $costResponse->status());
            }
        } else {
            $storeLat = config('shipping.origin_latitude');
            $storeLng = config('shipping.origin_longitude');

            if (!$address->latitude || !$address->longitude) {
                return response()->json(['error' => 'Alamat belum punya koordinat, mohon update!'], 400);
            }

            $distance = $this->calculateHaversineDistance(
                $storeLat,
                $storeLng,
                $address->latitude,
                $address->longitude
            );

            $costPerKm = 3000;
            $finalCost = ceil($distance) * $costPerKm;

            return response()->json([
                [
                    'service' => strtoupper($request->courier) . '_CUSTOM',
                    'description' => ' ' . round($distance, 2) . ' km',
                    'cost' => $finalCost,
                ]
            ]);
        }
    }

    private function calculateHaversineDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Km

        $latFrom = deg2rad($lat1);
        $lngFrom = deg2rad($lng1);
        $latTo = deg2rad($lat2);
        $lngTo = deg2rad($lng2);

        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lngDelta / 2), 2)));
        return $earthRadius * $angle;
    }

    public function cancelOrder(Request $request)
    {
        $request->validate([
            'checkout_ids' => 'required|array',
            'checkout_ids.*' => 'integer|exists:checkouts,id',
        ]);

        $checkoutIds = $request->input('checkout_ids');
        $user = auth()->user();

        $orderIds = DB::table('order_checkouts')
            ->whereIn('checkout_id', $checkoutIds)
            ->pluck('order_id')
            ->unique();

        $orders = Order::with('checkouts')->whereIn('id', $orderIds)->get();

        DB::beginTransaction();

        try {
            foreach ($orders as $order) {
                foreach ($order->checkouts as $checkout) {
                    $variant = SubVariant::where('product_id', $checkout->product_id)
                        ->first();

                    if ($variant) {
                        $variant->increment('stock', $checkout->quantity);
                    }

                    $product = Product::find($checkout->product_id);
                    if ($product) {
                        $product->decrement('sales', $checkout->quantity);
                    }

                    if ($checkout->voucher_code) {
                        $voucher = Voucher::where('code', $checkout->voucher_code)->first();
                        if ($voucher) {
                            UserVoucher::where('user_id', $user->id)
                                ->where('voucher_id', $voucher->id)
                                ->delete();

                            $voucher->increment('usage_limit');
                        }
                    }
                }
            }

            Order::whereIn('id', $orderIds)->delete();
            Checkout::whereIn('id', $checkoutIds)->delete();

            DB::commit();
            return response()->json(['message' => 'Order dibatalkan dan data berhasil di-rollback.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal membatalkan order: ' . $e->getMessage()], 500);
        }
    }
}
