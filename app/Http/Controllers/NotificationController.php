<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Notifications\CheckoutNotification;
use App\Notifications\NewOrderAdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $user = auth()->user();

        $notifications = $user->notifications()->orderBy('created_at', 'desc')->take(99)->get();

        return response()->json($notifications);
    }

    public function markAsRead(Request $request)
    {
        $user = auth()->user();
        $notificationIds = $request->input('notification_ids', []);

        if (!empty($notificationIds)) {
            $user->unreadNotifications()->whereIn('id', $notificationIds)->update(['read_at' => now()]);
        }

        return response()->json(['status' => 'success']);
    }

    public function showAllNotifications()
    {
        $user = auth()->user();

        $notifications = $user->notifications()->orderBy('created_at', 'desc')->get();

        return view('notifications.show', compact('notifications'));
    }
}
