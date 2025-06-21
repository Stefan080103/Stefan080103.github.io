@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Feed -->
        <div class="lg:col-span-2">
            <!-- Create Post -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex items-center space-x-4 mb-4">
                        <img class="h-10 w-10 rounded-full object-cover" 
                             src="{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7C3AED&background=EDE9FE' }}" 
                             alt="{{ auth()->user()->name }}">
                        <div class="flex-1">
                            <textarea name="caption" placeholder="Ce gândești, {{ auth()->user()->name }}?" class="w-full border-0 resize-none focus:ring-0 text-gray-900 placeholder-gray-500" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <label for="image" class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 cursor-pointer">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Foto</span>
                                </label>
                                <input type="file" id="image" name="image" accept="image/*" class="hidden" required>
                            </div>
                            
                            <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-full text-sm font-medium hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                                Postează
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Posts Feed -->
            @foreach($posts as $post)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
                <!-- Post Header -->
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('users.profile', $post->user) }}">
                            <img class="h-10 w-10 rounded-full object-cover" 
                                 src="{{ $post->user->avatar ? Storage::url($post->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) . '&color=7C3AED&background=EDE9FE' }}" 
                                 alt="{{ $post->user->name }}">
                        </a>
                        <div>
                            <a href="{{ route('users.profile', $post->user) }}" class="font-semibold text-gray-900 hover:text-purple-600">{{ $post->user->name }}</a>
                            <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                </div>

                <!-- Post Image -->
                <div class="relative">
                    <img src="{{ Storage::url($post->image_path) }}" alt="Post image" class="w-full h-96 object-cover">
                </div>

                <!-- Post Actions -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-4">
                            <button onclick="toggleLike({{ $post->id }})" class="flex items-center space-x-1 text-gray-700 hover:text-red-500 transition-colors">
                                <svg id="heart-{{ $post->id }}" class="w-6 h-6 {{ $post->isLikedBy(auth()->user()) ? 'text-red-500 fill-current' : 'text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                            
                            <button class="flex items-center space-x-1 text-gray-700 hover:text-gray-900 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <button class="text-gray-700 hover:text-gray-900 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Likes Count -->
                    <p id="likes-count-{{ $post->id }}" class="font-semibold text-gray-900 mb-2">
                        @if($post->likes_count > 0)
                            {{ $post->likes_count }} {{ $post->likes_count == 1 ? 'apreciere' : 'aprecieri' }}
                        @endif
                    </p>

                    <!-- Caption -->
                    @if($post->caption)
                    <div class="mb-3">
                        <a href="{{ route('users.profile', $post->user) }}" class="font-semibold text-gray-900 hover:text-purple-600">{{ $post->user->name }}</a>
                        <span class="text-gray-900 ml-2">{{ $post->caption }}</span>
                    </div>
                    @endif

                    <!-- Comments -->
                    @if($post->comments_count > 0)
                    <button class="text-gray-500 text-sm mb-3 hover:text-gray-700">
                        Vezi toate cele {{ $post->comments_count }} comentarii
                    </button>
                    @endif

                    @foreach($post->comments->take(2) as $comment)
                    <div class="mb-2">
                        <a href="{{ route('users.profile', $comment->user) }}" class="font-semibold text-gray-900 hover:text-purple-600">{{ $comment->user->name }}</a>
                        <span class="text-gray-900 ml-2">{{ $comment->comment }}</span>
                    </div>
                    @endforeach

                    <!-- Add Comment -->
                    <form action="{{ route('posts.comment', $post) }}" method="POST" class="mt-3 border-t border-gray-100 pt-3">
                        @csrf
                        <div class="flex items-center space-x-3">
                            <img class="h-8 w-8 rounded-full object-cover" 
                                 src="{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7C3AED&background=EDE9FE' }}" 
                                 alt="{{ auth()->user()->name }}">
                            <input type="text" name="comment" placeholder="Adaugă un comentariu..." class="flex-1 border-0 focus:ring-0 text-sm text-gray-900 placeholder-gray-500" required>
                            <button type="submit" class="text-blue-600 font-semibold text-sm hover:text-blue-700">Postează</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Sugestii pentru tine</h3>
                    <a href="{{ route('users.search') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Vezi toate</a>
                </div>
                
                <div class="space-y-4">
                    @forelse($suggestions as $user)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('users.profile', $user) }}">
                                <img class="h-10 w-10 rounded-full object-cover" 
                                     src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7C3AED&background=EDE9FE' }}" 
                                     alt="{{ $user->name }}">
                            </a>
                            <div>
                                <a href="{{ route('users.profile', $user) }}" class="font-semibold text-gray-900 hover:text-purple-600 text-sm">{{ $user->name }}</a>
                                <p class="text-xs text-gray-500">{{ '@' . $user->username }}</p>
                            </div>
                        </div>
                        <form action="{{ route('follow', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-blue-600 font-semibold text-sm hover:text-blue-700">Urmărește</button>
                        </form>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">Nu există sugestii disponibile.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
