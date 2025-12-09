<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-gradient-to-r from-rose-100 via-peach-100 to-pink-100 border-b-2 border-rose-200 shadow-md">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-gradient-to-r from-rose-100 via-peach-100 to-pink-100 border-t-2 border-rose-200 mt-auto shadow-lg">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col gap-6">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="text-center md:text-left">
                                <p class="text-sm text-rose-700 font-medium">
                                    &copy; {{ date('Y') }} Кондитерская. Все права защищены.
                                </p>
                            </div>
                            <div class="flex gap-6">
                                <a href="{{ route('about') }}" class="text-sm text-rose-700 font-medium hover:text-rose-900 transition-colors">О нас</a>
                            </div>
                        </div>
                        <div class="border-t border-rose-300 pt-4">
                            <div class="flex flex-col md:flex-row justify-center items-center gap-4">
                                <p class="text-xs text-rose-600 font-medium">Руководство пользователя:</p>
                                <div class="flex flex-wrap justify-center gap-4">
                                    @guest
                                        <a href="{{ route('user-guide.guest') }}" class="text-xs text-rose-700 font-medium hover:text-rose-900 transition-colors underline">Для гостей</a>
                                    @endguest
                                    @auth
                                        @if(Auth::user()->role == 1)
                                            <a href="{{ route('user-guide.user') }}" class="text-xs text-rose-700 font-medium hover:text-rose-900 transition-colors underline">Для пользователей</a>
                                        @elseif(Auth::user()->role == 2)
                                            <a href="{{ route('user-guide.admin') }}" class="text-xs text-rose-700 font-medium hover:text-rose-900 transition-colors underline">Для администраторов</a>
                                        @elseif(Auth::user()->role == 3)
                                            <a href="{{ route('user-guide.manager') }}" class="text-xs text-rose-700 font-medium hover:text-rose-900 transition-colors underline">Для менеджеров</a>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
