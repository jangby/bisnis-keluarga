<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Garut Food - Sistik & Kecap Asli</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased bg-orange-50/50 text-gray-800 font-figtree pb-10">

    <nav class="fixed top-0 w-full z-50 px-4 sm:px-6 py-4 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-orange-100 shadow-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logos/logo.PNG') }}" 
     alt="Garut Food Logo" 
     class="w-10 h-10 rounded-xl shadow-lg shadow-orange-200 border-2 border-white object-cover bg-white">
                <div>
                    <h1 class="font-black text-lg text-gray-900 tracking-tight leading-none">Garut Food</h1>
                    <span class="text-[10px] font-bold text-orange-600 uppercase tracking-widest">Oleh-oleh Asli</span>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-full bg-gray-900 text-white text-xs font-bold shadow-lg shadow-gray-300 hover:scale-105 transition transform">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold text-gray-600 hover:text-orange-600 transition px-2">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-orange-600 text-white text-xs font-bold shadow-lg shadow-orange-200 hover:bg-orange-700 hover:scale-105 transition transform">
                                Daftar
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <div class="relative pt-32 pb-16 sm:pt-44 sm:pb-24 overflow-hidden">
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-pulse translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute top-20 left-0 w-[300px] h-[300px] bg-red-100 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-pulse -translate-x-1/2"></div>

        <div class="relative max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
            
            <div class="text-center lg:text-left z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-orange-100 text-orange-700 text-[10px] font-bold uppercase tracking-wider mb-6 shadow-sm">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                    </span>
                    Produksi Rumahan Asli Garut
                </div>
                
                <h1 class="text-4xl sm:text-6xl font-black leading-tight text-gray-900 mb-6">
                    Renyahnya <span class="text-orange-600">Sistik,</span><br>
                    Legitnya <span class="text-amber-900">Kecap.</span>
                </h1>
                
                <p class="text-base text-gray-600 max-w-lg mx-auto lg:mx-0 leading-relaxed mb-8">
                    Hadirkan cita rasa khas Garut di meja makan Anda. Sistik gurih yang bikin nagih dan Kecap manis alami warisan leluhur.
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                    <a href="{{ route('front.index') }}" wire:navigate 
                       class="w-full sm:w-auto px-8 py-4 bg-gray-900 text-white text-sm font-bold rounded-2xl shadow-xl shadow-gray-400 hover:bg-black hover:scale-105 transition transform flex items-center justify-center gap-2 active:scale-95">
                        Pesan Sekarang <span>🛍️</span>
                    </a>
                </div>
            </div>

            <div class="relative hidden lg:block h-[400px]">
                <div class="absolute top-0 right-10 bg-white p-4 rounded-3xl shadow-xl border border-orange-100 transform rotate-6 hover:rotate-0 transition duration-500 z-10 w-64">
                    <div class="h-40 bg-orange-50 rounded-2xl flex items-center justify-center text-6xl mb-4">
                        🍟
                    </div>
                    <h3 class="font-bold text-gray-900">Sistik Keju</h3>
                    <p class="text-xs text-gray-500">Renyah & Gurih</p>
                </div>

                <div class="absolute bottom-10 left-10 bg-white p-4 rounded-3xl shadow-xl border border-amber-100 transform -rotate-6 hover:rotate-0 transition duration-500 z-20 w-64">
                    <div class="h-40 bg-amber-50 rounded-2xl flex items-center justify-center text-6xl mb-4">
                        🍶
                    </div>
                    <h3 class="font-bold text-gray-900">Kecap Manis</h3>
                    <p class="text-xs text-gray-500">Kental & Alami</p>
                </div>

                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-red-600 text-white px-6 py-3 rounded-full font-bold shadow-lg z-30 animate-bounce">
                    100% Halal
                </div>
            </div>

        </div>
    </div>

    <div class="py-16 bg-white rounded-t-[3rem] relative z-20 shadow-[0_-10px_40px_rgba(0,0,0,0.03)] border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            
            <div class="flex flex-row justify-between items-end mb-8 gap-4">
                <div>
                    <span class="text-orange-600 font-bold tracking-wider uppercase text-[10px]">Katalog Produk</span>
                    <h2 class="text-2xl font-black text-gray-900 mt-1">Stok Tersedia 📦</h2>
                </div>
                <a href="{{ route('front.index') }}" wire:navigate class="group flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-orange-600 transition">
                    Lihat Semua
                    <span class="w-5 h-5 rounded-full bg-gray-100 group-hover:bg-orange-100 flex items-center justify-center transition">→</span>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
                @foreach($products->take(8) as $product)
                    <div class="bg-white rounded-2xl p-3 shadow-sm border border-gray-100 hover:shadow-xl hover:border-orange-200 hover:-translate-y-1 transition duration-300 group flex flex-col relative h-full">
                        
                        @if($product->has_discount)
                            <div class="absolute top-0 left-0 bg-red-600 text-white text-[9px] font-bold px-2 py-1 rounded-br-xl rounded-tl-xl z-10 shadow-sm">
                                HEMAT {{ number_format($product->sell_price - $product->discount_price) }}
                            </div>
                        @endif

                        <div class="aspect-square bg-gray-50 rounded-xl flex items-center justify-center mb-3 group-hover:scale-105 transition-transform duration-500 relative overflow-hidden">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-4xl sm:text-5xl">🍽️</div>
                            @endif
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
                                
                                <a href="{{ route('front.index') }}" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-gray-900 text-white flex items-center justify-center hover:bg-orange-600 transition shadow-lg active:scale-90">
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

    <footer class="bg-white pt-12 pb-10 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8 text-center md:text-left">
            <div>
                <div class="flex items-center justify-center md:justify-start gap-2 mb-4">
    <img src="{{ asset('logos/logo.PNG') }}" 
         alt="Garut Food Logo" 
         class="w-8 h-8 rounded-lg object-cover bg-white shadow-sm border border-orange-100">
    <span class="font-black text-xl text-gray-800">Garut Food</span>
</div>

                <p class="text-sm text-gray-500 leading-relaxed">
                    Menyediakan Sistik renyah dan Kecap manis asli produksi rumahan Garut. Higienis, halal, dan tanpa bahan pengawet berbahaya.
                </p>
            </div>
            
            <div>
                <h4 class="font-bold text-gray-900 mb-4">Produk Kami</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li>Sistik Keju & Pedas</li>
                    <li>Kecap Manis Kental</li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-gray-900 mb-4">Hubungi Kami</h4>
                <p class="text-sm text-gray-500 mb-2">📍 Garut, Jawa Barat</p>
                <p class="text-sm text-gray-500">📞 085797750256</p>
            </div>
        </div>
        <div class="text-center text-xs text-gray-400 mt-10 border-t border-gray-50 pt-6">
            &copy; {{ date('Y') }} Garut Food. All rights reserved.
        </div>
    </footer>

</body>
</html>