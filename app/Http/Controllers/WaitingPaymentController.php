<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class WaitingPaymentController extends Controller
{
    public function pending()
    {
        $user = auth()->user();

        $pendingOrders = Order::where('payment_status', 'pending')
            ->whereHas('checkouts', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('order_date', 'desc')
            ->paginate(10);

        return view('pendingpayment', compact('pendingOrders'));
    }
}
