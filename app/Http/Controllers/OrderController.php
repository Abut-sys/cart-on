<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusUpdatedNotification;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

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

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => [new EnumValue(OrderStatusEnum::class, false)],
            'payment_status' => [new EnumValue(PaymentStatusEnum::class, false)],
        ]);

        $oldOrderStatus = $order->order_status;
        $oldPaymentStatus = $order->payment_status;

        $order->update($validated);

        if ($order->user) {
            $order->user->notify(new OrderStatusUpdatedNotification($order, $oldOrderStatus, $oldPaymentStatus));
        }

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new OrderStatusUpdatedNotification($order, $oldOrderStatus, $oldPaymentStatus));

        return back()->with('success', 'Status berhasil diperbarui.');
    }
}
