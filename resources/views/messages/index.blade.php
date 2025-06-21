@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Mesaje</h1>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($conversations as $conversation)
            @php
                $otherUser = $conversation->sender_id === auth()->id() ? $conversation->receiver : $conversation->sender;
            @endphp
            <a href="{{ route('messages.show', $otherUser) }}" class="block p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-4">
                    <img class="h-12 w-12 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($otherUser->name) }}&color=7C3AED&background=EDE9FE" alt="{{ $otherUser->name }}">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900 truncate">{{ $otherUser->name }}</h3>
                            <span class="text-sm text-gray-500">{{ $conversation->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate mt-1">
                            @if($conversation->sender_id === auth()->id())
                                Tu: {{ $conversation->message }}
                            @else
                                {{ $conversation->message }}
                            @endif
                        </p>
                    </div>
                    @if($conversation->sender_id !== auth()->id() && !$conversation->read_at)
                    <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                    @endif
                </div>
            </a>
            @empty
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Nu ai mesaje</h3>
                <p class="mt-2 text-gray-500">Începe să urmărești oameni pentru a putea să le trimiți mesaje!</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
