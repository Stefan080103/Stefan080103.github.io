<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Confirmă Parola</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="mx-auto h-12 w-12 flex items-center justify-center">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                        Texty
                    </h1>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Confirmă parola
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Aceasta este o zonă securizată a aplicației. Te rugăm să confirmi parola înainte de a continua.
                </p>
            </div>

            <form class="mt-8 space-y-6" action="{{ route('password.confirm') }}" method="POST">
                @csrf
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Parola</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" 
                           placeholder="Parola">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                        Confirmă
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
