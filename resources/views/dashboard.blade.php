<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Halo, {{ Auth::user()->name }}! 👋
                </h2>
                <p class="text-xs text-gray-500 mt-1 capitalize">Posisi: {{ Auth::user()->role }}</p>
            </div>
            <!-- Logout Button Kecil di Header -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </x-slot>

    <div class="space-y-4">
        
        <!-- 1. AREA KHUSUS OWNER & FINANCE (Lihat Saldo) -->
        @if(in_array(Auth::user()->role, ['owner', 'finance']))
            <div class="bg-blue-600 rounded-xl p-6 text-white shadow-lg relative overflow-hidden">
                <!-- Hiasan background -->
                <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-white/10"></div>
                <div class="absolute -left-6 -bottom-6 w-16 h-16 rounded-full bg-white/10"></div>
                
                <p class="text-sm opacity-80 relative z-10">Total Saldo (Kas + Bank)</p>
                
                <h3 class="text-3xl font-bold mt-1 relative z-10">
                    Rp {{ number_format($totalSaldo ?? 0, 0, ',', '.') }}
                </h3>

                <!-- Tombol Aksi Cepat -->
                <div class="mt-4 flex gap-2 relative z-10">
                    <a href="{{ route('finance.create', ['type' => 'income']) }}" class="bg-white/20 text-center text-sm px-4 py-2 rounded-lg hover:bg-white/30 w-full cursor-pointer border border-white/10 flex items-center justify-center gap-1">
                        <span>⬇️</span> Masuk
                    </a>
                    <a href="{{ route('finance.create', ['type' => 'expense']) }}" class="bg-white/20 text-center text-sm px-4 py-2 rounded-lg hover:bg-white/30 w-full cursor-pointer border border-white/10 flex items-center justify-center gap-1">
                        <span>⬆️</span> Keluar
                    </a>
                </div>

                <!-- TOMBOL BARU: CEK PERMINTAAN (Khusus Keuangan) -->
                @if(Auth::user()->role === 'finance')
                    <div class="mt-2 relative z-10">
                        <a href="{{ route('finance.approval') }}" class="block bg-yellow-500 text-yellow-900 text-center text-sm px-4 py-2 rounded-lg hover:bg-yellow-400 font-bold shadow-md">
                            🔔 Cek Permintaan Belanja
                        </a>
                    </div>
                @endif
            </div>
        @endif

        <!-- 2. AREA KHUSUS MARKETING (Kasir) -->
        @if(Auth::user()->role === 'marketing')
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('pos.index') }}" wire:navigate class="bg-green-100 p-4 rounded-xl border border-green-200 flex flex-col items-center justify-center gap-2 hover:bg-green-200 transition cursor-pointer h-32">
                    <span class="text-4xl">🛒</span>
                    <span class="font-bold text-green-800">Kasir Baru</span>
                </a>
                <a href="{{ route('products.index') }}" wire:navigate class="bg-purple-100 p-4 rounded-xl border border-purple-200 flex flex-col items-center justify-center gap-2 hover:bg-purple-200 transition cursor-pointer h-32">
                    <span class="text-4xl">📦</span>
                    <span class="font-bold text-purple-800">Cek Stok</span>
                </a>
            </div>
        @endif

        <!-- 3. AREA KHUSUS PRODUKSI (TOMBOL BARU) -->
        @if(Auth::user()->role === 'production')
            <div class="grid grid-cols-2 gap-4">
                <!-- Tombol Request Bahan -->
                <a href="{{ route('production.request') }}" wire:navigate class="bg-orange-100 p-4 rounded-xl border border-orange-200 flex flex-col items-center justify-center gap-2 hover:bg-orange-200 transition cursor-pointer h-32">
                    <span class="text-4xl">📝</span>
                    <span class="font-bold text-orange-800 text-center">Ajukan Bahan</span>
                </a>
                
                <!-- Tombol Manajemen Produk -->
                <a href="{{ route('products.index') }}" wire:navigate class="bg-blue-100 p-4 rounded-xl border border-blue-200 flex flex-col items-center justify-center gap-2 hover:bg-blue-200 transition cursor-pointer h-32">
                    <span class="text-4xl">🏭</span>
                    <span class="font-bold text-blue-800 text-center">Stok Gudang</span>
                </a>

                <a href="{{ route('production.run') }}" wire:navigate class="bg-indigo-100 p-4 rounded-xl border border-indigo-200 flex flex-col items-center justify-center gap-2 hover:bg-indigo-200 transition cursor-pointer h-32">
    <span class="text-4xl">⚙️</span>
    <span class="font-bold text-indigo-800 text-center">Input Produksi</span>
</a>
            </div>
        @endif

        <!-- 4. AKTIVITAS TERAKHIR (Semua User Bisa Lihat) -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
            <div class="p-4 text-gray-900">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-bold text-sm text-gray-500 uppercase">Aktivitas Terakhir</h4>
                    <a href="{{ route('report.index') }}" class="text-xs text-blue-600 font-medium">Lihat Laporan &rarr;</a>
                </div>
                
                <div class="space-y-3">
                    @if(isset($transactions) && count($transactions) > 0)
                        @foreach($transactions as $trx)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-100 hover:bg-gray-100 transition">
                                <div class="flex items-center gap-3">
                                    <!-- Icon Kategori -->
                                    <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold {{ $trx->type == 'income' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                        {{ substr($trx->category, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-800 line-clamp-1">{{ $trx->category }}</p>
                                        <div class="flex gap-2 text-[10px] text-gray-500 mt-0.5">
                                            <span>{{ $trx->transaction_date->format('d M') }}</span>
                                            <span>•</span>
                                            <span class="uppercase">{{ $trx->product_line->name ?? 'Umum' }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <span class="font-bold text-sm whitespace-nowrap {{ $trx->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $trx->type == 'income' ? '+' : '-' }} 
                                    {{ number_format($trx->amount, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-gray-400 text-sm">
                            Belum ada transaksi.
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-app-layout>