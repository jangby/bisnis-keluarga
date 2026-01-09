<div class="bg-gray-50/50 min-h-screen pb-20 font-sans">
    
    {{-- Header --}}
    <div class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-200">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center gap-3">
            <a href="{{ route('production.run') }}" wire:navigate class="p-2 rounded-full hover:bg-gray-100 text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">Riwayat Produksi</h1>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-6 space-y-6">
        
        {{-- SECTION FILTER --}}
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 space-y-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                
                {{-- Filter Tanggal Mulai --}}
                <div class="col-span-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase block mb-1">Dari Tanggal</label>
                    <input wire:model.live="startDate" type="date" class="w-full text-sm rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                {{-- Filter Tanggal Selesai --}}
                <div class="col-span-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase block mb-1">Sampai Tanggal</label>
                    <input wire:model.live="endDate" type="date" class="w-full text-sm rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                {{-- Filter Jenis --}}
                <div class="col-span-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase block mb-1">Jenis Data</label>
                    <select wire:model.live="filterType" class="w-full text-sm rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="goods">📦 Barang Jadi (Masuk)</option>
                        <option value="material">🧱 Bahan Baku (Keluar)</option>
                        <option value="">Semua Data</option>
                    </select>
                </div>

                {{-- Filter User --}}
                <div class="col-span-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase block mb-1">Oleh Akun</label>
                    <select wire:model.live="filterUser" class="w-full text-sm rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua User</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- LIST LOG --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-700 text-sm">Data Ditemukan</h3>
                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded-md">{{ $logs->total() }} Baris</span>
            </div>

            @forelse($logs as $log)
                @php
                    // Logika Tampilan Berdasarkan Tipe Log
                    $isGoods = $log->type == 'production_in';
                    $colorClass = $isGoods ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700';
                    $icon = $isGoods 
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>' // Icon Box
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>'; // Icon Bahan
                @endphp

                <div class="p-4 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition flex gap-4 items-start">
                    
                    {{-- Tanggal (Kotak Kiri) --}}
                    <div class="flex flex-col items-center justify-center w-12 h-12 bg-gray-100 rounded-xl text-gray-600 shrink-0">
                        <span class="text-[10px] font-bold uppercase">{{ $log->created_at->format('M') }}</span>
                        <span class="text-lg font-black">{{ $log->created_at->format('d') }}</span>
                    </div>

                    {{-- Detail Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <h3 class="text-sm font-bold text-gray-900 truncate pr-2">
                                {{ $log->product->name ?? 'Produk Terhapus' }}
                            </h3>
                            <span class="text-xs font-mono text-gray-400 whitespace-nowrap">
                                {{ $log->created_at->format('H:i') }}
                            </span>
                        </div>
                        
                        {{-- User & Note --}}
                        <div class="text-xs text-gray-500 mt-0.5 flex flex-wrap gap-1">
                            <span>Oleh: <b>{{ $log->user->name ?? 'Unknown' }}</b></span>
                            <span class="text-gray-300">•</span>
                            <span class="italic text-gray-400">{{ $log->notes }}</span>
                        </div>
                    </div>

                    {{-- Jumlah & Badge --}}
                    <div class="text-right shrink-0">
                        <div class="flex flex-col items-end">
                            <span class="font-black text-sm {{ $isGoods ? 'text-green-600' : 'text-red-600' }}">
                                {{ $isGoods ? '+' : '-' }}{{ number_format(abs($log->quantity)) }}
                            </span>
                            <span class="text-[10px] font-bold text-gray-400">{{ $log->product->unit ?? 'Unit' }}</span>
                        </div>
                        <div class="mt-1">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full {{ $colorClass }}">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $icon !!}
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <p class="text-sm">Tidak ada data dengan filter ini.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>