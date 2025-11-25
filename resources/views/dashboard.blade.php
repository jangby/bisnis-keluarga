<x-app-layout>

    <div class="space-y-6 pb-10">
        
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

        @if(Auth::user()->role === 'owner')
            <div>
                <h3 class="font-bold text-gray-800 text-sm mb-4 px-1">Menu Utama</h3>
                
                <div class="grid grid-cols-4 gap-y-6 gap-x-2">
                    
                    {{-- 1. KASIR --}}
                    <a href="{{ route('pos.index') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center shadow-sm group-hover:bg-green-600 group-hover:text-white group-hover:shadow-green-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-green-600 text-center leading-tight">Kasir<br>Jualan</span>
                    </a>

                    {{-- 2. STOK GUDANG --}}
                    <a href="{{ route('products.index') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-blue-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-blue-600 text-center leading-tight">Stok<br>Produk</span>
                    </a>

                    {{-- 3. PRODUKSI --}}
                    <a href="{{ route('production.run') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shadow-sm group-hover:bg-orange-600 group-hover:text-white group-hover:shadow-orange-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-orange-600 text-center leading-tight">Input<br>Produksi</span>
                    </a>

                    {{-- 4. LAPORAN --}}
                    <a href="{{ route('report.index') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shadow-sm group-hover:bg-purple-600 group-hover:text-white group-hover:shadow-purple-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-purple-600 text-center leading-tight">Laporan<br>Keuangan</span>
                    </a>
                    
                    {{-- 5. UTANG / KASBON --}}
                    <a href="{{ route('finance.debts') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center shadow-sm group-hover:bg-pink-600 group-hover:text-white group-hover:shadow-pink-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M15 2H9a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1Z"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-pink-600 text-center leading-tight">Buku<br>Utang</span>
                    </a>

                    {{-- 6. APPROVAL --}}
                    <a href="{{ route('finance.approval') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-yellow-50 text-yellow-600 flex items-center justify-center shadow-sm group-hover:bg-yellow-500 group-hover:text-white group-hover:shadow-yellow-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.78 4.78 4 4 0 0 1-6.74 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-yellow-600 text-center leading-tight">Cek<br>Request</span>
                    </a>

                    <a href="{{ route('purchase.create') }}" wire:navigate class="group flex flex-col items-center gap-2">
    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm group-hover:bg-indigo-600 group-hover:text-white group-hover:shadow-indigo-500/30 group-hover:shadow-lg transition-all duration-300">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
    </div>
    <span class="text-[11px] font-semibold text-gray-600 group-hover:text-indigo-600 text-center leading-tight">Belanja<br>Bahan</span>
</a>
{{-- MENU BARU: KELOLA WEB --}}
<a href="{{ route('web.manage') }}" wire:navigate class="group flex flex-col items-center gap-2">
    <div class="w-14 h-14 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center shadow-sm group-hover:bg-pink-600 group-hover:text-white group-hover:shadow-pink-500/30 group-hover:shadow-lg transition-all duration-300">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-9 3-9m-9 9c-1.657 0-3-9-3-9" />
        </svg>
    </div>
    <span class="text-[11px] font-semibold text-gray-600 group-hover:text-pink-600 text-center leading-tight">Kelola<br>Web</span>
</a>
{{-- MENU BARU: MANAJEMEN PESANAN --}}
<a href="{{ route('orders.manage') }}" wire:navigate class="group flex flex-col items-center gap-2">
    <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shadow-sm group-hover:bg-orange-600 group-hover:text-white group-hover:shadow-orange-500/30 group-hover:shadow-lg transition-all duration-300">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
    </div>
    <span class="text-[11px] font-semibold text-gray-600 group-hover:text-orange-600 text-center leading-tight">Order<br>Masuk</span>
</a>

                    {{-- 8. EMPTY (Atau Tambahan Lain Nanti) --}}
                     <a href="{{ route('activity.index') }}" wire:navigate class="group flex flex-col items-center gap-2">
    <div class="w-14 h-14 rounded-2xl bg-gray-800 text-white flex items-center justify-center shadow-sm group-hover:bg-black transition-all duration-300">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    </div>
    <span class="text-[11px] font-semibold text-gray-600 group-hover:text-black text-center leading-tight">Log<br>Aktivitas</span>
</a>

                    {{-- 7. SETTING --}}
                    <a href="{{ route('settings.index') }}" wire:navigate class="group flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-600 flex items-center justify-center shadow-sm group-hover:bg-gray-800 group-hover:text-white group-hover:shadow-gray-500/30 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-1-1.72v-.51a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 group-hover:text-gray-800 text-center leading-tight">Setting<br>Akun</span>
                    </a>

                </div>
            </div>
        @endif

        {{-- 
            UNTUK ROLE LAIN (Marketing & Produksi)
            Tampilan tetap simpel sesuai tugas mereka masing-masing
        --}}

        @if(Auth::user()->role === 'marketing')
            <div>
                 <h3 class="font-bold text-gray-800 text-sm mb-3 px-1">Menu Kasir</h3>
                 <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('pos.index') }}" wire:navigate class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center gap-3 hover:border-green-500 hover:shadow-md transition group">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 group-hover:scale-110 transition">
                             <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        </div>
                        <span class="font-bold text-gray-800">Buka Kasir</span>
                    </a>
                    <a href="{{ route('products.index') }}" wire:navigate class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center gap-3 hover:border-blue-500 hover:shadow-md transition group">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>
                        </div>
                        <span class="font-bold text-gray-800">Cek Stok</span>
                    </a>
                </div>
            </div>
        @endif

        @if(Auth::user()->role === 'production')
            <div>
                 <h3 class="font-bold text-gray-800 text-sm mb-3 px-1">Menu Produksi</h3>
                 <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('production.run') }}" wire:navigate class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center gap-3 hover:border-orange-500 hover:shadow-md transition group">
                        <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                        </div>
                        <span class="font-bold text-gray-800">Input Produksi</span>
                    </a>
                    <a href="{{ route('production.request') }}" wire:navigate class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center gap-3 hover:border-red-500 hover:shadow-md transition group">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M15 2H9a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1Z"/></svg>
                        </div>
                        <span class="font-bold text-gray-800">Request Bahan</span>
                    </a>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h4 class="font-bold text-sm text-gray-700">Aktivitas Terakhir</h4>
                <a href="{{ route('finance.history') }}" wire:navigate class="text-xs text-blue-600 font-medium hover:underline">
        Lihat Semua Aktivitas &rarr;
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
                                        <span>•</span>
                                        <span class="uppercase tracking-wide">{{ $trx->product_line->name ?? 'Umum' }}</span>
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
                        <p class="text-xs">Belum ada transaksi tercatat.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>