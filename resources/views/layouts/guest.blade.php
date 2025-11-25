<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Garut Food') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden p-6">
            
            <div class="absolute top-0 left-0 w-full h-full z-0 pointer-events-none">
                 <div class="absolute top-[-10%] right-[-10%] w-[400px] h-[400px] bg-orange-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse"></div>
                 <div class="absolute bottom-[-10%] left-[-10%] w-[400px] h-[400px] bg-red-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse" style="animation-delay: 2s"></div>
            </div>

            <div class="w-full sm:max-w-md bg-white shadow-2xl shadow-orange-100 overflow-hidden rounded-[2.5rem] relative z-10 border border-white/50">
                <div class="bg-gradient-to-br from-orange-50 to-white p-8 pb-0 text-center">
                    <a href="/" wire:navigate class="inline-flex items-center justify-center mb-4 transform hover:scale-105 transition duration-300">
                        <img src="{{ asset('logos/logo.PNG') }}" 
                             alt="Garut Food" 
                             class="w-24 h-24 rounded-3xl shadow-lg shadow-orange-200 object-cover border-4 border-white bg-white">
                    </a>
                </div>

                <div class="px-8 py-8 pt-2">
                    {{ $slot }}
                </div>
            </div>

            <div class="mt-8 text-center text-xs text-gray-400 relative z-10">
                &copy; {{ date('Y') }} Garut Food. Rasa Asli Masakan Ibu.
            </div>
        </div>
    </body>
</html>