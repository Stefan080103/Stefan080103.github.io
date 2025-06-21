@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Editează Profilul</h1>
        </div>

        @if (session('status'))
            <div class="p-4 bg-green-50 border-l-4 border-green-400">
                <p class="text-green-700">{{ session('status') }}</p>
            </div>
        @endif

        <div class="p-6">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Avatar -->
                <div class="flex items-center space-x-6">
                    <div class="shrink-0">
                        <img class="h-20 w-20 object-cover rounded-full" 
                             src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7C3AED&background=EDE9FE' }}" 
                             alt="{{ $user->name }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Schimbă avatarul</label>
                        <input type="file" name="avatar" accept="image/*" 
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                        @error('avatar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nume complet</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Nume utilizator</label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700">Biografie</label>
                    <textarea name="bio" id="bio" rows="3" 
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                              placeholder="Spune-ne ceva despre tine...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-4">
                    <button type="submit" 
                            class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-full text-sm font-medium hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                        Salvează modificările
                    </button>
                    
                    <a href="{{ route('home') }}" 
                       class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                        Anulează
                    </a>
                </div>
            </form>
        </div>

        <!-- Delete Account Section -->
        <div class="border-t border-gray-200 p-6">
            <h3 class="text-lg font-medium text-red-900 mb-4">Șterge contul</h3>
            <p class="text-sm text-gray-600 mb-4">
                Odată ce contul tău este șters, toate resursele și datele vor fi șterse permanent.
            </p>
            
            <form action="{{ route('profile.destroy') }}" method="POST" 
                  onsubmit="return confirm('Ești sigur că vrei să ștergi contul? Această acțiune nu poate fi anulată.')">
                @csrf
                @method('DELETE')
                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Confirmă cu parola</label>
                    <input type="password" name="password" id="password" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" 
                        class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700 transition-colors">
                    Șterge contul
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
