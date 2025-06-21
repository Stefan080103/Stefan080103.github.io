<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Nu te poți urmări pe tine însuți!');
        }

        $existingFollow = Follow::where('follower_id', auth()->id())
            ->where('following_id', $user->id)
            ->first();

        if ($existingFollow) {
            return back()->with('error', 'Cererea de urmărire a fost deja trimisă!');
        }

        // Create follow request with pending status
        Follow::create([
            'follower_id' => auth()->id(),
            'following_id' => $user->id,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Cererea de urmărire a fost trimisă către ' . $user->name . '!');
    }

    public function unfollow(User $user)
    {
        $follow = Follow::where('follower_id', auth()->id())
            ->where('following_id', $user->id)
            ->first();

        if ($follow) {
            $follow->delete();
            return back()->with('success', 'Nu mai urmărești pe ' . $user->name . '!');
        }

        return back()->with('error', 'Nu urmărești acest utilizator!');
    }

    public function acceptRequest(Follow $follow)
    {
        if ($follow->following_id !== auth()->id()) {
            return back()->with('error', 'Acțiune neautorizată!');
        }

        $follow->update(['status' => 'accepted']);

        return back()->with('success', 'Cererea de urmărire de la ' . $follow->follower->name . ' a fost acceptată!');
    }

    public function rejectRequest(Follow $follow)
    {
        if ($follow->following_id !== auth()->id()) {
            return back()->with('error', 'Acțiune neautorizată!');
        }

        $follow->delete();

        return back()->with('success', 'Cererea de urmărire de la ' . $follow->follower->name . ' a fost respinsă!');
    }

    public function cancelRequest(User $user)
    {
        $follow = Follow::where('follower_id', auth()->id())
            ->where('following_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($follow) {
            $follow->delete();
            return back()->with('success', 'Cererea de urmărire a fost anulată!');
        }

        return back()->with('error', 'Nu există cerere de urmărire pentru acest utilizator!');
    }

    public function requests()
    {
        $requests = Follow::where('following_id', auth()->id())
            ->where('status', 'pending')
            ->with('follower')
            ->latest()
            ->get();

        return view('follow-requests', compact('requests'));
    }
}
