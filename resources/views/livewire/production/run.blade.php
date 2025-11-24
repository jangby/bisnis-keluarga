<div class="bg-gray-50/50 min-h-screen pb-32 font-sans">

    {{-- HEADER (Sticky) --}}
    <div class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" wire:navigate class="group p-2 rounded-full hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-lg font-bold text-gray-900 leading-tight">Input Produksi</h1>
                    <p class="text-xs text-gray-500">Catat hasil produksi & penggunaan bahan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        
        <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- KOLOM KIRI: Target Produksi --}}
            <div class="md:col-span-1 space-y-6">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
                    <h3 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
                        <span class="w-1 h-5 bg-blue-600 rounded-full"></span>
                        Target Produksi
                    </h3>

                    <div class="space-y-4">
                        {{-- Pilih Produk --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">Produk yang dibuat</label>
                            <select wire:model.live="product_id" class="w-full rounded-xl border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Input Jumlah --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">Jumlah Jadi</label>
                            <div class="relative">
                                <input wire:model.live.debounce.500ms="quantity_produced" type="number" class="w-full pl-4 pr-12 rounded-xl border-gray-300 text-lg font-bold text-gray-900 focus:ring-blue-500 focus:border-blue-500 py-2" placeholder="0">
                                <span class="absolute right-4 top-3.5 text-xs font-bold text-gray-400">Pcs</span>
                            </div>
                        </div>

                        {{-- Tanggal --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">Tanggal Produksi</label>
                            <input wire:model="date" type="date" class="w-full rounded-xl border-gray-300 text-sm py-2.5">
                        </div>
                    </div>
                </div>

                {{-- Info Box --}}
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 text-xs text-blue-700 leading-relaxed">
                    <p class="font-bold mb-1">ℹ️ Catatan Sistem:</p>
                    Stok bahan baku akan otomatis berkurang sesuai input "Terpakai". HPP produk jadi akan dihitung ulang secara otomatis.
                </div>
            </div>

            {{-- KOLOM KANAN: Bahan Baku --}}
            <div class="md:col-span-2">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 min-h-[400px]">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                            <span class="w-1 h-5 bg-orange-500 rounded-full"></span>
                            Pemakaian Bahan Baku
                        </h3>
                        @if(count($materialsUsed) > 0)
                            <span class="bg-orange-100 text-orange-700 text-[10px] font-bold px-2 py-1 rounded-lg border border-orange-200">
                                {{ count($materialsUsed) }} Bahan
                            </span>
                        @endif
                    </div>

                    @if(count($materialsUsed) > 0)
                        <div class="space-y-4">
                            @foreach($materialsUsed as $index => $item)
                                @php
                                    $isStockLow = $item['current_stock'] < $item['actual_qty'];
                                @endphp

                                <div class="relative bg-white rounded-xl border {{ $isStockLow ? 'border-red-300 ring-1 ring-red-100' : 'border-gray-200' }} p-4 transition hover:shadow-md group">
                                    
                                    {{-- Header Item --}}
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-sm">{{ $item['name'] }}</h4>
                                            <p class="text-[10px] text-gray-400 mt-0.5">
                                                Stok Gudang: <span class="{{ $isStockLow ? 'text-red-600 font-bold' : 'text-gray-600' }}">{{ $item['current_stock'] + 0 }} {{ $item['unit'] }}</span>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="block text-[10px] text-gray-400 uppercase font-bold">Resep Standar</span>
                                            <span class="block text-xs font-medium text-gray-600">{{ $item['standard_qty'] + 0 }} {{ $item['unit'] }}</span>
                                        </div>
                                    </div>

                                    {{-- Input Realisasi --}}
                                    <div class="bg-gray-50 rounded-lg p-2 flex items-center gap-3 border border-gray-100 group-hover:border-blue-200 transition-colors">
                                        <label class="text-[10px] font-bold text-gray-500 uppercase whitespace-nowrap pl-1">Aktual Dipakai:</label>
                                        <div class="flex-1 relative">
                                            <input type="number" step="0.001" 
                                                wire:model.live="materialsUsed.{{ $index }}.actual_qty"
                                                class="w-full bg-white text-right font-bold text-gray-900 text-sm rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500 py-1.5 pr-8"
                                            >
                                            <span class="absolute right-2 top-1.5 text-xs text-gray-400 font-bold pointer-events-none">{{ $item['unit'] }}</span>
                                        </div>
                                    </div>

                                    {{-- Warning Stok Kurang --}}
                                    @if($isStockLow)
                                        <div class="mt-2 flex items-center gap-1.5 text-red-600 bg-red-50 p-1.5 rounded-lg animate-pulse">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            <span class="text-[10px] font-bold">Stok tidak cukup! (Kurang {{ abs($item['current_stock'] - $item['actual_qty']) }})</span>
                                        </div>
                                    @endif

                                    @error("materialsUsed.{$index}.actual_qty") 
                                        <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p> 
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    @elseif($product_id && $quantity_produced > 0)
                        {{-- State jika produk tidak punya resep --}}
                        <div class="h-full flex flex-col items-center justify-center text-center py-10 opacity-60">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <p class="text-sm font-bold text-gray-600">Produk Tanpa Resep</p>
                            <p class="text-xs text-gray-500 mt-1">Stok produk akan bertambah tanpa<br>mengurangi bahan baku.</p>
                        </div>
                    @else
                        {{-- State Kosong --}}
                        <div class="h-full flex flex-col items-center justify-center text-center py-10 opacity-60">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <p class="text-sm font-bold text-gray-600">Belum ada data</p>
                            <p class="text-xs text-gray-500 mt-1">Pilih produk & masukkan jumlah<br>untuk melihat kebutuhan bahan.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- FOOTER ACTION --}}
            <div class="fixed bottom-16 left-0 right-0 p-4 bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] md:static md:bg-transparent md:border-none md:p-0 md:col-span-3 z-20">
                <div class="max-w-4xl mx-auto flex gap-3">
                    <a href="{{ route('dashboard') }}" class="hidden md:flex px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="flex-1 py-4 rounded-xl font-bold text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-600/30 transform active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>SIMPAN HASIL PRODUKSI</span>
                    </button>
                </div>
            </div>

        </form>
    </div>

    {{-- Notifikasi Error Global (Jika ada) --}}
    @if ($errors->any())
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
             class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 bg-red-600 text-white px-6 py-3 rounded-full shadow-xl flex items-center gap-3 animate-bounce-in">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span class="text-sm font-bold">Periksa inputan, ada yang salah!</span>
        </div>
    @endif

    {{-- Notifikasi Sukses --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-4 left-4 right-4 md:top-6 md:right-6 md:left-auto md:w-96 z-50 bg-green-600 text-white px-4 py-3 rounded-xl shadow-2xl flex items-center gap-3 animate-fade-in-down">
            <div class="bg-white/20 p-2 rounded-full">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <h4 class="font-bold text-sm">Produksi Berhasil!</h4>
                <p class="text-xs text-green-100">{{ session('message') }}</p>
            </div>
        </div>
    @endif

</div>