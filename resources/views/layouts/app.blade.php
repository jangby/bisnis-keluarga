<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        
        <div class="min-h-screen flex flex-col md:flex-row">

            @include('layouts.navigation')

            <main class="flex-1 pb-24 md:pb-8 md:pl-20 lg:pl-64 w-full transition-all duration-300">
                
                @if (isset($header))
                    <header class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-20">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-[1600px] mx-auto">
                    {{ $slot }}
                </div>
            </main>

        </div>
    </body>
</html>