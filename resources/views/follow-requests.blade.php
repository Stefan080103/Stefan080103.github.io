@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Cereri de urmărire</h1>
            <p class="text-gray-600 mt-1">Gestionează cererile de urmărire primite</p>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($requests as $request)
            <div class="p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('users.profile', $request->follower) }}">
                        <img class="h-12 w-12 rounded-full object-cover" 
                             src="{{ $request->follower->avatar ? Storage::url($request->follower->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($request->follower->name) . '&color=7C3AED&background=EDE9FE' }}" 
                             alt="{{ $request->follower->name }}">
                    </a>
                    <div>
                        <a href="{{ route('users.profile', $request->follower) }}" class="block">
                            <h3 class="font-semibold text-gray-900 hover:text-purple-600">{{ $request->follower->name }}</h3>
                            <p class="text-sm text-gray-500">{{ '@' . $request->follower->username }}</p>
                            @if($request->follower->bio)
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($request->follower->bio, 60) }}</p>
                            @endif
                        </a>
                        <p class="text-xs text-gray-400 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <form action="{{ route('follow.accept', $request) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                            Acceptă
                        </button>
                    </form>
                    
                    <form action="{{ route('follow.reject', $request) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors">
                            Respinge
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Nu ai cereri de urmărire</h3>
                <p class="mt-2 text-gray-500">Când cineva îți va trimite o cerere de urmărire, o vei vedea aici.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
