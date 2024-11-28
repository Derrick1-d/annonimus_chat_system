<?php

// app/Http/Controllers/StudentDashboardController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chat; // Assuming you have a Chat model
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function searchLecturers(Request $request)
    {
        $query = $request->input('query');

        // Assuming 'role' field is used to differentiate between students and lecturers
        $lecturers = User::where('role', 'lecturer')
                         ->where('name', 'like', "%{$query}%")
                         ->get();

        return response()->json($lecturers);
    }
    public function index()
    {
        // Get all lecturers
        $lecturers = User::where('role', 'lecturer')->get();

        return view('student.dashboard', compact('lecturers'));
    }

    public function initiateChat($lecturerId)
    {
        $studentId = Auth::id();
        $lecturer = User::findOrFail($lecturerId);

        // Fetch the chat history between the student and the lecturer
        $chats = Chat::where(function ($query) use ($studentId, $lecturerId) {
            $query->where('sender_id', $studentId)
                  ->where('receiver_id', $lecturerId);
        })->orWhere(function ($query) use ($studentId, $lecturerId) {
            $query->where('sender_id', $lecturerId)
                  ->where('receiver_id', $studentId);
        })->get();

        return view('student.chat', compact('lecturer', 'chats'));
    }

    // Show the post page
    public function showPosts()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('student.posts', compact('posts'));
    }

    // Store a new post
    public function storePost(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Post::create([
            'student_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->route('student.posts')->with('success', 'Post created successfully!');
    }
}



