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
    public function pending()
    {
        $user = Auth::user();

        Order::whereHas('checkouts', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('payment_status', PaymentStatusEnum::Pending)
            ->where('order_date', '<', now()->subHours(24))
            ->update([
                'payment_status' => PaymentStatusEnum::Failed,
                'order_status' => OrderStatusEnum::Canceled,
            ]);

        $orders = Order::whereHas('checkouts', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereIn('payment_status', ['pending', 'failed'])
            ->with(['checkouts' => fn($q) => $q->with(['product.images'])])
            ->orderBy('order_date', 'desc')
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
            $order->update([
                'payment_status' =>  PaymentStatusEnum::Failed,
                'order_status' => OrderStatusEnum::Canceled,
            ]);

            $user->notify(new OrderCanceledNotification($order, $user));

            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new OrderCanceledNotification($order, $user));
        });

        return redirect()->route('orders.pending')->with('success', 'Order has been cancelled successfully.');
    }

    public function triggerPayment(Order $order, PaymentService $paymentService)
    {
        $user = auth()->user();

        if (!$order->user || $order->user->id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $items = [[
                'id' => $order->id,
                'price' => (int) $order->amount,
                'quantity' => 1,
                'name' => 'Pembayaran Order #' . $order->unique_order_id,
            ]];

            $snapToken = $paymentService->generateSnapTokenForOrder(
                $order->unique_order_id,
                (int) $order->amount,
                $user,
                $items
            );

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Throwable $e) {
            Log::error('Midtrans trigger error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Gagal memproses pembayaran.'], 500);
        }
    }
}
