<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\LecturerSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Display all student messages for all lecturers
    public function getMessages()
    {
        // Get all student messages that have been sent to any lecturer
        $chats = Chat::where('is_anonymous', true)
                    ->orWhere('receiver_id', Auth::id())
                    ->get();

        return view('lecturer.messages', compact('chats'));
    }

    // Display the chat window for a student to chat with a lecturer
    public function initiateChat($lecturerId)
    {
        $lecturer = User::findOrFail($lecturerId);
        return view('chat.window', compact('lecturer'));
    }

    // Handle sending messages from a student to a lecturer
    public function sendMessage(Request $request, $lecturerId)
{
    $request->validate([
        'message' => 'required|string|max:1000',
    ]);

    $chat = Chat::create([
        'sender_id' => Auth::id(),
        'receiver_id' => $lecturerId,
        'message' => $request->message,
        'is_anonymous' => true,
    ]);

    return response()->json(['message' => 'Message sent successfully!']);
}


    // Handle lecturer's reply to a student's message
    public function replyMessage(Request $request, $chatId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $chat = Chat::findOrFail($chatId);

        Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $chat->sender_id, // Reply to the original sender
            'message' => $request->message,
            'is_anonymous' => !$request->has('is_identity_visible'), // Toggle identity visibility
        ]);

        return response()->json(['message' => 'Reply sent successfully!']);
    }

    // Toggle the lecturer's identity visibility for future replies
    public function toggleIdentityVisibility(Request $request)
    {
        $request->validate([
            'is_identity_visible' => 'boolean',
        ]);

        LecturerSetting::updateOrCreate(
            ['lecturer_id' => Auth::id()],
            ['is_identity_visible' => $request->boolean('is_identity_visible')]
        );

        return response()->json(['message' => 'Identity visibility updated!']);
    }
}
