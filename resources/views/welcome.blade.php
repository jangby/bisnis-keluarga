<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bisnis Keluarga') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Animasi Floating untuk Emoji */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        
        /* Hide Scrollbar tapi tetap bisa scroll (Opsional) */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-800 font-figtree pb-10">

    <nav class="fixed top-0 w-full z-50 px-4 sm:px-6 py-4 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-white/20 shadow-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg shadow-indigo-200">
                    🍲
                </div>
                <span class="font-bold text-lg text-gray-800 tracking-tight hidden sm:block">Dapur Keluarga</span>
            </div>
            
            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-full bg-gray-900 text-white text-xs font-bold shadow-lg shadow-gray-300 hover:scale-105 transition transform">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold text-gray-600 hover:text-indigo-600 transition px-2">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-indigo-600 text-white text-xs font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:scale-105 transition transform">
                                Daftar
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <div class="relative pt-32 pb-10 sm:pt-40 sm:pb-16 overflow-hidden">
        <div class="absolute top-0 right-0 w-[300px] h-[300px] bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute top-20 left-0 w-[300px] h-[300px] bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse -translate-x-1/2"></div>

        <div class="relative max-w-7xl mx-auto px-6 text-center z-10">
            
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-orange-100 text-orange-700 text-[10px] font-bold uppercase tracking-wider mb-6 border border-orange-200 shadow-sm">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                </span>
                Buka Setiap Hari
            </div>
            
            <h1 class="text-4xl sm:text-6xl font-black leading-tight text-gray-900 mb-6">
                Rasa Asli <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-pink-500">
                    Masakan Ibu.
                </span>
            </h1>
            
            <p class="text-base text-gray-500 max-w-lg mx-auto leading-relaxed mb-8">
                Nikmati kehangatan masakan rumahan dengan bahan pilihan terbaik. Pesan online, kami antar sampai depan pintu.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('front.index') }}" wire:navigate 
                   class="w-full sm:w-auto px-8 py-4 bg-indigo-600 text-white text-sm font-bold rounded-2xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 hover:scale-105 transition transform flex items-center justify-center gap-2 active:scale-95">
                    Pesan Sekarang <span>🚀</span>
                </a>
                
                <a href="{{ route('front.account') }}" wire:navigate
                   class="w-full sm:w-auto px-8 py-4 bg-white text-gray-700 text-sm font-bold rounded-2xl shadow-sm border border-gray-100 hover:bg-gray-50 hover:text-indigo-600 transition flex items-center justify-center gap-2 active:scale-95">
                    <span>📦</span> Cek Pesanan
                </a>
            </div>
        </div>
    </div>

    <div class="py-12 bg-white rounded-t-[2.5rem] relative z-20 shadow-[0_-10px_40px_rgba(0,0,0,0.03)] border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            
            <div class="flex flex-row justify-between items-end mb-8 gap-4">
                <div>
                    <span class="text-indigo-600 font-bold tracking-wider uppercase text-[10px]">Pilihan Terbaik</span>
                    <h2 class="text-2xl font-black text-gray-900 mt-1">Menu Favorit 🔥</h2>
                </div>
                <a href="{{ route('front.index') }}" wire:navigate class="group flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-indigo-600 transition">
                    Lihat Semua
                    <span class="w-5 h-5 rounded-full bg-gray-100 group-hover:bg-indigo-100 flex items-center justify-center transition">→</span>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
                @foreach($products->take(8) as $product)
                    <div class="bg-white rounded-2xl p-3 shadow-sm border border-gray-100 hover:shadow-xl hover:border-indigo-100 hover:-translate-y-1 transition duration-300 group flex flex-col relative h-full">
                        
                        @if($product->has_discount)
                            <div class="absolute top-0 left-0 bg-red-500 text-white text-[9px] font-bold px-2 py-1 rounded-br-xl rounded-tl-xl z-10 shadow-sm">
                                HEMAT {{ number_format($product->sell_price - $product->discount_price) }}
                            </div>
                        @endif

                        <div class="aspect-square bg-gray-50 rounded-xl flex items-center justify-center mb-3 group-hover:scale-105 transition-transform duration-500 relative overflow-hidden">
    
    @if($product->image_url)
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
    @else
        <div class="text-4xl sm:text-5xl">🍽️</div>
    @endif

    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700 transform -translate-x-full group-hover:translate-x-full"></div>
</div>
                        
                        <div class="flex-1 flex flex-col">
                            <h3 class="font-bold text-gray-900 text-xs sm:text-base leading-tight mb-1 line-clamp-2 min-h-[2.5em]">
                                {{ $product->name }}
                            </h3>
                            <p class="text-[10px] sm:text-xs text-gray-400 mb-3">{{ $product->unit }}</p>
                            
                            <div class="mt-auto flex items-end justify-between gap-1">
                                <div class="flex flex-col">
                                    @if($product->has_discount)
                                        <span class="text-[9px] sm:text-[10px] text-gray-400 line-through">Rp {{ number_format($product->sell_price, 0) }}</span>
                                        <span class="text-sm sm:text-lg font-black text-gray-900">Rp {{ number_format($product->discount_price, 0) }}</span>
                                    @else
                                        <span class="text-[9px] sm:text-[10px] text-transparent select-none">.</span>
                                        <span class="text-sm sm:text-lg font-black text-gray-900">Rp {{ number_format($product->sell_price, 0) }}</span>
                                    @endif
                                </div>
                                
                                <a href="{{ route('front.index') }}" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-gray-900 text-white flex items-center justify-center hover:bg-indigo-600 transition shadow-lg active:scale-90">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 text-center sm:hidden">
                <a href="{{ route('front.index') }}" wire:navigate class="inline-flex items-center justify-center w-full py-3.5 bg-gray-100 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-200 transition active:scale-98">
                    Jelajahi Menu Lainnya
                </a>
            </div>
        </div>
    </div>

    <footer class="bg-white pt-10 pb-10 border-t border-gray-100 text-center">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-3xl mb-4 animate-bounce">🥣</div>
            <h3 class="font-bold text-gray-900">Dapur Keluarga</h3>
            <p class="text-xs text-gray-400 mt-2">&copy; {{ date('Y') }} Bisnis Keluarga.</p>
        </div>
    </footer>

</body>
</html>