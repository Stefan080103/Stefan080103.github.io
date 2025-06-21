<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Posts
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
    
    // Users
    Route::get('/search', [UserController::class, 'search'])->name('users.search');
    Route::get('/suggestions', [UserController::class, 'suggestions'])->name('users.suggestions');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.profile');
    
    // Follow system
    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('follow');
    Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollow');
    Route::delete('/cancel-request/{user}', [FollowController::class, 'cancelRequest'])->name('follow.cancel');
    Route::post('/follow-requests/{follow}/accept', [FollowController::class, 'acceptRequest'])->name('follow.accept');
    Route::delete('/follow-requests/{follow}/reject', [FollowController::class, 'rejectRequest'])->name('follow.reject');
    Route::get('/follow-requests', [FollowController::class, 'requests'])->name('follow.requests');
    
    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
