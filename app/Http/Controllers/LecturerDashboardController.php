<?php
namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerDashboardController extends Controller
{
    public function index()
    {
        $lecturerId = Auth::id();

        // Get the list of students who have sent messages to the logged-in lecturer
        $students = User::whereHas('sentMessages', function ($query) use ($lecturerId) {
            $query->where('receiver_id', $lecturerId);
        })->distinct()->get();

        return view('lecturer.dashboard', compact('students'));
    }

    

    public function reply($studentId)
{
    $lecturerId = Auth::id();

    // Fetch the chat history between the lecturer and the specific student
    $chats = Chat::where(function ($query) use ($lecturerId, $studentId) {
        $query->where('sender_id', $lecturerId)
              ->where('receiver_id', $studentId);
    })->orWhere(function ($query) use ($lecturerId, $studentId) {
        $query->where('sender_id', $studentId)
              ->where('receiver_id', $lecturerId);
    })->get();

    // Get the student and lecturer details
    $student = User::findOrFail($studentId);
    $lecturer = Auth::user(); // Get the logged-in lecturer

    return view('lecturer.reply', compact('chats', 'student', 'lecturer'));
}
public function toggleIdentity(Request $request)
{
    $lecturer = Auth::user();

    // Check if settings exist, if not create default settings
    if (!$lecturer->settings) {
        $lecturer->settings()->create([
            'is_identity_visible' => false, // Default value
        ]);
    }

    // Toggle the visibility of the identity
    $lecturer->settings->is_identity_visible = !$lecturer->settings->is_identity_visible;
    $lecturer->settings->save();

    return back()->with('status', 'Identity visibility toggled successfully!');
}

}
