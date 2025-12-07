<nav class="hidden md:flex flex-col fixed inset-y-0 left-0 z-50 w-20 lg:w-64 bg-white border-r border-gray-200 transition-all duration-300">
    <div class="h-16 flex items-center justify-center lg:justify-start lg:px-6 border-b border-gray-100">
        <div class="bg-blue-600 text-white p-2 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
        </div>
        <span class="ml-3 font-bold text-xl text-gray-800 hidden lg:block">BisnisKu</span>
    </div>

    <div class="flex-1 flex flex-col py-6 space-y-2 px-3">
        @php
            $navClasses = "flex items-center p-3 rounded-xl transition-all duration-200 group";
            $activeClasses = "bg-blue-50 text-blue-600 shadow-sm";
            $inactiveClasses = "text-gray-500 hover:bg-gray-50 hover:text-gray-900";
        @endphp

        {{-- LOGIC: Sembunyikan semua menu sidebar untuk Staff --}}
        @if(Auth::user()->role !== 'staff')
            <a href="{{ route('dashboard') }}" wire:navigate class="{{ $navClasses }} {{ request()->routeIs('dashboard') ? $activeClasses : $inactiveClasses }}">
                <svg class="w-6 h-6 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                <span class="ml-3 font-medium hidden lg:block">Beranda</span>
            </a>

            <a href="{{ route('report.index') }}" wire:navigate class="{{ $navClasses }} {{ request()->routeIs('report.*') ? $activeClasses : $inactiveClasses }}">
                <svg class="w-6 h-6 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span class="ml-3 font-medium hidden lg:block">Keuangan</span>
            </a>

            {{-- Tombol POS untuk Desktop --}}
            <a href="{{ route('pos.index') }}" wire:navigate class="{{ $navClasses }} {{ request()->routeIs('pos.index') ? $activeClasses : $inactiveClasses }}">
                <svg class="w-6 h-6 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 2h2l.6 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span class="ml-3 font-medium hidden lg:block">Kasir (POS)</span>
            </a>

            @if(in_array(Auth::user()->role, ['owner', 'production']))
            <a href="{{ route('production.run') }}" wire:navigate class="{{ $navClasses }} {{ request()->routeIs('production.*') ? $activeClasses : $inactiveClasses }}">
                <svg class="w-6 h-6 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                <span class="ml-3 font-medium hidden lg:block">Produksi</span>
            </a>
            @endif

            <a href="{{ route('products.index') }}" wire:navigate class="{{ $navClasses }} {{ request()->routeIs('products.*') ? $activeClasses : $inactiveClasses }}">
                <svg class="w-6 h-6 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>
                <span class="ml-3 font-medium hidden lg:block">Stok Produk</span>
            </a>

            <a href="{{ route('settings.index') }}" wire:navigate class="{{ $navClasses }} {{ request()->routeIs('settings.index') ? $activeClasses : $inactiveClasses }}">
                <svg class="w-6 h-6 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-1-1.72v-.51a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                <span class="ml-3 font-medium hidden lg:block">Pengaturan</span>
            </a>
        @endif
    </div>

    <div class="p-4 border-t border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xs">
                {{ substr(Auth::user()->name, 0, 2) }}
            </div>
            <div class="hidden lg:block">
                <p class="text-sm font-bold text-gray-700 truncate">{{ Auth::user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}" class="cursor-pointer">
                    @csrf
                    <button type="submit" class="text-xs text-red-500 hover:text-red-700">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- LOGIC: Sembunyikan TOTAL navbar bawah (Mobile) untuk Staff --}}
@if(Auth::user()->role !== 'staff')
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 pb-safe">
    <div class="flex justify-around items-center h-16 px-2">
        
        <a href="{{ route('dashboard') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center py-2 text-gray-400 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'hover:text-gray-600' }}">
            <svg class="w-6 h-6 mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
            <span class="text-[10px] font-medium">Beranda</span>
        </a>

        <a href="{{ route('report.index') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center py-2 text-gray-400 {{ request()->routeIs('report.*') ? 'text-blue-600' : 'hover:text-gray-600' }}">
            <svg class="w-6 h-6 mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            <span class="text-[10px] font-medium">Keuangan</span>
        </a>

        <div class="relative -top-5">
            <a href="{{ route('pos.index') }}" wire:navigate class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-600 text-white shadow-lg shadow-blue-600/30 border-4 border-gray-50 transform transition active:scale-95">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 2h2l.6 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </a>
        </div>

        <a href="{{ route('products.index') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center py-2 text-gray-400 {{ request()->routeIs('products.*') ? 'text-blue-600' : 'hover:text-gray-600' }}">
            <svg class="w-6 h-6 mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>
            <span class="text-[10px] font-medium">Stok</span>
        </a>

        <a href="{{ route('settings.index') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center py-2 text-gray-400 {{ request()->routeIs('settings.index') ? 'text-blue-600' : 'hover:text-gray-600' }}">
            <svg class="w-6 h-6 mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-1-1.72v-.51a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
            <span class="text-[10px] font-medium">Setting</span>
        </a>
    </div>
</nav>
@endif