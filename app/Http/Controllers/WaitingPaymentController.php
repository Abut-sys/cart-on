<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () use ($order) {
            $order->update([
                'payment_status' =>  PaymentStatusEnum::Failed,
                'order_status' => OrderStatusEnum::Canceled,
            ]);
        });

        return redirect()->route('orders.pending')->with('success', 'Order has been cancelled successfully.');
    }
}
