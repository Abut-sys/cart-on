<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatController extends Controller
{
    // Tampilkan halaman chat & riwayat pesan user login
    public function index()
    {
        $userId = auth()->id();

        $messages = Chat::where('from_user_id', $userId)->orWhere('to_user_id', $userId)->orderBy('created_at')->get();

        return view('components.liveChat', compact('messages'));
    }

    // Simpan pesan ke database
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'to_user_id' => 'required|exists:users,id',
        ]);

        $chat = Chat::create([
            'from_user_id' => auth()->id(),
            'to_user_id' => $request->to_user_id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        // Trigger event broadcast jika ingin (opsional)
        broadcast(new \App\Events\MessageSent($chat))->toOthers();

        return response()->json(['message' => 'sent']);
    }

    public function getChatWithUser($userId)
    {
        $adminId = auth()->id();

        $messages = Chat::where(function ($query) use ($adminId, $userId) {
            $query->where('from_user_id', $adminId)->where('to_user_id', $userId);
        })
            ->orWhere(function ($query) use ($adminId, $userId) {
                $query->where('from_user_id', $userId)->where('to_user_id', $adminId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}
