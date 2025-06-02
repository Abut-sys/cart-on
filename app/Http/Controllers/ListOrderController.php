<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                }
            ])
            ->orderByDesc('order_date')
            ->paginate(5)
            ->appends($request->query());

        return view('orders_history', compact('orders'));
    }
}
