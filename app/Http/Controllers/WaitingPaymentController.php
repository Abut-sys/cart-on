<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderCanceledNotification;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class WaitingPaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function pending()
    {
        $user = Auth::user();

        $expiredOrders = Order::whereHas('checkouts', fn($q) => $q->where('user_id', $user->id))
            ->where('payment_status', PaymentStatusEnum::Pending)
            ->get();

        foreach ($expiredOrders as $order) {

            $statusData = $this->paymentService->getTransactionStatus($order->unique_order_id);
            $midtransStatus = strtolower($statusData->transaction_status ?? '');

            if (in_array($midtransStatus, ['settlement', 'capture', 'success'])) {
                $order->update([
                    'payment_status' => PaymentStatusEnum::Completed,
                    'order_status' => OrderStatusEnum::Pending,
                ]);
            } elseif ($midtransStatus === 'expire') {
                $order->update([
                    'payment_status' => PaymentStatusEnum::Failed,
                    'order_status' => OrderStatusEnum::Canceled,
                ]);
            }
        }

        $orders = Order::whereHas('checkouts', fn($q) => $q->where('user_id', $user->id))
            ->whereIn('payment_status', [PaymentStatusEnum::Pending, PaymentStatusEnum::Failed])
            ->with(['checkouts.product.images'])
            ->orderByDesc('order_date')
            ->paginate(10);

        return view('pendingpayment', compact('orders'));
    }

    public function cancel(Order $order)
    {
        $user = auth()->user();

        if (!$order->checkouts->contains(fn($checkout) => (int) $checkout->user_id === (int) $user->id)) {
            abort(403, 'Unauthorized action.');
        }

        DB::transaction(function () use ($order, $user) {
            $midtransStatus = strtolower($this->paymentService->getTransactionStatus($order->unique_order_id)->transaction_status ?? '');

            if ($midtransStatus !== 'cancel') {
                $this->paymentService->cancelTransaction($order->unique_order_id);
            }

            $order->update([
                'payment_status' => PaymentStatusEnum::Failed,
                'order_status' => OrderStatusEnum::Canceled,
            ]);

            $user->notify(new OrderCanceledNotification($order, $user));
            Notification::send(User::where('role', 'admin')->get(), new OrderCanceledNotification($order, $user));
        });

        return redirect()->route('orders.pending')->with('success', 'Order has been cancelled successfully.');
    }

    public function triggerPayment(Order $order)
    {
        $user = auth()->user();

        if (!$order->user || $order->user->id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $status = strtolower($this->paymentService->getTransactionStatus($order->unique_order_id)->transaction_status ?? '');

        if (in_array($status, ['settlement', 'capture', 'success'])) {
            if ($order->payment_status !== PaymentStatusEnum::Completed) {
                $order->update([
                    'payment_status' => PaymentStatusEnum::Completed,
                    'order_status' => OrderStatusEnum::Pending,
                ]);
            }

            return response()->json([
                'status' => 'paid',
                'message' => 'Order sudah dibayar.',
            ]);
        }

        $stillValid = $order->snap_token && $order->order_date && now()->diffInHours($order->order_date) < 24;

        if ($stillValid && $status === 'pending') {
            return response()->json([
                'status' => 'unpaid',
                'snap_token' => $order->snap_token,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Snap token tidak valid atau pembayaran sudah tidak bisa diproses.',
        ], 400);
    }

    public function checkAndSyncStatus(Order $order)
    {
        $midtransStatus = strtolower($this->paymentService->getTransactionStatus($order->unique_order_id)->transaction_status ?? '');

        match ($midtransStatus) {
            'settlement', 'capture', 'success' => $order->update([
                'payment_status' => PaymentStatusEnum::Completed,
                'order_status' => OrderStatusEnum::Pending,
            ]),
            'expire' => $order->update([
                'payment_status' => PaymentStatusEnum::Failed,
                'order_status' => OrderStatusEnum::Canceled,
            ]),
            default => null,
        };

        return response()->json([
            'payment_status' => $order->payment_status->value,
        ]);
    }
}
