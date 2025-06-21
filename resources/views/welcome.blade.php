<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fdf2f8',
                            100: '#fce7f3',
                            500: '#ec4899',
                            600: '#db2777',
                            700: '#be185d',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-4xl mx-auto text-center px-4">
            <div class="mb-8">
                <h1 class="text-6xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-4">
                    Texty
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    Conectează-te cu prietenii și împărtășește momentele tale speciale
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl p-8 shadow-lg">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Împărtășește Momentele</h3>
                                <p class="text-gray-600 text-sm">Postează fotografii și povești</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-8 shadow-lg">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Conectează-te</h3>
                                <p class="text-gray-600 text-sm">Urmărește prietenii și familia</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-8 shadow-lg">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Conversează</h3>
                                <p class="text-gray-600 text-sm">Trimite mesaje private</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    @auth
                        <a href="{{ route('home') }}" 
                           class="block w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white text-center py-3 px-6 rounded-full text-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                            Mergi la Feed
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="block w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white text-center py-3 px-6 rounded-full text-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                            Conectează-te
                        </a>
                        
                        <a href="{{ route('register') }}" 
                           class="block w-full bg-white text-gray-900 text-center py-3 px-6 rounded-full text-lg font-semibold border-2 border-gray-200 hover:border-gray-300 transition-all duration-200">
                            Creează cont
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</body>
</html>
