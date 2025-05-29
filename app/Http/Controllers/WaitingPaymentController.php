<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class WaitingPaymentController extends Controller
{
    public function pending()
    {
        $pendingOrders = Order::where('payment_status', 'pending')
                            ->orderBy('order_date', 'desc')
                            ->paginate(10);

        return view('pendingpayment', compact('pendingOrders'));
    }
}
