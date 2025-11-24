<div class="bg-gray-50 min-h-screen pb-32">
    {{-- Header Mobile (Sticky Top) --}}
    <div class="bg-white border-b border-gray-200 px-4 py-4 sticky top-0 z-30 shadow-sm">
        <h2 class="font-bold text-lg text-gray-800 flex items-center gap-2">
            <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
            </span>
            Produksi Baru
        </h2>
    </div>

    <form wire:submit="save" class="p-4 space-y-6">
        
        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded text-sm shadow-sm animate-pulse">
                <p class="font-bold">Gagal Disimpan!</p>
                <p>{{ $errors->first() }}</p>
            </div>
        @endif

        {{-- BAGIAN 1: Target Produksi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-blue-50 px-4 py-3 border-b border-blue-100">
                <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wide">1. Target Produksi</h3>
            </div>
            
            <div class="p-4 space-y-4">
                {{-- Pilih Produk --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Mau bikin apa?</label>
                    <select wire:model.live="product_id" class="w-full h-12 rounded-xl border-gray-300 text-gray-800 font-medium focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Input Jumlah --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Jumlah Jadi</label>
                        <div class="relative">
                            <input wire:model.live.debounce.500ms="quantity_produced" type="number" class="w-full h-12 pl-4 pr-8 rounded-xl border-gray-300 text-gray-900 font-bold text-lg focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal</label>
                        <input wire:model="date" type="date" class="w-full h-12 rounded-xl border-gray-300 text-sm focus:ring-blue-500">
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN 2: Bahan Baku --}}
        @if(count($materialsUsed) > 0)
            <div class="space-y-3">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-sm font-bold text-gray-700">2. Pemakaian Bahan</h3>
                    <span class="text-[10px] bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full font-medium">Wajib Diisi Real</span>
                </div>

                @foreach($materialsUsed as $index => $item)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 relative">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $item['name'] }}</h4>
                                <p class="text-xs text-gray-500">Stok: <span class="font-medium text-gray-700">{{ $item['current_stock'] + 0 }} {{ $item['unit'] }}</span></p>
                            </div>
                            <div class="text-right">
                                <span class="block text-[10px] text-gray-400 uppercase">Saran</span>
                                <span class="block text-sm font-bold text-gray-400">{{ $item['standard_qty'] + 0 }}</span>
                            </div>
                        </div>

                        <div class="bg-yellow-50 rounded-xl p-3 flex items-center gap-3 border border-yellow-200">
                            <div class="flex-1">
                                <label class="text-[10px] font-bold text-yellow-800 uppercase block mb-1">Terpakai (Real)</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" step="0.001" 
                                        wire:model="materialsUsed.{{ $index }}.actual_qty"
                                        class="w-full h-10 bg-white rounded-lg border-yellow-300 text-gray-900 font-bold text-center focus:ring-yellow-500 focus:border-yellow-500 shadow-sm"
                                    >
                                    <span class="text-xs font-bold text-yellow-700">{{ $item['unit'] }}</span>
                                </div>
                            </div>
                        </div>
                        @error("materialsUsed.{$index}.actual_qty") 
                            <p class="text-xs text-red-500 mt-2 text-right font-medium">{{ $message }}</p> 
                        @enderror
                    </div>
                @endforeach
            </div>
        
        @elseif($product_id && $quantity_produced > 0)
            <div class="flex flex-col items-center justify-center p-8 bg-gray-100 rounded-2xl border-2 border-dashed border-gray-300 text-gray-400 text-center">
                <span class="text-xs">Produk ini belum punya resep.<br>Stok bahan tidak berkurang.</span>
            </div>
        @endif

        {{-- TOMBOL SIMPAN (STATIS DI BAWAH) --}}
        {{-- Kita hapus 'fixed' dan ganti jadi 'mt-8' agar tombol selalu ada di akhir form --}}
        <div class="pt-6">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 transition transform active:scale-95">
                <span>SIMPAN & UPDATE STOK</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </button>
            <p class="text-center text-[10px] text-gray-400 mt-3">Pastikan data bahan baku sudah benar sebelum menyimpan.</p>
        </div>

    </form>

    {{-- Success Notification --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-4 left-4 right-4 z-50 bg-green-600 text-white px-4 py-3 rounded-xl shadow-2xl flex items-center gap-3 animate-bounce-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h4 class="font-bold text-sm">Berhasil!</h4>
                <p class="text-xs text-green-100">{{ session('message') }}</p>
            </div>
        </div>
    @endif
</div>