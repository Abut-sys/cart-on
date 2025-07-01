<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        // Get admin user (assuming role = 'admin')
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            // If no admin found, create a default admin or handle error
            return redirect()->back()->with('error', 'No admin available for chat');
        }

        // Get messages between current user and admin
        $messages = Chat::where(function ($query) use ($admin) {
            $query->where('from_user_id', Auth::id())
                  ->where('to_user_id', $admin->id);
        })->orWhere(function ($query) use ($admin) {
            $query->where('from_user_id', $admin->id)
                  ->where('to_user_id', Auth::id());
        })
        ->orderBy('created_at', 'asc')
        ->get();

        return view('components.liveChat', compact('messages', 'admin'));
    }

    public function send(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000',
                'to_user_id' => 'required|integer|exists:users,id',
            ]);

            // Validate that the recipient is an admin
            $recipient = User::where('id', $request->to_user_id)
                           ->where('role', 'admin')
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

            // Load the message with user relationships
            $chatMessage->load(['fromUser', 'toUser']);

            // Broadcast the message
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

    public function getMessages()
    {
        try {
            // Get admin user
            $admin = User::where('role', 'admin')->first();

            if (!$admin) {
                return response()->json(['error' => 'Admin not found'], 404);
            }

            // Get messages between current user and admin
            $messages = Chat::where(function ($query) use ($admin) {
                $query->where('from_user_id', Auth::id())
                      ->where('to_user_id', $admin->id);
            })->orWhere(function ($query) use ($admin) {
                $query->where('from_user_id', $admin->id)
                      ->where('to_user_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

            return response()->json($messages);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load messages'], 500);
        }
    }
}
