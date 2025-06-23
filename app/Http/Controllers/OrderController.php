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
    public function index(Request $request)
    {
        $searchable = ['id', 'unique_order_id', 'order_date', 'amount', 'payment_status', 'order_status', 'tracking_number'];

        $query = Order::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request, $searchable) {
                foreach ($searchable as $column) {
                    $q->orWhere($column, 'like', '%' . $request->search . '%');
                }
            });
        }

        $sortColumn = $request->input('sort_column', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        if (in_array($sortColumn, $searchable) && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy($sortColumn, $sortDirection);
        }

        $orders = $query->paginate(10)->withQueryString();

        $orderCounts = Order::selectRaw('unique_order_id, COUNT(*) as total')
            ->groupBy('unique_order_id')
            ->pluck('total', 'unique_order_id');

        return view('orders.index', compact('orders', 'orderCounts'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => [new EnumValue(OrderStatusEnum::class, false)],
            'payment_status' => [new EnumValue(PaymentStatusEnum::class, false)],
            'tracking_number' => 'nullable|string|max:50',
        ]);

        if ($validated['order_status'] === 'shipped' && empty($validated['tracking_number'])) {
            return back()->withErrors(['tracking_number' => 'Tracking number is required when the order is shipped.']);
        }

        $oldOrderStatus = $order->order_status;
        $oldPaymentStatus = $order->payment_status;

        $order->order_status = $validated['order_status'];
        $order->payment_status = $validated['payment_status'];

        if ($validated['order_status'] === 'shipped') {
            $order->tracking_number = $validated['tracking_number'];
        }

        $order->save();

        if ($order->user) {
            $order->user->notify(new OrderStatusUpdatedNotification($order, $oldOrderStatus, $oldPaymentStatus));
        }

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new OrderStatusUpdatedNotification($order, $oldOrderStatus, $oldPaymentStatus));

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:50',
        ]);

        $order->tracking_number = $request->tracking_number;
        $order->save();

        return back()->with('success', 'Nomor resi berhasil diperbarui.');
    }
}
