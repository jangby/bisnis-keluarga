<div class="min-h-screen bg-gray-50/50 pb-20 font-sans">

    {{-- HEADER --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Request Bahan Baku</h1>
                    <p class="text-xs text-gray-500">Ajukan permintaan stok ke gudang pusat/owner</p>
                </div>
                
                {{-- Indikator Cart Mobile --}}
                <div class="md:hidden">
                    <span class="bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1 rounded-full border border-orange-200">
                        {{ count($cart) }} Item Dipilih
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col lg:flex-row gap-6">

            {{-- BAGIAN KIRI: DAFTAR BAHAN BAKU --}}
            <div class="flex-1">
                {{-- Search (Optional Visual Only karena filter di controller belum ada search live) --}}
                {{-- <div class="mb-4">
                    <input type="text" placeholder="Cari bahan..." class="w-full rounded-xl border-gray-300 text-sm focus:ring-orange-500 focus:border-orange-500">
                </div> --}}

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- PERBAIKAN: Menggunakan $materials, bukan $products --}}
                    @forelse($materials as $material)
                        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm hover:border-orange-400 transition group relative">
                            
                            <div class="flex justify-between items-start mb-2">
                                <div class="h-10 w-10 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-lg">
                                    {{ substr($material->name, 0, 1) }}
                                </div>
                                <span class="text-[10px] font-bold bg-gray-100 text-gray-500 px-2 py-1 rounded">
                                    Stok: {{ $material->current_stock }} {{ $material->unit }}
                                </span>
                            </div>

                            <h3 class="font-bold text-gray-900 text-sm mb-1 truncate">{{ $material->name }}</h3>
                            <p class="text-xs text-gray-400 mb-4">{{ $material->code }}</p>

                            {{-- Tombol Tambah --}}
                            <button wire:click="addToCart({{ $material->id }})" 
                                class="w-full py-2 bg-orange-50 text-orange-700 font-bold text-xs rounded-lg hover:bg-orange-600 hover:text-white transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah ke List
                            </button>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-10 text-gray-400">
                            <p>Tidak ada data bahan baku.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- BAGIAN KANAN: KERANJANG REQUEST (STICKY) --}}
            <div class="lg:w-96 shrink-0">
                <div class="bg-white rounded-2xl shadow-lg border border-orange-100 sticky top-24 overflow-hidden">
                    <div class="p-4 bg-orange-50 border-b border-orange-100 flex justify-between items-center">
                        <h3 class="font-bold text-orange-800 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Daftar Permintaan
                        </h3>
                        <span class="bg-white text-orange-600 text-xs font-bold px-2 py-0.5 rounded-md border border-orange-200">
                            {{ count($cart) }} Item
                        </span>
                    </div>

                    <div class="p-4 space-y-4 max-h-[60vh] overflow-y-auto">
                        @if(empty($cart))
                            <div class="text-center py-8 text-gray-400 border-2 border-dashed border-gray-100 rounded-xl">
                                <p class="text-xs">Belum ada item dipilih</p>
                            </div>
                        @else
                            @foreach($cart as $id => $item)
                                <div class="flex items-center gap-3 bg-white border border-gray-100 p-2 rounded-xl shadow-sm">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-xs text-gray-800 truncate">{{ $item['name'] }}</h4>
                                        <p class="text-[10px] text-gray-400">Satuan: {{ $item['unit'] }}</p>
                                    </div>
                                    
                                    {{-- Input Qty --}}
                                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                        <button wire:click="updateQty({{ $id }}, {{ $item['qty'] - 1 }})" class="px-2 py-1 bg-gray-50 hover:bg-gray-200 text-gray-600 text-xs">-</button>
                                        <input type="number" value="{{ $item['qty'] }}" readonly class="w-10 text-center border-none text-xs p-1 font-bold">
                                        <button wire:click="updateQty({{ $id }}, {{ $item['qty'] + 1 }})" class="px-2 py-1 bg-gray-50 hover:bg-gray-200 text-gray-600 text-xs">+</button>
                                    </div>

                                    <button wire:click="removeFromCart({{ $id }})" class="text-red-400 hover:text-red-600 p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            @endforeach

                            {{-- Catatan --}}
                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase block mb-1">Catatan Tambahan</label>
                                <textarea wire:model="notes" class="w-full rounded-xl border-gray-300 text-sm focus:ring-orange-500 focus:border-orange-500" rows="2" placeholder="Cth: Butuh mendesak untuk besok..."></textarea>
                            </div>
                        @endif
                    </div>

                    <div class="p-4 bg-gray-50 border-t border-gray-200">
                        <button wire:click="submit" 
                            @if(empty($cart)) disabled @endif
                            class="w-full py-3 bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-600/20 hover:bg-orange-700 disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center justify-center gap-2">
                            <span>Ajukan Request</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>