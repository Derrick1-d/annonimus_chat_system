<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LecturerDashboardController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Lecturer Routes
Route::middleware(['auth', 'role:lecturer'])->group(function () {
    Route::get('/lecturer/dashboard', [LecturerDashboardController::class, 'index'])->name('lecturer.dashboard');
    Route::post('/lecturer/toggle-identity', [LecturerDashboardController::class, 'toggleIdentity'])->name('lecturer.toggleIdentity');
    Route::get('/lecturer/reply/{student}', [LecturerDashboardController::class, 'reply'])->name('lecturer.reply');
    Route::get('/lecturer/messages', [ChatController::class, 'getMessages'])->name('lecturer.messages');
    Route::post('/chat/reply/{chat}', [ChatController::class, 'replyMessage'])->name('chat.reply');
    Route::post('/chat/toggle-identity', [ChatController::class, 'toggleIdentityVisibility'])->name('chat.toggleIdentity');
});

// Student Routes
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/student/chat/{lecturer}', [StudentDashboardController::class, 'initiateChat'])->name('student.chat');
    Route::get('/student/search', [StudentDashboardController::class, 'searchLecturers'])->name('student.search');
    Route::get('/chat/{lecturer}', [ChatController::class, 'initiateChat'])->name('chat.initiate');
    Route::post('/chat/send/{lecturer}', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/student/posts', [StudentDashboardController::class, 'showPosts'])->name('student.posts');
    // Route::post('/student/posts', [StudentDashboardController::class, 'storePost'])->name('student.posts.store');
    Route::get('/posts', [PostController::class, 'index'])->name('student.posts.index'); // Define this route
    Route::post('/posts', [PostController::class, 'store'])->name('student.posts.store');
    Route::post('/student/posts/{post}/like', [PostController::class, 'like'])->name('student.posts.like');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('student.posts.comment');
});
