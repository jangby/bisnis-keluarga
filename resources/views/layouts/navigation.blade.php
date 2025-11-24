<nav class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200">
    <div class="max-w-md mx-auto w-full">
        
        {{-- Gunakan FLEXBOX (justify-between) agar menu otomatis berjarak rata --}}
        <div class="flex justify-between items-center h-16 px-2 relative">
            
            {{-- 1. BERANDA (Semua Role Bisa Akses) --}}
            <a href="{{ route('dashboard') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center h-full text-gray-500 hover:text-blue-600 {{ request()->routeIs('dashboard') ? 'text-blue-600' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-[10px] font-medium">Beranda</span>
            </a>

            {{-- 2. KEUANGAN (Semua Role Bisa Akses - Atau mau dibatasi juga?) --}}
            <a href="{{ route('report.index') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center h-full text-gray-500 hover:text-blue-600 {{ request()->routeIs('report.*') ? 'text-blue-600' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-[10px] font-medium">Keuangan</span>
            </a>

            {{-- 3. TOMBOL PRODUKSI (HANYA OWNER & PRODUCTION) --}}
            {{-- Jika BUKAN Owner/Produksi, tampilkan placeholder kosong agar layout tetap rapi --}}
            @if(in_array(Auth::user()->role, ['owner', 'production']))
                <div class="relative -top-6 w-16 flex justify-center shrink-0 z-10">
                    <a href="{{ route('production.run') }}" wire:navigate class="flex flex-col items-center justify-center w-14 h-14 bg-blue-600 rounded-full shadow-lg shadow-blue-600/40 hover:bg-blue-700 hover:scale-105 transition-all border-4 border-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </a>
                    <span class="absolute -bottom-4 text-[10px] font-bold text-blue-600">Produksi</span>
                </div>
            @else
                {{-- Placeholder kosong agar tombol Stok & Setting tidak geser ke tengah --}}
                <div class="w-16 shrink-0"></div>
            @endif

            {{-- 4. STOK --}}
            <a href="{{ route('products.index') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center h-full text-gray-500 hover:text-blue-600 {{ request()->routeIs('products.*') ? 'text-blue-600' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span class="text-[10px] font-medium">Stok</span>
            </a>

            {{-- 5. SETTING --}}
            <a href="{{ route('settings.index') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center h-full text-gray-500 hover:text-blue-600 {{ request()->routeIs('settings.index') ? 'text-blue-600' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-[10px] font-medium">Setting</span>
            </a>

        </div>
    </div>
</nav>