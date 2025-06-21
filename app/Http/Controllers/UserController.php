<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $users = collect();
        
        if ($query) {
            $users = User::where('id', '!=', auth()->id())
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('username', 'LIKE', "%{$query}%");
                })
                ->limit(10)
                ->get();
        }
        
        return view('users.search', compact('users', 'query'));
    }

    public function suggestions()
    {
        // Get users that current user is not following or has pending requests
        $followingAndPendingIds = auth()->user()->following()
            ->whereIn('status', ['accepted', 'pending'])
            ->pluck('following_id');
        
        $suggestions = User::where('id', '!=', auth()->id())
            ->whereNotIn('id', $followingAndPendingIds)
            ->inRandomOrder()
            ->limit(5)
            ->get();
            
        return view('users.suggestions', compact('suggestions'));
    }

    public function show(User $user)
    {
        $followStatus = null;
        $canViewPosts = false;

        if (auth()->id() === $user->id) {
            // Own profile - can see everything
            $canViewPosts = true;
        } else {
            // Check follow status
            $follow = auth()->user()->following()
                ->where('following_id', $user->id)
                ->first();
            $followStatus = $follow ? $follow->status : null;
            
            // Can view posts if following is accepted
            $canViewPosts = $followStatus === 'accepted';
        }

        // Get posts only if user can view them
        $posts = collect();
        if ($canViewPosts) {
            $posts = $user->posts()->latest()->paginate(12);
        }
        
        return view('users.profile', compact('user', 'posts', 'followStatus', 'canViewPosts'));
    }
}
