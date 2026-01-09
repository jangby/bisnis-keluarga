<div class="bg-gray-50/50 min-h-screen pb-20 font-sans">
    
    {{-- Header --}}
    <div class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-200">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center gap-3">
            <a href="{{ route('dashboard') }}" wire:navigate class="p-2 rounded-full hover:bg-gray-100 text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">Log Barang Terjual</h1>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-6 space-y-6">
        
        {{-- SECTION FILTER --}}
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 space-y-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                
                {{-- Tanggal --}}
                <div class="col-span-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase block mb-1">Dari</label>
                    <input wire:model.live="startDate" type="date" class="w-full text-sm rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>
                <div class="col-span-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase block mb-1">Sampai</label>
                    <input wire:model.live="endDate" type="date" class="w-full text-sm rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>

                {{-- User --}}
                <div class="col-span-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase block mb-1">Kasir</label>
                    <select wire:model.live="filterUser" class="w-full text-sm rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500">
                        <option value="">Semua</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Cari Barang --}}
                <div class="col-span-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase block mb-1">Cari Barang</label>
                    <input wire:model.live.debounce.500ms="search" type="text" placeholder="Nama produk..." class="w-full text-sm rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>
            </div>
        </div>

        {{-- LIST LOG --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-700 text-sm">Rincian Penjualan</h3>
                <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-md">{{ $items->total() }} Item</span>
            </div>

            @forelse($items as $item)
                <div class="p-4 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition flex gap-4 items-start">
                    
                    {{-- Tanggal (Kotak Kiri) --}}
                    <div class="flex flex-col items-center justify-center w-12 h-12 bg-green-50 rounded-xl text-green-700 shrink-0">
                        <span class="text-[10px] font-bold uppercase">{{ $item->created_at->format('M') }}</span>
                        <span class="text-lg font-black">{{ $item->created_at->format('d') }}</span>
                    </div>

                    {{-- Detail Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <h3 class="text-sm font-bold text-gray-900 truncate pr-2">
                                {{ $item->product_name }}
                            </h3>
                            <span class="text-xs font-mono text-gray-400 whitespace-nowrap">
                                {{ $item->created_at->format('H:i') }}
                            </span>
                        </div>
                        
                        {{-- Info Kasir & Harga Satuan --}}
                        <div class="text-xs text-gray-500 mt-1 flex flex-wrap gap-2 items-center">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ $item->order->user->name ?? 'Sistem' }}
                            </span>
                            <span class="text-gray-300">|</span>
                            <span>@ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Jumlah & Total --}}
                    <div class="text-right shrink-0">
                        <div class="flex flex-col items-end">
                            <span class="font-black text-sm text-gray-800">
                                {{ $item->quantity }} Pcs
                            </span>
                            <span class="text-[10px] font-bold text-green-600 mt-0.5">
                                + Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <p class="text-sm">Belum ada barang terjual di tanggal ini.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </div>
</div>