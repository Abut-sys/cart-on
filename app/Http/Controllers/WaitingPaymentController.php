<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WaitingPaymentController extends Controller
{
    /**
     * Menampilkan semua pesanan user yang masih menunggu pembayaran.
     */
    public function pending()
    {
        $user = Auth::user();

        $orders = Order::whereHas('checkouts', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereIn('payment_status', ['pending', 'failed']) 
            ->with('checkouts')
            ->orderBy('order_date', 'desc')
            ->paginate(10);

        return view('pendingpayment', compact('orders'));
    }

    /**
     * Membatalkan pesanan (jika milik user yang sedang login dan status masih pending).
     */
    public function cancel(Order $order)
    {
        $user = auth()->user();

        // Cek apakah order masih pending dan dimiliki oleh user login
        if (!$order->checkouts->contains(fn($checkout) => (int) $checkout->user_id === (int) $user->id)) {
            Log::warning('Unauthorized cancel attempt', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'checkout_user_ids' => $order->checkouts->pluck('user_id')->toArray(),
            ]);

            abort(403, 'Unauthorized action.');
        }

        // Jalankan pembatalan dalam transaksi database
        DB::transaction(function () use ($order, $user) {
            $order->update([
                'payment_status' => 'failed', // atau bisa 'cancelled' jika kamu pakai enum sendiri
                'order_status' => 'canceled',
            ]);

            Log::info('Order cancelled successfully.', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'timestamp' => now()->toDateTimeString(),
            ]);
        });

        return redirect()->route('orders.pending')->with('success', 'Order has been cancelled successfully.');
    }
}
