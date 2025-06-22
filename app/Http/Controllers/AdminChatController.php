<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminChatController extends Controller
{
    public function index()
    {
        try {
            // Ambil semua user kecuali admin yang sedang login
            $users = User::where('id', '!=', auth()->id())
                ->orderBy('name', 'asc')
                ->get();

            return view('components.adminChat', compact('users'));
        } catch (\Exception $e) {
            Log::error('Error in AdminChatController@index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat chat');
        }
    }

    public function getMessages($userId)
    {
        try {
            $adminId = auth()->id();

            // Validasi user exists
            $user = User::findOrFail($userId);

            $messages = Chat::where(function ($q) use ($userId, $adminId) {
                $q->where('from_user_id', $adminId)->where('to_user_id', $userId);
            })
                ->orWhere(function ($q) use ($userId, $adminId) {
                    $q->where('from_user_id', $userId)->where('to_user_id', $adminId);
                })
                ->orderBy('created_at', 'asc')
                ->select('id', 'from_user_id', 'to_user_id', 'message', 'created_at')
                ->get();

            // Mark messages as read
            Chat::where('from_user_id', $userId)
                ->where('to_user_id', $adminId)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json($messages);
        } catch (\Exception $e) {
            Log::error('Error in AdminChatController@getMessages: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat pesan'], 500);
        }
    }

    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000',
                'to_user_id' => 'required|exists:users,id',
            ]);

            $chat = Chat::create([
                'from_user_id' => auth()->id(),
                'to_user_id' => $request->to_user_id,
                'message' => trim($request->message),
                'is_read' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim',
                'data' => $chat,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            Log::error('Error in AdminChatController@sendMessage: ' . $e->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal mengirim pesan',
                ],
                500,
            );
        }
    }
}
