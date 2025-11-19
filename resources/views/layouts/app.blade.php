<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        
        <div class="max-w-md mx-auto bg-white min-h-screen shadow-2xl relative overflow-hidden">

            @if (isset($header))
                <header class="bg-white shadow sticky top-0 z-10">
                    <div class="max-w-7xl mx-auto py-4 px-4 flex justify-between items-center">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="p-4 pb-24">
                {{ $slot }}
            </main>

            @include('layouts.navigation')

        </div>
    </body>
</html>