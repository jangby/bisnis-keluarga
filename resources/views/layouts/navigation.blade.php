<nav class="hidden md:flex flex-col fixed inset-y-0 left-0 z-50 w-20 lg:w-64 bg-white border-r border-gray-200 transition-all duration-300">
    {{-- 1. LOGO & JUDUL (Sidebar Desktop) --}}
    <div class="h-16 flex items-center justify-center lg:justify-start lg:px-6 border-b border-gray-100">
        <div class="bg-blue-600 text-white p-2 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
        </div>
        <span class="ml-3 font-bold text-xl text-gray-800 hidden lg:block">BisnisKu</span>
    </div>

    {{-- 2. DAFTAR MENU SIDEBAR (DESKTOP) --}}
    <div class="flex-1 flex flex-col py-6 space-y-2 px-3">
        @php
            $navClasses = "flex items-center p-3 rounded-xl transition-all duration-200 group";
            $activeClasses = "bg-blue-50 text-blue-600 shadow-sm font-bold";
            $inactiveClasses = "text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium";
        @endphp

        @if(Auth::user()->role !== 'staff')
            
            {{-- A. BERANDA (Semua) --}}
            <a href="{{ route('dashboard') }}" wire:navigate class="{{ $navClasses }} {{ request()->routeIs('dashboard') ? $activeClasses : $inactiveClasses }}">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="ml-3 hidden lg:block">Beranda</span>
            </a>

            {{-- B. KEUANGAN (Owner & Finance) --}}
            @if(in_array(Auth::user()->role, ['owner', 'finance']))
                <a href="{{ route('report.index') }}" wire:navigate class="{{ $navClasses }} {{ request()->routeIs('report.*') ? $activeClasses : $inactiveClasses }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span class="ml-3 hidden lg:block">Keuangan</span>
                </a>
            @endif

            {{-- C. KASIR / KERANJANG (Owner & Marketing) --}}
            @if(in_array(Auth::user()->role, ['owner', 'marketing']))
                <a href="{{ route('pos.index') }}" wire:navigate class="{{ $navClasses }} {{ request()->routeIs('pos.index') ? $activeClasses : $inactiveClasses }}">
                    {{-- Icon Keranjang --}}
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="ml-3 hidden lg:block">Kasir</span>
                </a>
            @endif

            {{-- D. MENU STOK (Owner, Finance, Marketing, Inventory) --}}
            {{-- TAMBAHKAN 'inventory' DI SINI --}}
            @if(in_array(Auth::user()->role, ['owner', 'finance', 'marketing', 'inventory'])) 
                <a href="{{ route('products.index') }}" wire:navigate class="{{ $navClasses }} {{ request()->routeIs('products.*') ? $activeClasses : $inactiveClasses }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>
                    <span class="ml-3 hidden lg:block">Stok Produk</span>
                </a>
            @endif

            {{-- E. PRODUKSI (Owner & Production) --}}
            @if(in_array(Auth::user()->role, ['owner', 'production']))
                <a href="{{ route('production.run') }}" wire:navigate class="{{ $navClasses }} {{ request()->routeIs('production.*') ? $activeClasses : $inactiveClasses }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span class="ml-3 hidden lg:block">Produksi</span>
                </a>
            @endif

            {{-- F. SETTING (Owner Only) --}}
            @if(Auth::user()->role === 'owner')
                <a href="{{ route('settings.index') }}" wire:navigate class="{{ $navClasses }} {{ request()->routeIs('settings.*') ? $activeClasses : $inactiveClasses }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="ml-3 hidden lg:block">Setting</span>
                </a>
            @endif

            {{-- G. MENU KHUSUS INVENTORY --}}
            @if(Auth::user()->role === 'inventory')
                {{-- Request Bahan --}}
                <a href="{{ route('production.request') }}" wire:navigate class="{{ $navClasses }} {{ request()->routeIs('production.request') ? $activeClasses : $inactiveClasses }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span class="ml-3 hidden lg:block">Request Bahan</span>
                </a>

                {{-- Log Aktivitas --}}
                <a href="{{ route('activity.index') }}" wire:navigate class="{{ $navClasses }} {{ request()->routeIs('activity.index') ? $activeClasses : $inactiveClasses }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="ml-3 hidden lg:block">Log Aktivitas</span>
                </a>
            @endif
        @endif
        
        {{-- LOGOUT (Desktop) --}}
        <div class="mt-auto pt-4 border-t border-gray-100">
             <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center p-3 rounded-xl text-red-500 hover:bg-red-50 hover:text-red-700 transition-all duration-200 group">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span class="ml-3 font-bold hidden lg:block">Keluar</span>
                </button>
            </form>
        </div>
    </div>
