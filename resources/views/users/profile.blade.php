@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Profile Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center space-x-6">
            <img class="h-24 w-24 rounded-full object-cover" 
                 src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7C3AED&background=EDE9FE' }}" 
                 alt="{{ $user->name }}">
            
            <div class="flex-1">
                <div class="flex items-center space-x-4 mb-4">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    
                    @if(auth()->id() !== $user->id)
                        @if($followStatus === 'accepted')
                            <div class="flex space-x-2">
                                <form action="{{ route('unfollow', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors">
                                        Urmărești
                                    </button>
                                </form>
                                
                                <a href="{{ route('messages.show', $user) }}" 
                                   class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                    Mesaj
                                </a>
                            </div>
                        @elseif($followStatus === 'pending')
                            <form action="{{ route('follow.cancel', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg text-sm font-medium hover:bg-yellow-200 transition-colors">
                                    Anulează cererea
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
                    @else
                        <a href="{{ route('profile.edit') }}" 
                           class="bg-gray-100 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                            Editează profilul
                        </a>
                    @endif
                </div>
                
                <p class="text-gray-600 mb-2">{{ '@' . $user->username }}</p>
                
                @if($user->bio)
                    <p class="text-gray-900 mb-4">{{ $user->bio }}</p>
                @endif
                
                <div class="flex space-x-6 text-sm">
                    <span><strong>{{ $user->posts_count }}</strong> postări</span>
                    <span><strong>{{ $user->followers_count }}</strong> urmăritori</span>
                    <span><strong>{{ $user->following_count }}</strong> urmăriri</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Posts Grid -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Postări</h2>
        </div>
        
        @if($canViewPosts)
            @if($posts->count() > 0)
                <div class="grid grid-cols-3 gap-1">
                    @foreach($posts as $post)
                    <div class="aspect-square bg-gray-100">
                        <img src="{{ Storage::url($post->image_path) }}" 
                             alt="Post" 
                             class="w-full h-full object-cover hover:opacity-75 transition-opacity cursor-pointer">
                    </div>
                    @endforeach
                </div>
                
                <div class="p-6">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Nu există postări</h3>
                    <p class="mt-2 text-gray-500">
                        @if(auth()->id() === $user->id)
                            Când vei posta fotografii, acestea vor apărea aici.
                        @else
                            {{ $user->name }} nu a postat încă nimic.
                        @endif
                    </p>
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Cont privat</h3>
                <p class="mt-2 text-gray-500">
                    Urmărește pe {{ $user->name }} pentru a vedea postările.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
