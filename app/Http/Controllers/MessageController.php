<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        // Get conversations only with users that follow each other (accepted status)
        $conversations = Message::where(function($query) {
            $query->where('sender_id', auth()->id())
                  ->orWhere('receiver_id', auth()->id());
        })
        ->with(['sender', 'receiver'])
        ->get()
        ->groupBy(function ($message) {
            return $message->sender_id === auth()->id() 
                ? $message->receiver_id 
                : $message->sender_id;
        })
        ->map(function ($messages) {
            return $messages->sortByDesc('created_at')->first();
        })
        ->filter(function ($message) {
            // Only show conversations with users we can message
            $otherUserId = $message->sender_id === auth()->id() 
                ? $message->receiver_id 
                : $message->sender_id;
            $otherUser = User::find($otherUserId);
            return auth()->user()->canMessageWith($otherUser);
        });

        return view('messages.index', compact('conversations'));
    }

    public function show(User $user)
    {
        if (!auth()->user()->canMessageWith($user)) {
            return redirect()->route('messages.index')
                ->with('error', 'Poți trimite mesaje doar utilizatorilor care te urmăresc și pe care îi urmărești!');
        }

        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', auth()->id());
        })->orderBy('created_at')->get();

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $user)
    {
        if (!auth()->user()->canMessageWith($user)) {
            return back()->with('error', 'Poți trimite mesaje doar utilizatorilor care te urmăresc și pe care îi urmărești!');
        }

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'message' => $request->message
        ]);

        return back();
    }
}
