@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-[600px] flex flex-col">
        <!-- Chat Header -->
        <div class="p-4 border-b border-gray-200 flex items-center space-x-4">
            <a href="{{ route('messages.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <img class="h-10 w-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7C3AED&background=EDE9FE" alt="{{ $user->name }}">
            <div>
                <h2 class="font-semibold text-gray-900">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">Activ recent</p>
            </div>
        </div>

        <!-- Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            @foreach($messages as $message)
            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs lg:max-w-md">
                    @if($message->sender_id !== auth()->id())
                    <div class="flex items-end space-x-2">
                        <img class="h-6 w-6 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($message->sender->name) }}&color=7C3AED&background=EDE9FE" alt="{{ $message->sender->name }}">
                        <div class="bg-gray-100 rounded-2xl rounded-bl-md px-4 py-2">
                            <p class="text-gray-900">{{ $message->message }}</p>
                        </div>
                    </div>
                    @else
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-2xl rounded-br-md px-4 py-2">
                        <p>{{ $message->message }}</p>
                    </div>
                    @endif
                    <p class="text-xs text-gray-500 mt-1 {{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                        {{ $message->created_at->format('H:i') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Message Input -->
        <div class="p-4 border-t border-gray-200">
            <form action="{{ route('messages.store', $user) }}" method="POST" class="flex items-center space-x-4">
                @csrf
                <div class="flex-1">
                    <input type="text" name="message" placeholder="Scrie un mesaj..." class="w-full border border-gray-300 rounded-full px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                </div>
                <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-2 rounded-full hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
