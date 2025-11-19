<div class="bg-gray-50 min-h-screen pb-24">

    <!-- HEADER -->
    <div class="bg-white p-4 shadow-sm sticky top-0 z-10 flex items-center gap-3">
        <a href="{{ route('products.index') }}" wire:navigate class="p-2 rounded-full hover:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="font-bold text-lg text-gray-800">
            {{ $product_id ? 'Edit Produk' : 'Produk Baru' }}
        </h2>
    </div>

    <!-- FORM INPUT -->
    <form wire:submit="save" class="p-4 space-y-5">

        <!-- 1. PILIHAN TIPE (Barang Jadi / Bahan Baku) -->
        <div class="bg-white p-4 rounded-xl border border-gray-200">
            <label class="text-xs font-medium text-gray-500 mb-2 block">Tipe Produk</label>
            <div class="flex gap-3">
                <label class="flex-1 flex items-center gap-2 p-3 border rounded-xl cursor-pointer transition {{ $type == 'goods' ? 'bg-blue-50 border-blue-500 ring-1 ring-blue-500' : 'border-gray-200' }}">
                    <input type="radio" wire:model.live="type" value="goods" class="text-blue-600 focus:ring-blue-500">
                    <div>
                        <span class="block text-sm font-bold text-gray-800">Barang Jadi</span>
                        <span class="text-[10px] text-gray-500">Produk yang dijual (Kecap, Sistik)</span>
                    </div>
                </label>
                
                <label class="flex-1 flex items-center gap-2 p-3 border rounded-xl cursor-pointer transition {{ $type == 'material' ? 'bg-orange-50 border-orange-500 ring-1 ring-orange-500' : 'border-gray-200' }}">
                    <input type="radio" wire:model.live="type" value="material" class="text-orange-600 focus:ring-orange-500">
                    <div>
                        <span class="block text-sm font-bold text-gray-800">Bahan Baku</span>
                        <span class="text-[10px] text-gray-500">Bahan mentah (Tepung, Kedelai)</span>
                    </div>
                </label>
            </div>
        </div>

        <!-- 2. Identitas Produk -->
        <div class="bg-white p-4 rounded-xl border border-gray-200 space-y-4">
            <h3 class="font-bold text-gray-500 text-xs uppercase">Identitas Barang</h3>
            
            <div>
                <label class="text-xs font-medium text-gray-500">Divisi (Jenis)</label>
                <select wire:model="product_line_id" class="w-full mt-1 rounded-lg border-gray-300 text-sm bg-gray-50">
                    @foreach($productLines as $line)
                        <option value="{{ $line->id }}">{{ $line->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs font-medium text-gray-500">Kode Barang (SKU)</label>
                <input wire:model="code" type="text" placeholder="Contoh: KCP-001" class="w-full mt-1 rounded-lg border-gray-300 text-sm uppercase">
                @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-xs font-medium text-gray-500">Nama Produk</label>
                <input wire:model="name" type="text" placeholder="Contoh: Kecap Manis 600ml" class="w-full mt-1 rounded-lg border-gray-300 text-sm font-semibold">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-xs font-medium text-gray-500">Satuan</label>
                <select wire:model="unit" class="w-full mt-1 rounded-lg border-gray-300 text-sm">
                    <option value="Pcs">Pcs</option>
                    <option value="Botol">Botol</option>
                    <option value="Bungkus">Bungkus</option>
                    <option value="Kg">Kg</option>
                    <option value="Liter">Liter</option>
                    <option value="Karton">Karton</option>
                    <option value="Karung">Karung</option>
                </select>
            </div>
        </div>

        <!-- 3. RESEP / KOMPOSISI (Hanya Muncul Jika Tipe = Barang Jadi) -->
        @if($type == 'goods')
            <div class="bg-blue-50 p-4 rounded-xl border border-blue-200 space-y-3">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-blue-800 text-xs uppercase flex items-center gap-1">
                        🧪 Resep / Bahan Baku
                    </h3>
                    <button type="button" wire:click="addRecipeRow" class="text-[10px] bg-blue-600 text-white px-3 py-1.5 rounded-lg font-bold hover:bg-blue-700 shadow-sm">
                        + Tambah Bahan
                    </button>
                </div>
                
                <p class="text-[10px] text-blue-600 mb-2 bg-blue-100 p-2 rounded-lg">
                    Tentukan bahan yang dibutuhkan untuk membuat <b>1 {{ $unit }}</b> produk ini.
                    <br><i>Contoh: 1 Botol Kecap butuh 0.5 Kg Kedelai.</i>
                </p>

                <div class="space-y-2">
                    @foreach($recipes as $index => $recipe)
                        <div class="flex gap-2 items-end animate-fade-in-up">
                            <div class="flex-1">
                                <label class="text-[10px] text-gray-500 font-bold">Pilih Bahan</label>
                                <select wire:model="recipes.{{ $index }}.material_id" class="w-full rounded-lg border-blue-200 text-xs focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Pilih --</option>
                                    @foreach($materials as $mat)
                                        <option value="{{ $mat->id }}">{{ $mat->name }} ({{ $mat->unit }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-20">
                                <label class="text-[10px] text-gray-500 font-bold">Jumlah</label>
                                <input type="number" step="0.001" wire:model="recipes.{{ $index }}.quantity_needed" class="w-full rounded-lg border-blue-200 text-xs text-center font-bold" placeholder="0">
                            </div>
                            <button type="button" wire:click="removeRecipeRow({{ $index }})" class="mb-[2px] text-red-500 hover:bg-red-100 p-2 rounded-lg border border-transparent hover:border-red-200 transition" title="Hapus Baris">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 4. Harga & Modal -->
        <div class="bg-white p-4 rounded-xl border border-gray-200 space-y-4">
            <h3 class="font-bold text-gray-500 text-xs uppercase">Harga & Modal</h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-medium text-gray-500">HPP (Modal)</label>
                    <div class="relative mt-1">
                        <span class="absolute left-3 top-2 text-gray-400 text-xs">Rp</span>
                        <input wire:model="base_price" type="number" class="w-full pl-8 rounded-lg border-gray-300 text-sm" placeholder="0">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500">Harga Jual</label>
                    <div class="relative mt-1">
                        <span class="absolute left-3 top-2 text-gray-400 text-xs">Rp</span>
                        <input wire:model="sell_price" type="number" class="w-full pl-8 rounded-lg border-blue-300 text-sm font-bold text-blue-600" placeholder="0">
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Stok Awal (Hanya Muncul saat Buat Baru) -->
        @if(!$product_id)
        <div class="bg-white p-4 rounded-xl border border-gray-200 space-y-4">
            <h3 class="font-bold text-gray-500 text-xs uppercase">Stok Awal</h3>
            <div>
                <label class="text-xs font-medium text-gray-500">Jumlah Stok Fisik</label>
                <input wire:model="current_stock" type="number" class="w-full mt-1 rounded-lg border-gray-300 text-sm" placeholder="0">
                <p class="text-[10px] text-gray-400 mt-1">Stok akan tercatat otomatis saat anda menyimpan.</p>
            </div>
        </div>
        @endif

        <!-- TOMBOL SIMPAN -->
        <button type="submit" class="w-full py-4 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg transform active:scale-95 transition">
            SIMPAN DATA
        </button>

    </form>
</div>