<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListOrderController extends Controller
{
    public function history(Request $request)
    {
        $validated = $request->validate([
            'payment_status' => 'nullable|in:pending,completed,failed',
            'order_status' => 'nullable|in:pending,shipped,delivered,canceled',
        ]);

        $user = auth()->user();

        $checkouts = Checkout::with([
            'orders' => function ($query) use ($validated) {
                $query
                    ->when(isset($validated['order_status']), function ($q) use ($validated) {
                        $q->where('order_status', $validated['order_status']);
                    })
                    ->when(isset($validated['payment_status']), function ($q) use ($validated) {
                        $q->where('payment_status', $validated['payment_status']);
                    })
                    ->with([
                        'product' => function ($query) {
                            $query->with([
                                'images' => function ($q) {
                                    $q->select('id', 'product_id', 'image_path');
                                },
                                'brand:id,name',
                                'subCategory:id,name',
                            ]);

                            // Jika Product menggunakan soft delete
                            if (method_exists(Product::class, 'bootSoftDeletes')) {
                                $query->withTrashed();
                            }
                        },
                    ]);
            },
        ])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query());

        return view('orders_history', compact('checkouts'));
    }
}
