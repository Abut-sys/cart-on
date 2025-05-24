<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $users = User::where('role', 'user')->get();
            return view('costumers.index', compact('users'));
        }

        // Chat user hanya dengan admin
        $messages = Chat::where('user_id', Auth::id())->get();

        return view('home_user.home', compact('messages'));
    }

    public function fetchMessages($userId)
    {
        // Untuk admin: ambil chat dengan user tertentu
        // Untuk user: hanya ambil chat milik sendiri
        $query = Chat::query();

        if (Auth::user()->role === 'admin') {
            $query->where('user_id', $userId);
        } else {
            $query->where('user_id', Auth::id());
        }

        $messages = $query->orderBy('created_at')->get();

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        $chat = Chat::create([
            'user_id' => Auth::user()->role === 'admin' ? $request->user_id : Auth::id(),
            'message' => $request->message,
            'sender' => Auth::user()->role,
        ]);

        // Jika kamu sudah membuat Event MessageSent, bisa seperti ini:
        // broadcast(new MessageSent($chat))->toOthers();

        return response()->json($chat);
    }
}
