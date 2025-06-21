@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Caută utilizatori</h1>
            
            <!-- Search Form -->
            <form action="{{ route('users.search') }}" method="GET" class="mb-6">
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <input type="text" name="q" value="{{ $query }}" 
                               placeholder="Caută după nume sau username..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <button type="submit" 
                            class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                        Caută
                    </button>
                </div>
            </form>
        </div>

        <!-- Search Results -->
        <div class="divide-y divide-gray-200">
            @forelse($users as $user)
            <div class="p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('users.profile', $user) }}">
                        <img class="h-12 w-12 rounded-full object-cover" 
                             src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7C3AED&background=EDE9FE' }}" 
                             alt="{{ $user->name }}">
                    </a>
                    <div>
                        <a href="{{ route('users.profile', $user) }}" class="block">
                            <h3 class="font-semibold text-gray-900 hover:text-purple-600">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ '@' . $user->username }}</p>
                            @if($user->bio)
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($user->bio, 50) }}</p>
                            @endif
                        </a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    @php
                        $followStatus = auth()->user()->following()->where('following_id', $user->id)->first();
                    @endphp
                    
                    @if($followStatus && $followStatus->status === 'accepted')
                        <form action="{{ route('unfollow', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors">
                                Urmărești
                            </button>
                        </form>
                    @elseif($followStatus && $followStatus->status === 'pending')
                        <form action="{{ route('follow.cancel', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg text-sm font-medium hover:bg-yellow-200 transition-colors">
                                Cerere trimisă
                            </button>
                        </form>
                    @else
                        <form action="{{ route('follow', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                Urmărește
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                @if($query)
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Nu am găsit utilizatori</h3>
                    <p class="mt-2 text-gray-500">Încearcă să cauți cu alți termeni.</p>
                @else
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Caută utilizatori</h3>
                    <p class="mt-2 text-gray-500">Introdu un nume sau username pentru a căuta utilizatori.</p>
                @endif
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
