<div class="space-y-8 font-sans text-gray-800">

    {{-- 1. HERO SECTION & GREETING --}}
    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4 animate-fade-in-down">
        <div>
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Overview Bisnis</p>
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 leading-tight">
                Halo, {{ Auth::user()->name }}! 👋
            </h1>
            <p class="text-gray-500 mt-1">Berikut adalah performa bisnis Anda hari ini, <span class="font-bold text-gray-700">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>.</p>
        </div>
        <div class="hidden md:block">
            <span class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-full text-sm font-bold shadow-sm flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                Sistem Online
            </span>
        </div>
    </div>

    {{-- 2. STATISTIC CARDS (HIGHLIGHT) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Card 1: Omset Hari Ini (Gradient Purple) --}}
        <div class="relative overflow-hidden rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-purple-700 text-white shadow-xl shadow-purple-500/30 group hover:scale-[1.02] transition-transform duration-300">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-sm font-medium text-indigo-100 uppercase tracking-wide">Omset Hari Ini</span>
                </div>
                <h3 class="text-3xl font-black tracking-tight">Rp {{ number_format($incomeToday, 0, ',', '.') }}</h3>
                <p class="text-xs text-indigo-200 mt-2 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    Pantau terus penjualan kasir
                </p>
            </div>
        </div>

        {{-- Card 2: Laba Bulan Ini (Gradient Emerald) --}}
        <div class="relative overflow-hidden rounded-3xl p-6 bg-gradient-to-br from-emerald-500 to-teal-700 text-white shadow-xl shadow-emerald-500/30 group hover:scale-[1.02] transition-transform duration-300">
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                    <span class="text-sm font-medium text-emerald-100 uppercase tracking-wide">Profit Bulan Ini</span>
                </div>
                <h3 class="text-3xl font-black tracking-tight">Rp {{ number_format($profitMonth, 0, ',', '.') }}</h3>
                <p class="text-xs text-emerald-100 mt-2 opacity-80">
                    Laba bersih (Income - Expense)
                </p>
            </div>
        </div>

        {{-- Card 3: Kehadiran Pegawai (Gradient Blue) --}}
        <div class="relative overflow-hidden rounded-3xl p-6 bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-xl shadow-blue-500/30 group hover:scale-[1.02] transition-transform duration-300">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 -mt-6 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                        <span class="text-sm font-medium text-blue-100 uppercase tracking-wide">Kehadiran</span>
                    </div>
                    <span class="text-lg font-bold">{{ round($attendancePercent) }}%</span>
                </div>
                <h3 class="text-3xl font-black tracking-tight">{{ $presentToday }} <span class="text-lg font-medium text-blue-200">/ {{ $totalStaff }} Org</span></h3>
                <div class="w-full bg-blue-900/30 h-1.5 rounded-full mt-4 overflow-hidden">
                    <div class="bg-white h-full rounded-full" style="width: {{ $attendancePercent }}%"></div>
                </div>
            </div>
        </div>

        {{-- Card 4: Stok Alert (Gradient Orange) --}}
        <div class="relative overflow-hidden rounded-3xl p-6 bg-gradient-to-br from-orange-400 to-red-500 text-white shadow-xl shadow-orange-500/30 group hover:scale-[1.02] transition-transform duration-300">
            <div class="absolute bottom-0 right-0 -mb-6 -mr-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                    <span class="text-sm font-medium text-orange-100 uppercase tracking-wide">Stok Menipis</span>
                </div>
                <h3 class="text-3xl font-black tracking-tight">{{ $lowStockCount }} <span class="text-lg font-medium text-orange-100">Item</span></h3>
                <p class="text-xs text-orange-100 mt-2">
                    Perlu restock segera!
                </p>
            </div>
        </div>
    </div>

    {{-- 3. MENU PUSAT KONTROL (Grid Rapi) --}}
    <div>
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            🚀 Pusat Kontrol
            <div class="h-px bg-gray-200 flex-1 ml-4"></div>
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            
            {{-- Menu Item Component --}}
            <x-menu-card href="{{ route('pos.index') }}" color="green" icon="shopping-cart" title="Kasir Toko" subtitle="Point of Sales" />
            <x-menu-card href="{{ route('products.index') }}" color="blue" icon="cube" title="Stok Produk" subtitle="Gudang & Opname" />
            <x-menu-card href="{{ route('report.index') }}" color="purple" icon="chart-bar" title="Laporan" subtitle="Analisa Keuangan" />
            <x-menu-card href="{{ route('payroll.index') }}" color="emerald" icon="cash" title="Gaji & Kasbon" subtitle="Payroll Karyawan" />
            <x-menu-card href="{{ route('finance.approval') }}" color="yellow" icon="check-circle" title="Approval" subtitle="Cek Request" />
            
            <x-menu-card href="{{ route('production.run') }}" color="orange" icon="cog" title="Produksi" subtitle="Input Hasil" />
            <x-menu-card href="{{ route('purchase.create') }}" color="indigo" icon="truck" title="Belanja" subtitle="Bahan Baku" />
            <x-menu-card href="{{ route('finance.debts') }}" color="pink" icon="book-open" title="Buku Utang" subtitle="Catatan Hutang" />
            <x-menu-card href="{{ route('attendance.index') }}" color="teal" icon="finger-print" title="Absensi" subtitle="Masuk & Pulang" />
            <x-menu-card href="{{ route('settings.index') }}" color="gray" icon="adjustments" title="Pengaturan" subtitle="Sistem & Akun" />

        </div>
    </div>
</div>

{{-- Helper Component untuk Menu Card (Inline Blade Component) --}}
@php
if(!function_exists('renderMenuIcon')) {
    function renderMenuIcon($icon) {
        $icons = [
            'shopping-cart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />',
            'cube' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />',
            'chart-bar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />',
            'cash' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
            'check-circle' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
            'cog' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
            'truck' => '<path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />',
            'book-open' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />',
            'finger-print' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.131A8 8 0 008 2.85M6.23 2.85C3.753 5.32 2.222 8.71 2.222 12.39a13.98 13.98 0 001.07 5.37M12 21a21.996 21.996 0 01-3.713-3.28" />',
            'adjustments' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />',
        ];
        return $icons[$icon] ?? '';
    }
}
@endphp