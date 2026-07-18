<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Readoo!') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen flex flex-col bg-base font-sans text-gray-900 antialiased">
        <!-- ヘッダー（ブランドサブカラーの青） -->
        <header class="w-full bg-secondary">
            <nav class="flex items-center justify-between max-w-7xl mx-auto px-6">
                {{-- ロゴ（クリックでMyPageへ） --}}
                <a href="{{ route('mypage')}}">
                    <x-application-logo class="block h-20 w-auto" />
                </a>
                {{-- 右側メニュー --}}
                <div class="flex items-center space-x-3 text-sm text-white">
                    <a href="{{ route('register') }}" class="hover:underline font-extrabold text-xl">Register</a>
                    <span class="opacity-60 font-extrabold text-xl">|</span>
                    <a href="{{ route('login') }}" class="hover:underline font-extrabold text-xl">Log in</a>
                </div>
            </nav>
        </header>

        <!-- メイン（login / register などがここに入る） -->
        <main class="flex-1 flex flex-col items-center px-6 py-16">
            {{ $slot }}
        </main>

        <!-- フッター -->
        <footer class="w-full px-6 py-4 bg-secondary text-center text-sm text-white">
            &copy; {{ date('Y') }} Readoo! All rights reserved.
        </footer>
    </body>
</html>
