<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use App\Models\Checkout;
use App\Models\ReviewProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ListOrderController extends Controller
{
    public function history(Request $request)
    {
        $validated = $request->validate([
            'payment_status' => 'nullable|in:pending,completed,failed',
            'order_status' => 'nullable|in:pending,packaged,shipped,delivered,canceled',
        ]);

        $user = auth()->user();

        $orders = Order::whereHas('checkouts', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->when($validated['payment_status'] ?? null, function ($q) use ($validated) {
                $q->where('payment_status', $validated['payment_status']);
            })
            ->when($validated['order_status'] ?? null, function ($q) use ($validated) {
                $q->where('order_status', $validated['order_status']);
            })
            ->with([
                'checkouts' => function ($q) {
                    $q->with(['product.images', 'product.subCategory', 'product.brand', 'voucher']);
                },
            ])
            ->orderByDesc('order_date')
            ->paginate(5)
            ->appends($request->query());

        return view('orders_history', compact('orders'));
    }

    public function confirm($id)
    {
        $order = Order::with('checkouts')->findOrFail($id);

        // Ambil semua checkout dari order dan pastikan milik user login
        $isOwnedByUser = $order->checkouts->contains(function ($checkout) {
            return $checkout->user_id === auth()->id();
        });

        if (!$isOwnedByUser) {
            abort(403);
        }

        $order->is_confirmed = true;
        $order->save();

        return back()->with('msg', 'Pesanan berhasil dikonfirmasi.');
    }

    public function rate(Request $request, $orderId)
    {
        $productIds = $request->input('product_ids', []);
        $ratings = $request->input('ratings', []);
        $comments = $request->input('comments', []);

        foreach ($productIds as $productId) {
            ReviewProduct::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'order_id' => $orderId,
                'rating' => $ratings[$productId] ?? 0,
                'comment' => $comments[$productId] ?? null,
            ]);

            // Update rating dan rating_count di tabel produk
            $product = Product::find($productId);
            if ($product) {
                $newCount = $product->rating_count + 1;
                $newAverage = ($product->rating * $product->rating_count + ($ratings[$productId] ?? 0)) / $newCount;
                $product->update([
                    'rating' => round($newAverage, 1),
                    'rating_count' => $newCount,
                ]);
            }
        }

        return back()->with('msg', 'Terima kasih atas rating Anda!');
    }
}
