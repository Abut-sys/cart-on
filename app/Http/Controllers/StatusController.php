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
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;

class StatusController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

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

        $transactionStatus = strtolower($request->payment_status);
        $paidStatuses = ['settlement', 'capture', 'success'];

        if (in_array($transactionStatus, $paidStatuses)) {
            try {
                $order->update([
                    'payment_status' => PaymentStatusEnum::Completed,
                    'order_status' => OrderStatusEnum::Pending,
                ]);

                if ($order->user) {
                    $order->user->notify(new OrderPaidNotification($order));
                }

                Notification::send(
                    User::where('role', 'admin')->get(),
                    new AdminOrderPaidNotification($order, $order->user)
                );

                return response()->json(['message' => 'Order status updated successfully.']);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Failed to update order status'], 500);
            }
        }

        return response()->json(['message' => 'Payment not completed.'], 400);
    }
}
