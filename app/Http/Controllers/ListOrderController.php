<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use Illuminate\Http\Request;

class ListOrderController extends Controller
{
    public function history(Request $request)
    {
        $validStatuses = ['pending', 'shipped', 'delivered', 'canceled', null];
        $selectedStatus = $request->validate(['status' => 'nullable|in:' . implode(',', $validStatuses)])['status'] ?? null;

        $user = auth()->user();

        $checkouts = Checkout::with([
            'orders' => function ($query) use ($selectedStatus) {
                $query
                    ->when($selectedStatus, function ($q) use ($selectedStatus) {
                        $q->where('order_status', $selectedStatus);
                    })
                    ->with([
                        'product' => function ($query) {
                            $query->select('id', 'name', 'image');
                        },
                    ])
                    ->select('id', 'checkout_id', 'product_id', 'quantity', 'price', 'order_status');
            },
        ])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query());

        return view('orders_history', compact('checkouts'));
    }
}
