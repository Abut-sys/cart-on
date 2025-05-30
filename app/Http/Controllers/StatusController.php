<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Enums\PaymentStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Notifications\OrderPaidNotification;
use App\Notifications\AdminOrderPaidNotification;

class StatusController extends Controller
{
    public function updatePaymentStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'payment_status' => 'required|string',
        ]);

        $order = Order::where('unique_order_id', $request->order_id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $paidStatuses = ['paid', 'settlement', 'capture', 'success'];

        if (in_array(strtolower($request->payment_status), $paidStatuses)) {
            $order->payment_status = PaymentStatusEnum::Completed;
            $order->order_status = OrderStatusEnum::Pending;
            $order->save();

            if ($order->user) {
                $order->user->notify(new OrderPaidNotification($order));
            }

            Notification::send(
                User::where('role', 'admin')->get(),
                new AdminOrderPaidNotification($order, $order->user)
            );

            return response()->json(['message' => 'Order status updated successfully.']);
        }

        return response()->json(['message' => 'Payment status not recognized or failed.'], 400);
    }
}