</nav>

{{-- ================================================================= --}}
{{-- 3. NAVIGASI BAWAH / MOBILE BOTTOM NAV (TAMPIL DI HP)              --}}
{{-- ================================================================= --}}
@if(Auth::user()->role !== 'staff')
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 pb-safe">
    <div class="flex justify-around items-center h-16 px-1">
        
        {{-- A. BERANDA (Semua Role) --}}
        <a href="{{ route('dashboard') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center py-2 text-gray-400 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'hover:text-gray-600' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-[10px] font-medium">Beranda</span>
        </a>

        {{-- ============================================= --}}
        {{-- B. LOGIK KHUSUS BERDASARKAN ROLE              --}}
        {{-- ============================================= --}}

        {{-- 1. TAMPILAN KHUSUS MARKETING --}}
        @if(Auth::user()->role === 'marketing')
             {{-- Tengah: Kasir --}}
             <div class="relative -top-5">
                <a href="{{ route('pos.index') }}" wire:navigate class="flex items-center justify-center w-14 h-14 rounded-full bg-green-600 text-white shadow-lg shadow-green-600/30 border-4 border-gray-50 transform transition active:scale-95">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </a>
            </div>

        {{-- 2. TAMPILAN KHUSUS PRODUKSI --}}
        @elseif(Auth::user()->role === 'production')
            {{-- Tengah: Input Produksi --}}
            <div class="relative -top-5">
                <a href="{{ route('production.run') }}" wire:navigate class="flex items-center justify-center w-14 h-14 rounded-full bg-orange-600 text-white shadow-lg shadow-orange-600/30 border-4 border-gray-50 transform transition active:scale-95">
                     <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/></svg>
                </a>
            </div>

        {{-- 3. TAMPILAN KHUSUS INVENTORY (GUDANG) --}}
        @elseif(Auth::user()->role === 'inventory')
            
            {{-- Tombol Kiri: Request --}}
            <a href="{{ route('production.request') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center py-2 text-gray-400 {{ request()->routeIs('production.request') ? 'text-blue-600' : 'hover:text-gray-600' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-[10px] font-medium">Request</span>
            </a>

            {{-- Tombol Tengah: Tambah Produk --}}
            <div class="relative -top-5">
                <a href="{{ route('products.create') }}" wire:navigate class="flex items-center justify-center w-14 h-14 rounded-full bg-purple-600 text-white shadow-lg shadow-purple-600/30 border-4 border-gray-50 transform transition active:scale-95">
                     <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </a>
            </div>

            {{-- Tombol Kanan: Stok --}}
            <a href="{{ route('products.index') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center py-2 text-gray-400 {{ request()->routeIs('products.index') ? 'text-blue-600' : 'hover:text-gray-600' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>
                <span class="text-[10px] font-medium">Stok</span>
            </a>

        {{-- 3. TAMPILAN OWNER (LENGKAP SESUAI REQUEST) --}}
        @elseif(Auth::user()->role === 'owner')
            
            {{-- Keuangan --}}
            <a href="{{ route('report.index') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center py-2 text-gray-400 {{ request()->routeIs('report.*') ? 'text-blue-600' : 'hover:text-gray-600' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span class="text-[10px] font-medium">Keuangan</span>
            </a>

            {{-- Tengah: Kasir (Keranjang) --}}
            <div class="relative -top-5">
                <a href="{{ route('pos.index') }}" wire:navigate class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-600 text-white shadow-lg shadow-blue-600/30 border-4 border-gray-50 transform transition active:scale-95">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </a>
            </div>

            {{-- Setting --}}
            <a href="{{ route('settings.index') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center py-2 text-gray-400 {{ request()->routeIs('settings.*') ? 'text-blue-600' : 'hover:text-gray-600' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-[10px] font-medium">Setting</span>
            </a>

        @else
            {{-- FINANCE DLL --}}
            <a href="{{ route('report.index') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center py-2 text-gray-400 {{ request()->routeIs('report.*') ? 'text-blue-600' : 'hover:text-gray-600' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span class="text-[10px] font-medium">Keuangan</span>
            </a>
        @endif


        {{-- C. LOGOUT MOBILE (Paling Kanan) --}}
        <form method="POST" action="{{ route('logout') }}" class="flex-1 flex flex-col items-center justify-center py-2 text-gray-400 hover:text-red-500 cursor-pointer">
            @csrf
            <button type="submit" class="flex flex-col items-center justify-center w-full h-full">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span class="text-[10px] font-medium">Keluar</span>
            </button>
        </form>

    </div>
</nav>
@endif