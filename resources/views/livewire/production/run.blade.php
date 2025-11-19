<div class="bg-gray-50 min-h-screen pb-24 p-4 space-y-6">
    
    <h2 class="font-bold text-xl text-gray-800">Input Hasil Produksi</h2>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
        
        <!-- Error Message Custom -->
        @if ($errors->any()) <!-- Menangkap throw exception tadi -->
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Gagal!</strong>
                <span class="block sm:inline">{{ $errors->first() }}</span>
            </div>
        @endif

        <form wire:submit="save" class="space-y-4">
            
            <!-- Pilih Barang Jadi -->
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Produk yang Dibuat</label>
                <select wire:model.live="product_id" class="w-full mt-1 rounded-lg border-gray-300 text-sm">
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Info Resep (Preview) -->
            @if($product_id)
                @php $selectedProd = $products->find($product_id); @endphp
                <div class="bg-blue-50 p-3 rounded-lg text-xs text-blue-800">
                    <p class="font-bold mb-1">Resep per 1 {{ $selectedProd->unit }}:</p>
                    <ul class="list-disc pl-4">
                        @forelse($selectedProd->recipes as $recipe)
                            <li>
                                {{ $recipe->material->name }}: 
                                <b>{{ $recipe->quantity_needed + 0 }} {{ $recipe->material->unit }}</b>
                                (Sisa Stok: {{ $recipe->material->current_stock }})
                            </li>
                        @empty
                            <li class="text-red-500">Belum ada resep! Stok bahan tidak akan berkurang.</li>
                        @endforelse
                    </ul>
                </div>
            @endif
            
            <div class="flex gap-3">
                <div class="flex-1">
                    <label class="text-xs font-bold text-gray-500 uppercase">Jumlah Jadi</label>
                    <input wire:model="quantity_produced" type="number" class="w-full mt-1 rounded-lg border-gray-300 text-sm" placeholder="0">
                </div>
                <div class="w-1/3">
                    <label class="text-xs font-bold text-gray-500 uppercase">Tanggal</label>
                    <input wire:model="date" type="date" class="w-full mt-1 rounded-lg border-gray-300 text-sm">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition shadow-lg mt-4">
                SIMPAN & UPDATE STOK 🏭
            </button>
        </form>

        @if (session()->has('message'))
            <div class="mt-4 bg-green-100 text-green-800 text-xs p-3 rounded text-center font-bold">
                {{ session('message') }}
            </div>
        @endif
    </div>
</div>