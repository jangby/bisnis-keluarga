<x-app-layout>

    <div class="space-y-6 pb-10">
        
        {{-- ========================================================== --}}
        {{-- 1. WIDGET KEUANGAN (Hanya Owner & Finance)                 --}}
        {{-- ========================================================== --}}
        @if(in_array(Auth::user()->role, ['owner', 'finance']))
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-6 text-white shadow-xl shadow-blue-500/20 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute -left-6 -bottom-6 w-20 h-20 rounded-full bg-white/10 blur-xl"></div>
                
                <div class="relative z-10">
                    <p class="text-xs text-blue-100 font-medium uppercase tracking-wider mb-1">Total Aset Keuangan</p>
                    <h3 class="text-3xl font-extrabold tracking-tight">
                        Rp {{ number_format($totalSaldo ?? 0, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3 relative z-10">
                    <a href="{{ route('finance.create', ['type' => 'income']) }}" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/10 text-white py-2.5 rounded-xl flex items-center justify-center gap-2 transition active:scale-95">
                        <div class="bg-green-400/20 p-1 rounded-full">
                            <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-sm font-bold">Pemasukan</span>
                    </a>
                    <a href="{{ route('finance.create', ['type' => 'expense']) }}" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/10 text-white py-2.5 rounded-xl flex items-center justify-center gap-2 transition active:scale-95">
                        <div class="bg-red-400/20 p-1 rounded-full">
                            <svg class="w-4 h-4 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/></svg>
                        </div>
                        <span class="text-sm font-bold">Pengeluaran</span>
                    </a>
                </div>
            </div>
        @endif

        {{-- ========================================================== --}}
        {{-- 2. MENU OWNER (LENGKAP - 14 MENU)                          --}}
        {{-- ========================================================== --}}
        @if(Auth::user()->role === 'owner')
            <div>
                <h3 class="font-bold text-gray-800 text-sm mb-4 px-1">Menu Utama</h3>
                
                <div class="grid grid-cols-4 gap-y-6 gap-x-2">
                    
                    {{-- 1. KASIR --}}
                    <a href="{{ route('pos.index') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center shadow-sm group-hover:bg-green-600 group-hover:text-white group-hover:shadow-green-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-green-600 text-center leading-tight">Kasir</span>
                    </a>

                    {{-- 2. STOK --}}
                    <a href="{{ route('products.index') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-blue-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-blue-600 text-center leading-tight">Stok</span>
                    </a>

                    {{-- 3. PRODUKSI --}}
                    <a href="{{ route('production.run') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shadow-sm group-hover:bg-orange-600 group-hover:text-white group-hover:shadow-orange-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-orange-600 text-center leading-tight">Produksi</span>
                    </a>

                    {{-- 4. LAPORAN --}}
                    <a href="{{ route('report.index') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shadow-sm group-hover:bg-purple-600 group-hover:text-white group-hover:shadow-purple-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-purple-600 text-center leading-tight">Laporan</span>
                    </a>
                    
                    {{-- 5. UTANG / KASBON --}}
                    <a href="{{ route('finance.debts') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center shadow-sm group-hover:bg-pink-600 group-hover:text-white group-hover:shadow-pink-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M15 2H9a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1Z"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-pink-600 text-center leading-tight">Utang</span>
                    </a>

                    {{-- 6. APPROVAL --}}
                    <a href="{{ route('finance.approval') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-yellow-50 text-yellow-600 flex items-center justify-center shadow-sm group-hover:bg-yellow-500 group-hover:text-white group-hover:shadow-yellow-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.78 4.78 4 4 0 0 1-6.74 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-yellow-600 text-center leading-tight">Request</span>
                    </a>

                    {{-- 7. BELANJA BAHAN --}}
                    <a href="{{ route('purchase.create') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm group-hover:bg-indigo-600 group-hover:text-white group-hover:shadow-indigo-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-indigo-600 text-center leading-tight">Belanja</span>
                    </a>

                    {{-- 8. KELOLA WEB --}}
                    <a href="{{ route('web.manage') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center shadow-sm group-hover:bg-pink-600 group-hover:text-white group-hover:shadow-pink-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-9 3-9m-9 9c-1.657 0-3-9-3-9" />
                            </svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-pink-600 text-center leading-tight">Web</span>
                    </a>

                    {{-- 9. ORDER MASUK --}}
                    <a href="{{ route('orders.manage') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shadow-sm group-hover:bg-orange-600 group-hover:text-white group-hover:shadow-orange-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-orange-600 text-center leading-tight">Order<br>Masuk</span>
                    </a>

                    {{-- 10. LOG AKTIVITAS --}}
                    <a href="{{ route('activity.index') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-gray-800 text-white flex items-center justify-center shadow-sm group-hover:bg-black transition-all duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-black text-center leading-tight">Log<br>Aktivitas</span>
                    </a>

                    {{-- 11. ABSENSI (OWNER) --}}
                    <a href="{{ route('attendance.index') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm group-hover:bg-teal-600 group-hover:text-white group-hover:shadow-teal-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-teal-600 text-center leading-tight">Absen</span>
                    </a>

                    {{-- 12. DATA ABSENSI --}}
                    <a href="{{ route('attendance.monitoring') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm group-hover:bg-indigo-600 group-hover:text-white group-hover:shadow-indigo-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-indigo-600 text-center leading-tight">Data<br>Absen</span>
                    </a>

                    {{-- MENU BARU: SLIP GAJI --}}
<a href="{{ route('payroll.index') }}" wire:navigate class="group flex flex-col items-center gap-2">
    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-sm group-hover:bg-emerald-600 group-hover:text-white group-hover:shadow-emerald-500/30 group-hover:shadow-lg transition-all duration-300">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <span class="text-[11px] font-semibold text-gray-600 group-hover:text-emerald-600 text-center leading-tight">Slip<br>Gaji</span>
</a>

                    {{-- 13. SETTING LOKASI --}}
                    <a href="{{ route('attendance.settings') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center shadow-sm group-hover:bg-red-600 group-hover:text-white group-hover:shadow-red-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-red-600 text-center leading-tight">Lokasi</span>
                    </a>

                    {{-- 14. SETTING AKUN --}}
                    <a href="{{ route('settings.index') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-600 flex items-center justify-center shadow-sm group-hover:bg-gray-800 group-hover:text-white group-hover:shadow-gray-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-1-1.72v-.51a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-gray-800 text-center leading-tight">Setting</span>
                    </a>

                </div>
            </div>
        @endif

        {{-- ========================================================== --}}
        {{-- MENU DASHBOARD KHUSUS MARKETING (PENJUALAN)                --}}
        {{-- ========================================================== --}}
        @if(Auth::user()->role === 'marketing')
            <div class="space-y-6">
                
                {{-- 1. Statistik Omset & Order Hari Ini --}}
                <div class="grid grid-cols-2 gap-4">
                    {{-- Card Omset --}}
                    <div class="col-span-2 sm:col-span-1 bg-gradient-to-r from-green-500 to-green-600 p-5 rounded-2xl shadow-lg shadow-green-500/30 text-white relative overflow-hidden">
                        <div class="relative z-10">
                            <p class="text-xs text-green-100 font-bold uppercase tracking-wider mb-1">Omset Hari Ini</p>
                            <h3 class="text-3xl font-extrabold tracking-tight">
                                Rp {{ number_format($todayRevenue ?? 0, 0, ',', '.') }}
                            </h3>
                            <p class="text-[10px] text-green-100 mt-1 opacity-90">{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                        </div>
                        {{-- Hiasan Background --}}
                        <div class="absolute right-0 bottom-0 opacity-20 transform translate-x-4 translate-y-4">
                            <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>

                    {{-- Card Jumlah Order --}}
                    <div class="bg-blue-50 p-5 rounded-2xl border border-blue-100 flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <span class="text-xs font-bold text-gray-500 uppercase">Order Selesai</span>
                        </div>
                        <span class="text-2xl font-black text-blue-700">{{ $todayOrdersCount ?? 0 }} <span class="text-sm font-medium text-blue-500">Transaksi</span></span>
                    </div>

                    {{-- Card Absensi Quick --}}
                    <a href="{{ route('attendance.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 flex flex-col justify-center hover:border-indigo-500 transition group">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="text-xs font-bold text-gray-500 uppercase">Kehadiran</span>
                        </div>
                        <span class="text-sm font-bold text-indigo-700">Absen Sekarang &rarr;</span>
                    </a>
                </div>

                {{-- 2. Menu Utama (Tombol Besar) --}}
                <div>
                    <h3 class="font-bold text-gray-800 text-sm mb-3 px-1">Menu Penjualan</h3>
                    <div class="grid grid-cols-1 gap-4">
                        {{-- Tombol Kasir --}}
                        <a href="{{ route('pos.index') }}" class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4 hover:shadow-md hover:border-green-500 transition group">
                            <div class="h-14 w-14 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0 group-hover:bg-green-600 group-hover:text-white transition">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-800">Buka Kasir (POS)</h4>
                                <p class="text-xs text-gray-500">Input penjualan baru di sini</p>
                            </div>
                            <div class="ml-auto text-gray-300 group-hover:text-green-600 group-hover:translate-x-1 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>

                        {{-- Tombol Log Penjualan (Arah ke Log Barang/Item) --}}
<a href="{{ route('orders.history') }}" wire:navigate class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4 hover:shadow-md hover:border-blue-500 transition group">
    <div class="h-14 w-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition">
        {{-- Ikon List/Catatan --}}
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    </div>
    <div>
        <h4 class="text-lg font-bold text-gray-800">Log Penjualan</h4>
        <p class="text-xs text-gray-500">Lihat rincian barang terjual</p>
    </div>
    <div class="ml-auto text-gray-300 group-hover:text-blue-600 group-hover:translate-x-1 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </div>
</a>
                    </div>
                </div>

                {{-- 3. Log Penjualan Terakhir (Timeline) --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
                        <h3 class="font-bold text-gray-700 text-sm">Penjualan Terakhir Hari Ini</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($recentOrders as $order)
                            <div class="p-4 flex items-center gap-4 hover:bg-gray-50 transition">
                                <div class="flex flex-col items-center min-w-[50px]">
                                    <span class="text-sm font-bold text-gray-800">{{ $order->created_at->format('H:i') }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-gray-800 text-sm">Order #{{ $order->id }}</h4>
                                    <p class="text-xs text-gray-500 truncate">Pelanggan: {{ $order->customer_name ?? 'Umum' }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="block font-bold text-green-600 text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">{{ $order->payment_method ?? 'Cash' }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center text-gray-400">
                                <p class="text-sm">Belum ada penjualan hari ini.</p>
                                <a href="{{ route('pos.index') }}" class="text-xs text-green-600 font-bold hover:underline">Mulai Jualan &rarr;</a>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        @endif


        {{-- ========================================================== --}}
        {{-- MENU DASHBOARD KHUSUS INVENTORY (GUDANG)                   --}}
        {{-- ========================================================== --}}
        @if(Auth::user()->role === 'inventory')
            <div class="space-y-6">
                
                {{-- Statistik Stok --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-purple-50 p-5 rounded-2xl border border-purple-100 flex flex-col justify-center">
                        <span class="text-xs font-bold text-purple-600 uppercase">Total Bahan</span>
                        <span class="text-3xl font-black text-purple-700 mt-1">{{ $totalMaterials ?? 0 }}</span>
                    </div>
                    <div class="bg-blue-50 p-5 rounded-2xl border border-blue-100 flex flex-col justify-center">
                        <span class="text-xs font-bold text-blue-600 uppercase">Total Produk</span>
                        <span class="text-3xl font-black text-blue-700 mt-1">{{ $totalProducts ?? 0 }}</span>
                    </div>
                </div>

                {{-- Alert Stok Menipis --}}
                @if(isset($lowStockItems) && count($lowStockItems) > 0)
                    <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden">
                        <div class="bg-red-50 px-4 py-3 border-b border-red-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <h3 class="text-sm font-bold text-red-700">Perhatian: Stok Menipis!</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($lowStockItems as $item)
                                <div class="px-4 py-3 flex justify-between items-center hover:bg-gray-50">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                            {{ substr($item->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">{{ $item->name }}</p>
                                            <p class="text-xs text-gray-400">Kode: {{ $item->code }}</p>
                                        </div>
                                    </div>
                                    <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-md">
                                        Sisa: {{ $item->current_stock }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Menu Aksi --}}
                <h3 class="font-bold text-gray-800 text-sm px-1">Manajemen Gudang</h3>
                <div class="grid grid-cols-1 gap-4">
                    <a href="{{ route('products.index') }}" wire:navigate class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4 hover:border-purple-500 transition group">
                        <div class="h-12 w-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-sm">Lihat Semua Stok</h4>
                            <p class="text-xs text-gray-500">Cek jumlah, tambah, atau edit barang</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-300 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="{{ route('attendance.index') }}" wire:navigate class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4 hover:border-indigo-500 transition group">
                        <div class="h-12 w-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-sm">Absensi</h4>
                            <p class="text-xs text-gray-500">Catat kehadiran kerja</p>
                        </div>
                    </a>
                </div>
            </div>
        @endif

        {{-- ========================================================== --}}
        {{-- MENU DASHBOARD KHUSUS PRODUKSI (DESAIN BARU)               --}}
        {{-- ========================================================== --}}
        @if(Auth::user()->role === 'production')
            <div>
                {{-- 1. Statistik Ringkas & Alert Stok (Agar informatif) --}}
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <div class="bg-orange-50 p-3 rounded-2xl border border-orange-100 flex flex-col items-center justify-center text-center">
                        <span class="text-xl font-black text-orange-600">{{ $totalMaterials ?? 0 }}</span>
                        <span class="text-[10px] uppercase font-bold text-orange-400 mt-1">Bahan</span>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-2xl border border-blue-100 flex flex-col items-center justify-center text-center">
                        <span class="text-xl font-black text-blue-600">{{ $totalProducts ?? 0 }}</span>
                        <span class="text-[10px] uppercase font-bold text-blue-400 mt-1">Produk</span>
                    </div>
                    <div class="bg-red-50 p-3 rounded-2xl border border-red-100 flex flex-col items-center justify-center text-center">
                        <span class="text-xl font-black text-red-600">{{ isset($lowStockItems) ? count($lowStockItems) : 0 }}</span>
                        <span class="text-[10px] uppercase font-bold text-red-400 mt-1">Alert</span>
                    </div>
                </div>

                {{-- 2. Tampilkan Detail Barang Stok Menipis (Jika Ada) --}}
                @if(isset($lowStockItems) && count($lowStockItems) > 0)
                    <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden mb-6">
                        <div class="bg-red-50 px-4 py-2 border-b border-red-100 flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <h3 class="text-xs font-bold text-red-700">Perhatian: Stok Menipis!</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($lowStockItems as $item)
                                <div class="px-4 py-2 flex justify-between items-center">
                                    <span class="text-xs font-bold text-gray-800">{{ $item->name }}</span>
                                    <span class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-md">Sisa: {{ $item->current_stock }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- 3. Menu Aksi Cepat (Tombol Besar) --}}
                <h3 class="font-bold text-gray-800 text-sm mb-3 px-1">Menu Produksi</h3>
                <div class="grid grid-cols-2 gap-3">
                
                    {{-- Input Produksi (Tombol Paling Besar & Menonjol) --}}
                    <a href="{{ route('production.run') }}" wire:navigate class="col-span-2 bg-gradient-to-br from-orange-500 to-orange-600 p-5 rounded-2xl shadow-lg shadow-orange-500/30 text-white flex items-center justify-between group active:scale-[0.98] transition-all">
                        <div class="flex flex-col">
                            <span class="text-lg font-bold">Input Produksi</span>
                            <span class="text-xs text-orange-100 opacity-90">Catat hasil produksi sekarang</span>
                        </div>
                        <div class="h-10 w-10 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                    </a>

                    {{-- Request Bahan --}}
                    <a href="{{ route('production.request') }}" wire:navigate class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col gap-2 hover:border-red-500 transition">
                         <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M15 2H9a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1Z"/></svg>
                         </div>
                         <span class="font-bold text-xs text-gray-800">Req Bahan</span>
                    </a>

                    {{-- Cek Stok --}}
                    <a href="{{ route('products.index') }}" wire:navigate class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col gap-2 hover:border-blue-500 transition">
                         <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>
                         </div>
                         <span class="font-bold text-xs text-gray-800">Cek Stok</span>
                    </a>

                    {{-- Absensi --}}
                    <a href="{{ route('attendance.index') }}" wire:navigate class="col-span-2 bg-indigo-50 p-3 rounded-2xl border border-indigo-100 flex items-center gap-3 hover:bg-indigo-100 transition">
                        <div class="h-8 w-8 rounded-full bg-white text-indigo-600 flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="font-bold text-xs text-indigo-900">Absensi Kehadiran</span>
                    </a>
                </div>
            </div>
        @endif

        {{-- ========================================================== --}}
        {{-- 4. MENU KARYAWAN UMUM / STAFF (YANG HILANG TOTAL)          --}}
        {{-- ========================================================== --}}
        
        @if(!in_array(Auth::user()->role, ['owner', 'marketing', 'production', 'finance']))
            <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
                
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Halo, {{ Auth::user()->name }}! 👋</h2>
                <p class="text-gray-500 mb-8 max-w-xs">Silakan lakukan absensi kehadiran atau pengajuan izin melalui menu di bawah ini.</p>

                <a href="{{ route('attendance.index') }}" wire:navigate class="group relative w-64 h-64 bg-white rounded-3xl shadow-xl border border-gray-100 flex flex-col items-center justify-center gap-4 hover:scale-105 transition-all duration-300 overflow-hidden">
                    
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-50 to-transparent opacity-50 group-hover:opacity-100 transition"></div>
                    
                    <div class="relative z-10 w-24 h-24 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center shadow-sm group-hover:bg-teal-600 group-hover:text-white group-hover:shadow-teal-500/30 transition-all duration-300">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    
                    <div class="relative z-10">
                        <h3 class="text-xl font-bold text-gray-800 group-hover:text-teal-600 transition">Absensi Harian</h3>
                        <p class="text-xs text-gray-400 mt-1">Klik untuk Masuk / Pulang</p>
                    </div>
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="px-6 py-2 bg-red-50 text-red-600 font-bold rounded-full hover:bg-red-100 transition shadow-sm border border-red-100 text-xs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar / Logout
                    </button>
                </form>

                <p class="mt-8 text-xs text-gray-400">
                    &copy; {{ date('Y') }} Sistem Bisnis Keluarga
                </p>
            </div>
        @endif
        
        {{-- ========================================================== --}}
        {{-- 5. LOG AKTIVITAS (Hanya Owner)                             --}}
        {{-- ========================================================== --}}
        @if(Auth::user()->role === 'owner')
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h4 class="font-bold text-sm text-gray-700">Aktivitas Terakhir</h4>
                    <a href="{{ route('finance.history') }}" wire:navigate class="text-xs text-blue-600 font-medium hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @if(isset($transactions) && count($transactions) > 0)
                        @foreach($transactions as $trx)
                            <div class="flex justify-between items-center p-4 hover:bg-gray-50 transition duration-150">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-full flex items-center justify-center text-sm font-bold shadow-sm {{ $trx->type == 'income' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                        @if($trx->type == 'income')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm mb-0.5">{{ $trx->category }}</p>
                                        <div class="flex gap-2 text-[11px] text-gray-400">
                                            <span>{{ $trx->transaction_date->format('d M, H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <span class="font-black text-sm {{ $trx->type == 'income' ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $trx->type == 'income' ? '+' : '-' }} 
                                    {{ number_format($trx->amount, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                            <svg class="w-12 h-12 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="text-xs">Belum ada aktivitas.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div>
</x-app-layout>