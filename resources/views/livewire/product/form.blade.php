<div class="bg-gray-50 min-h-screen pb-24">

    <div class="bg-white p-4 shadow-sm sticky top-0 z-10 flex items-center gap-3">
        <a href="{{ route('products.index') }}" wire:navigate class="p-2 rounded-full hover:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="font-bold text-lg text-gray-800">
            {{ $product_id ? 'Edit Data' : 'Input Baru' }}
        </h2>
    </div>

    <form wire:submit="save" class="p-4 space-y-5">

        <div class="bg-white p-4 rounded-xl border border-gray-200">
            <label class="text-xs font-medium text-gray-500 mb-2 block">Jenis Input</label>
            <div class="flex gap-3">
                <label class="flex-1 flex items-center gap-2 p-3 border rounded-xl cursor-pointer transition {{ $type == 'goods' ? 'bg-blue-50 border-blue-500 ring-1 ring-blue-500' : 'border-gray-200' }}">
                    <input type="radio" wire:model.live="type" value="goods" class="text-blue-600 focus:ring-blue-500">
                    <div>
                        <span class="block text-sm font-bold text-gray-800">Barang Jadi</span>
                        <span class="text-[10px] text-gray-500">Produk siap jual</span>
                    </div>
                </label>
                
                <label class="flex-1 flex items-center gap-2 p-3 border rounded-xl cursor-pointer transition {{ $type == 'material' ? 'bg-orange-50 border-orange-500 ring-1 ring-orange-500' : 'border-gray-200' }}">
                    <input type="radio" wire:model.live="type" value="material" class="text-orange-600 focus:ring-orange-500">
                    <div>
                        <span class="block text-sm font-bold text-gray-800">Bahan Baku</span>
                        <span class="text-[10px] text-gray-500">Bahan mentah</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-200 space-y-4">
            <h3 class="font-bold text-gray-500 text-xs uppercase">Identitas</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-medium text-gray-500">Divisi</label>
                    <select wire:model="product_line_id" class="w-full mt-1 rounded-lg border-gray-300 text-sm bg-gray-50">
                        @foreach($productLines as $line)
                            <option value="{{ $line->id }}">{{ $line->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-500">Kode (Otomatis)</label>
                    <input wire:model="code" type="text" readonly class="w-full mt-1 rounded-lg border-gray-200 bg-gray-100 text-gray-500 text-sm font-mono cursor-not-allowed">
                </div>
            </div>

            <div>
                <label class="text-xs font-medium text-gray-500">
                    {{ $type == 'goods' ? 'Nama Produk Baru' : 'Nama Bahan Baku' }}
                </label>
                <input wire:model="name" type="text" 
                       placeholder="{{ $type == 'goods' ? 'Contoh: Kripik Singkong Balado' : 'Contoh: Tepung Terigu Segitiga' }}" 
                       class="w-full mt-1 rounded-lg border-gray-300 text-sm font-semibold">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-medium text-gray-500">Satuan Stok</label>
                    <select wire:model.live="unit" class="w-full mt-1 rounded-lg border-gray-300 text-sm font-bold text-gray-700">
                        <option value="Pcs">Pcs</option>
                        <option value="Botol">Botol</option>
                        <option value="Bungkus">Bungkus</option>
                        <option value="Kg">Kg (Kilogram)</option>
                        <option value="Gr">Gr (Gram)</option>
                        <option value="Liter">Liter</option>
                        <option value="Ml">Ml (Mililiter)</option>
                        <option value="Karung">Karung</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-medium text-red-500 font-bold">Batas Minimum (Alert)</label>
                    <div class="flex items-center gap-2 mt-1">
                        <input wire:model="min_stock" type="number" class="w-full rounded-lg border-red-200 focus:border-red-500 focus:ring-red-500 text-sm font-bold text-red-600" placeholder="5">
                        <span class="text-xs font-bold text-gray-400">{{ $unit }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($type == 'goods')
            <div class="bg-blue-50 p-4 rounded-xl border border-blue-200 space-y-3">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-blue-800 text-xs uppercase flex items-center gap-1">
                        🧪 Resep / Komposisi
                    </h3>
                    <button type="button" wire:click="addRecipeRow" class="text-[10px] bg-blue-600 text-white px-3 py-1.5 rounded-lg font-bold hover:bg-blue-700 shadow-sm">
                        + Tambah Bahan
                    </button>
                </div>
                
                <p class="text-[10px] text-blue-600 mb-2 bg-blue-100 p-2 rounded-lg">
                    Masukkan bahan untuk membuat <b>1 {{ $unit }}</b> produk ini.
                </p>

                <div class="space-y-2">
                    @foreach($recipes as $index => $recipe)
                        <div class="flex gap-2 items-start animate-fade-in-up">
                            <div class="flex-1">
                                <label class="text-[10px] text-gray-500 font-bold">Bahan</label>
                                <select wire:model.live="recipes.{{ $index }}.material_id" class="w-full rounded-lg border-blue-200 text-xs focus:border-blue-500">
                                    <option value="">-- Pilih --</option>
                                    @foreach($materials as $mat)
                                        <option value="{{ $mat->id }}">{{ $mat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-24">
                                <label class="text-[10px] text-gray-500 font-bold">Jml</label>
                                <div class="relative">
                                    <input type="number" step="0.001" wire:model.live="recipes.{{ $index }}.quantity_needed" class="w-full rounded-lg border-blue-200 text-xs font-bold pl-2 pr-8" placeholder="0">
                                    <span class="absolute right-2 top-1.5 text-[10px] text-gray-400 font-bold pointer-events-none">
                                        @php
                                            $selectedMat = $materials->firstWhere('id', $recipe['material_id']);
                                        @endphp
                                        {{ $selectedMat ? $selectedMat->unit : '-' }}
                                    </span>
                                </div>
                            </div>

                            <button type="button" wire:click="removeRecipeRow({{ $index }})" class="mt-6 text-red-500 p-1 hover:bg-red-100 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="bg-white p-4 rounded-xl border border-gray-200 space-y-4">
            <h3 class="font-bold text-gray-500 text-xs uppercase">Informasi Harga</h3>

            <div class="grid grid-cols-1 gap-4">
                
                @if($type == 'material')
                    <div>
                        <label class="text-xs font-medium text-gray-500">Harga Beli / {{ $unit }}</label>
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-2.5 text-gray-400 text-xs font-bold">Rp</span>
                            <input wire:model="base_price" type="number" class="w-full pl-10 rounded-lg border-gray-300 text-sm font-semibold" placeholder="0">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">Harga beli rata-rata per {{ $unit }}.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500">HPP / {{ $unit }} (Auto)</label>
                            <div class="relative mt-1">
                                <span class="absolute left-3 top-2.5 text-gray-500 text-xs font-bold">Rp</span>
                                <input wire:model="base_price" type="text" readonly class="w-full pl-10 rounded-lg border-gray-200 bg-gray-100 text-gray-500 text-sm font-bold cursor-not-allowed">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-blue-600 font-bold">Harga Jual / {{ $unit }}</label>
                            <div class="relative mt-1">
                                <span class="absolute left-3 top-2.5 text-blue-600 text-xs font-bold">Rp</span>
                                <input wire:model="sell_price" type="number" class="w-full pl-10 rounded-lg border-blue-300 text-sm font-bold text-blue-800 focus:ring-blue-500" placeholder="0">
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        @if(!$product_id)
        <div class="bg-white p-4 rounded-xl border border-gray-200 space-y-4">
            <h3 class="font-bold text-gray-500 text-xs uppercase">Stok Awal</h3>
            <div>
                <label class="text-xs font-medium text-gray-500">Jumlah Fisik ({{ $unit }})</label>
                <div class="flex items-center gap-2 mt-1">
                    <input wire:model="current_stock" type="number" class="w-full rounded-lg border-gray-300 text-sm" placeholder="0">
                    <span class="text-sm text-gray-700 font-bold bg-gray-100 px-3 py-2 rounded-lg border border-gray-200 min-w-[60px] text-center">
                        {{ $unit }}
                    </span>
                </div>
            </div>
        </div>
        @endif

        <button type="submit" class="w-full py-4 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg transform active:scale-95 transition">
            SIMPAN DATA
        </button>

    </form>
</div>