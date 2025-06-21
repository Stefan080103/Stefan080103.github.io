<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get posts only from users that current user follows (accepted status) + own posts
        $followingIds = auth()->user()->following()
            ->where('status', 'accepted')
            ->pluck('following_id')
            ->push(auth()->id());

        $posts = Post::with(['user', 'likes', 'comments.user'])
            ->whereIn('user_id', $followingIds)
            ->latest()
            ->paginate(10);

        // Get suggested users (users not followed and not pending)
        $followingAndPendingIds = auth()->user()->following()
            ->whereIn('status', ['accepted', 'pending'])
            ->pluck('following_id');

        $suggestions = User::where('id', '!=', auth()->id())
            ->whereNotIn('id', $followingAndPendingIds)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        // Get pending follow requests count
        $pendingRequestsCount = Follow::where('following_id', auth()->id())
            ->where('status', 'pending')
            ->count();

        return view('home', compact('posts', 'suggestions', 'pendingRequestsCount'));
    }
}
