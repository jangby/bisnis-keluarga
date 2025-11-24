<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"> <title>{{ config('app.name', 'Sistik & Kecap Warisan') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Outfit', sans-serif; -webkit-tap-highlight-color: transparent; }
        
        /* Hide scrollbar for smooth horizontal scrolling */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Glassmorphism utility */
        .glass { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); }
        .glass-dark { background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 pb-24"> <header x-data="{ scrolled: false }" 
            @scroll.window="scrolled = (window.pageYOffset > 10)"
            class="fixed top-0 w-full z-40 transition-all duration-300"
            :class="{ 'bg-white shadow-sm py-2': scrolled, 'bg-transparent py-4': !scrolled }">
        <div class="max-w-md mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center text-white font-bold shadow-lg shadow-red-200">
                    S
                </div>
                <div class="flex flex-col">
                    <span class="font-bold leading-none text-lg" :class="scrolled ? 'text-gray-900' : 'text-gray-900'">Sistik<span class="text-red-600">Keluarga</span></span>
                    <span class="text-[10px] text-gray-500 leading-none">Original Taste</span>
                </div>
            </div>
            
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-full shadow-md">Masuk</a>
                @endauth
            @endif
        </div>
    </header>

    <div class="relative w-full h-[45vh] max-w-md mx-auto overflow-hidden rounded-b-[2rem] shadow-xl bg-gray-900">
        <img src="https://images.unsplash.com/photo-1599639668351-a5a5840d2836?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
             class="absolute inset-0 w-full h-full object-cover opacity-70" alt="Hero">
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
        
        <div class="absolute bottom-0 left-0 w-full p-6 text-white">
            <span class="px-2 py-1 bg-white/20 backdrop-blur-md rounded-md text-[10px] font-bold uppercase tracking-wider border border-white/20 mb-2 inline-block">
                Warisan Sejak 1998
            </span>
            <h1 class="text-3xl font-bold leading-tight mb-2">
                Renyahnya <br> <span class="text-red-500">Kebersamaan</span>
            </h1>
            <p class="text-gray-300 text-xs line-clamp-2 w-3/4">
                Nikmati Sistik & Kecap autentik tanpa bahan pengawet.
            </p>
        </div>
    </div>

    <div class="max-w-md mx-auto mt-6 pl-4">
        <h2 class="font-bold text-gray-900 text-base mb-3">Kategori Pilihan</h2>
        <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2 pr-4">
            <button class="flex-none px-4 py-2 rounded-xl bg-red-600 text-white shadow-lg shadow-red-200 text-xs font-bold transition-transform active:scale-95">
                Semua
            </button>
            <button class="flex-none px-4 py-2 rounded-xl bg-white text-gray-600 border border-gray-100 shadow-sm text-xs font-bold whitespace-nowrap active:bg-gray-50">
                🥖 Sistik
            </button>
            <button class="flex-none px-4 py-2 rounded-xl bg-white text-gray-600 border border-gray-100 shadow-sm text-xs font-bold whitespace-nowrap active:bg-gray-50">
                🍯 Kecap Manis
            </button>
            <button class="flex-none px-4 py-2 rounded-xl bg-white text-gray-600 border border-gray-100 shadow-sm text-xs font-bold whitespace-nowrap active:bg-gray-50">
                📦 Paket Hemat
            </button>
        </div>
    </div>

    <main class="max-w-md mx-auto px-4 mt-6">
        <div class="flex justify-between items-end mb-4">
            <h2 class="font-bold text-gray-900 text-base">Terlaris Minggu Ini</h2>
            <a href="#" class="text-xs text-red-600 font-semibold">Lihat Semua</a>
        </div>

        @if($products->isEmpty())
            <div class="text-center py-10 bg-white rounded-2xl border border-dashed border-gray-200">
                <p class="text-sm text-gray-400">Belum ada produk siap jual.</p>
            </div>
        @else
            <div class="grid grid-cols-2 gap-3">
                @foreach($products as $product)
                <div class="bg-white p-2 rounded-2xl shadow-[0_2px_8px_rgba(0,0,0,0.04)] border border-gray-100 group active:scale-[0.98] transition-all duration-200 relative overflow-hidden">
                    
                    @if($product->promo_price && $product->promo_price < $product->sell_price)
                        <div class="absolute top-0 left-0 bg-red-600 text-white text-[9px] font-bold px-2 py-1 rounded-br-lg z-10">
                            {{ round((($product->sell_price - $product->promo_price) / $product->sell_price) * 100) }}%
                        </div>
                    @endif

                    <div class="aspect-square bg-gray-50 rounded-xl overflow-hidden mb-2 relative">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        
                        <button class="absolute bottom-1 right-1 w-7 h-7 bg-white/90 backdrop-blur rounded-full shadow flex items-center justify-center text-gray-800 hover:bg-red-600 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-800 text-xs leading-tight mb-1 truncate">{{ $product->name }}</h3>
                        <p class="text-[10px] text-gray-400 mb-2 truncate">{{ $product->unit }}</p>
                        
                        <div class="flex flex-col">
                            @if($product->promo_price && $product->promo_price < $product->sell_price)
                                <span class="text-[9px] text-gray-400 line-through">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                                <span class="text-sm font-bold text-red-600">Rp {{ number_format($product->promo_price, 0, ',', '.') }}</span>
                            @else
                                <span class="text-sm font-bold text-gray-900">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </main>

    <div class="max-w-md mx-auto px-4 mt-8">
        <div class="bg-gradient-to-r from-red-600 to-orange-500 rounded-2xl p-4 text-white flex items-center justify-between shadow-lg shadow-red-200">
            <div>
                <h3 class="font-bold text-sm">Gratis Ongkir?</h3>
                <p class="text-[10px] text-red-100 opacity-90">Untuk pembelian di atas 100rb khusus Bandung.</p>
            </div>
            <a href="https://wa.me/6281234567890" class="px-3 py-1.5 bg-white text-red-600 text-[10px] font-bold rounded-lg shadow-sm">
                Chat Admin
            </a>
        </div>
    </div>

    <nav class="fixed bottom-4 left-4 right-4 z-50 max-w-md mx-auto">
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-white/20 p-2 flex justify-around items-center">
            <a href="#" class="flex flex-col items-center gap-1 p-2 text-red-600">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                <span class="text-[9px] font-bold">Home</span>
            </a>
            <a href="#produk" class="flex flex-col items-center gap-1 p-2 text-gray-400 hover:text-red-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span class="text-[9px] font-medium">Menu</span>
            </a>
            <div class="relative -top-5">
                <a href="https://wa.me/6281234567890" class="w-12 h-12 bg-gray-900 rounded-full flex items-center justify-center text-white shadow-lg shadow-gray-400 transform transition-transform active:scale-95">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                </a>
            </div>
            <a href="{{ route('login') }}" class="flex flex-col items-center gap-1 p-2 text-gray-400 hover:text-red-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span class="text-[9px] font-medium">Akun</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 p-2 text-gray-400 hover:text-red-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <span class="text-[9px] font-medium">Tas</span>
            </a>
        </div>
    </nav>

</body>
</html>