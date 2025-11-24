<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Bisnis Keluarga') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Animasi Kustom Sederhana */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-800 font-figtree">

    <nav class="absolute top-0 w-full z-50 px-6 py-6 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg shadow-indigo-200">
                🍲
            </div>
            <span class="font-bold text-xl text-gray-800 tracking-tight hidden sm:block">Dapur Keluarga</span>
        </div>
        
        <div class="flex items-center gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2 rounded-full bg-white/80 backdrop-blur text-sm font-bold text-gray-700 hover:bg-white transition shadow-sm border border-white/50">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-indigo-600 transition">
                        Masuk
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-5 py-2 rounded-full bg-gray-900 text-white text-sm font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-300/50">
                            Daftar
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <div class="relative min-h-[90vh] flex flex-col justify-center overflow-hidden">
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s"></div>

        <div class="relative max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center pt-20">
            <div class="space-y-6 text-center lg:text-left z-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-orange-100 text-orange-700 text-xs font-bold uppercase tracking-wider mb-2">
                    <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                    Buka Setiap Hari
                </div>
                
                <h1 class="text-5xl sm:text-7xl font-black leading-tight text-gray-900">
                    Rasa Asli <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-pink-500">
                        Masakan Ibu.
                    </span>
                </h1>
                
                <p class="text-lg text-gray-500 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                    Dibuat dengan bahan pilihan dan resep turun-temurun. Nikmati kehangatan keluarga dalam setiap gigitan, langsung diantar ke rumahmu.
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start pt-4">
                    <a href="{{ route('front.index') }}" wire:navigate 
                       class="w-full sm:w-auto px-8 py-4 bg-indigo-600 text-white text-lg font-bold rounded-2xl shadow-xl shadow-indigo-300 hover:bg-indigo-700 hover:scale-105 transition transform flex items-center justify-center gap-2">
                        Pesan Sekarang <span>🚀</span>
                    </a>
                    
                    <a href="{{ route('front.account') }}" wire:navigate
                       class="w-full sm:w-auto px-8 py-4 bg-white text-gray-700 text-lg font-bold rounded-2xl shadow-sm border border-gray-100 hover:bg-gray-50 hover:text-indigo-600 transition flex items-center justify-center gap-2">
                        <span>📦</span> Cek Pesanan
                    </a>
                </div>
            </div>

            <div class="relative hidden lg:block h-[500px]">
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-gradient-to-br from-indigo-100 to-pink-100 rounded-full flex items-center justify-center shadow-inner">
                    <span class="text-[180px] animate-float drop-shadow-2xl filter">🍛</span>
                </div>
                <div class="absolute top-20 right-10 bg-white p-4 rounded-2xl shadow-lg animate-float" style="animation-delay: 1s;">
                    <span class="text-4xl">🔥</span>
                </div>
                <div class="absolute bottom-20 left-10 bg-white p-4 rounded-2xl shadow-lg animate-float" style="animation-delay: 2.5s;">
                    <span class="text-4xl">🥬</span>
                </div>
                <div class="absolute top-1/2 right-[-20px] bg-white px-6 py-3 rounded-xl shadow-xl animate-float flex items-center gap-3" style="animation-delay: 1.5s;">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="font-bold text-gray-800 text-sm">Bahan Segar</span>
                </div>
            </div>
        </div>
    </div>

    <div class="py-20 bg-white rounded-t-[3rem] relative z-20 shadow-[0_-10px_40px_rgba(0,0,0,0.03)]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 mb-2">Menu Favorit</h2>
                    <p class="text-gray-500">Paling banyak dicari minggu ini 🔥</p>
                </div>
                <a href="{{ route('front.index') }}" class="hidden sm:flex items-center gap-2 text-indigo-600 font-bold hover:underline">
                    Lihat Semua Menu <span>→</span>
                </a>
            </div>

            <div class="flex overflow-x-auto gap-6 pb-10 hide-scrollbar snap-x snap-mandatory">
                @foreach($products->take(5) as $product)
                    <div class="snap-center shrink-0 w-[280px] bg-gray-50 rounded-3xl p-4 transition hover:bg-white hover:shadow-xl hover:shadow-indigo-100 hover:-translate-y-2 border border-transparent hover:border-indigo-100 group cursor-pointer relative">
                        
                        @if($product->has_discount)
                            <div class="absolute top-4 left-4 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full z-10 shadow-sm">
                                PROMO
                            </div>
                        @endif

                        <div class="h-40 bg-white rounded-2xl flex items-center justify-center text-6xl shadow-inner mb-4 group-hover:scale-105 transition duration-300">
                            {{-- Placeholder Emoji --}}
                            🍽️
                        </div>
                        
                        <h3 class="font-bold text-gray-900 text-lg mb-1 truncate">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-500 mb-4 line-clamp-2">Rasakan kenikmatan {{ $product->name }} buatan kami.</p>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                @if($product->has_discount)
                                    <span class="block text-xs text-gray-400 line-through">Rp {{ number_format($product->sell_price/1000, 0) }}k</span>
                                    <span class="block text-lg font-black text-gray-900">Rp {{ number_format($product->discount_price/1000, 0) }}k</span>
                                @else
                                    <span class="block text-lg font-black text-gray-900">Rp {{ number_format($product->sell_price/1000, 0) }}k</span>
                                @endif
                            </div>
                            <a href="{{ route('front.index') }}" class="w-10 h-10 rounded-full bg-gray-900 text-white flex items-center justify-center hover:bg-indigo-600 transition shadow-lg">
                                +
                            </a>
                        </div>
                    </div>
                @endforeach

                <a href="{{ route('front.index') }}" class="snap-center shrink-0 w-[200px] flex flex-col items-center justify-center bg-indigo-50 rounded-3xl border-2 border-dashed border-indigo-200 hover:bg-indigo-100 transition cursor-pointer text-indigo-600">
                    <span class="text-3xl mb-2">➡️</span>
                    <span class="font-bold">Lihat Semua</span>
                </a>
            </div>

            <div class="mt-4 text-center sm:hidden">
                <a href="{{ route('front.index') }}" class="inline-block px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl w-full">
                    Buka Buku Menu Lengkap
                </a>
            </div>
        </div>
    </div>

    <footer class="bg-white pt-10 pb-20 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="text-2xl mb-4">🥣</div>
            <h3 class="font-bold text-gray-900">Dapur Keluarga</h3>
            <p class="text-sm text-gray-500 mb-8">Dibuat dengan ❤️ untuk keluarga Indonesia.</p>
            <p class="text-xs text-gray-400">&copy; {{ date('Y') }} Bisnis Keluarga. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>