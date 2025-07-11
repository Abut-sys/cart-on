<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class AdminChatController extends Controller
{
    public function index()
    {
        // Get all users except admin
        $users = User::where('role', 'user')
                    ->orderBy('name')
                    ->get();

        return view('components.adminChat', compact('users'));
    }

    public function getMessages($userId)
    {
        try {
            // Validate that the user exists and is not an admin
            $user = User::where('id', $userId)
                       ->where('role', 'user')
                       ->first();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Get messages between admin and user
            $messages = Chat::where(function ($query) use ($userId) {
                $query->where('from_user_id', Auth::id())
                      ->where('to_user_id', $userId);
            })->orWhere(function ($query) use ($userId) {
                $query->where('from_user_id', $userId)
                      ->where('to_user_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

            return response()->json($messages);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load messages'], 500);
        }
    }

    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'to_user_id' => 'required|integer|exists:users,id',
                'message' => 'required|string|max:1000',
            ]);

            // Validate that the recipient is a user (not admin)
            $recipient = User::where('id', $request->to_user_id)
                           ->where('role', 'user')
                           ->first();

            if (!$recipient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid recipient'
                ], 400);
            }

            // Create the message
            $chatMessage = Chat::create([
                'from_user_id' => Auth::id(),
                'to_user_id' => $request->to_user_id,
                'message' => $request->message,
            ]);

            // Load the message with user relationship
            $chatMessage->load(['fromUser', 'toUser']);

            // Broadcast the message to both admin and user
            broadcast(new MessageSent($chatMessage))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => $chatMessage
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message'
            ], 500);
        }
    }
}
