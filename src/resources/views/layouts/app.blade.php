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
    @php
        $bodyBg = match($theme){
            'book'      => 'bg-book/10',
            'knowledge' => 'bg-knowledge/10',
            'action'   => 'bg-action/10',
            default     => 'bg-base',
        };
    @endphp
    <body class="min-h-screen flex flex-col {{ $bodyBg }} font-sans text-gray-900 antialiased">
        {{-- 上部ナビ --}}
        @include('layouts.navigation')

        {{-- メイン（各画面がここに入る） --}}
        <main class="flex-1">
            {{ $slot }}
        </main>

        {{-- フッター --}}
        <footer class="w-full px-6 py-4 bg-secondary text-center text-sm text-white">
            &copy; {{date('Y')}} Readoo! All rights reserved.
        </footer>
    </body>
</html>
