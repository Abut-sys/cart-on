<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Paginate orders (10 orders per page)
        $orders = Order::paginate(10);

        // Retrieve order counts
        $orderCounts = Order::selectRaw('unique_order_id, COUNT(*) as total')->groupBy('unique_order_id')->pluck('total', 'unique_order_id');

        return view('orders.index', compact('orders', 'orderCounts'));
    }
}
