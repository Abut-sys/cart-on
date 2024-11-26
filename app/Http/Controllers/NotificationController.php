<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $user = auth()->user();
        return response()->json($user->notifications()->get());
    }

    public function markAsRead(Request $request)
    {
        $user = auth()->user();
        $notificationIds = $request->input('notification_ids');

        // Menandai notifikasi sebagai dibaca menggunakan method Laravel
        $user->notifications()->whereIn('id', $notificationIds)->update(['read_at' => now()]);

        return response()->json(['status' => 'success']);
    }

    public function showAllNotifications()
    {
        $notifications = auth()->user()->notifications; // Ambil semua notifikasi

        $unreadNotifications = $notifications->whereNull('read_at'); // Belum dibaca
        $readNotifications = $notifications->whereNotNull('read_at'); // Sudah dibaca

        return view('notifications.show', compact('unreadNotifications', 'readNotifications'));
    }
}
