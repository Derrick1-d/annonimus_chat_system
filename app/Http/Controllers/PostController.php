<?php

// app/Http/Controllers/PostController.php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['student', 'comments.student', 'likes'])->latest()->get();
        return view('student.posts', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Post::create([
            'student_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->route('student.posts.index')->with('success', 'Post created successfully!');
    }

    public function likePost($id)
{
    $post = Post::findOrFail($id);
    $studentId = auth()->id();

    // Check if the student has already liked the post
    $liked = $post->likes->where('student_id', $studentId)->count();

    if ($liked) {
        // Unlike the post
        $post->likes()->where('student_id', $studentId)->delete();
    } else {
        // Like the post
        $post->likes()->create(['student_id' => $studentId]);
    }

    // Return the updated like count and status
    return response()->json([
        'success' => true,
        'liked' => !$liked,
        'likes_count' => $post->likes()->count()
    ]);
}

public function comment(Request $request, $postId)
{
    $request->validate(['comment' => 'required|string|max:255']);

    $post = Post::findOrFail($postId);
    $post->comments()->create([
        'content' => $request->comment,
        'student_id' => Auth::id(),
    ]);

    return redirect()->back()->with('success', 'Comment added successfully!');
}

}
