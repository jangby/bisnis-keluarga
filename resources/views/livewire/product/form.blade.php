<div class="min-h-screen bg-gray-50/50 pb-32 font-sans">

    {{-- HEADER (Sticky) --}}
    <div class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('products.index') }}" wire:navigate class="group p-2 rounded-full hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-lg font-bold text-gray-900 leading-tight">
                        {{ $product_id ? 'Edit Produk' : 'Tambah Produk Baru' }}
                    </h1>
                    <p class="text-xs text-gray-500">Isi data produk dengan lengkap</p>
                </div>
            </div>
            
            {{-- Status Badge (Opsional) --}}
            @if($product_id)
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border {{ $type == 'goods' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-orange-50 text-orange-600 border-orange-100' }}">
                    {{ $type == 'goods' ? 'Barang Jadi' : 'Bahan Baku' }}
                </span>
            @endif
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        <form wire:submit="save" class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- KOLOM KIRI (Main Info) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- 1. PILIH TIPE (Hanya tampil saat Create Baru) --}}
                @if(!$product_id)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 block">Tipe Produk</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer group relative">
                                <input type="radio" wire:model.live="type" value="goods" class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 transition-all duration-200 flex flex-col items-center text-center gap-2
                                    peer-checked:border-blue-600 peer-checked:bg-blue-50/50 hover:bg-gray-50 border-gray-100">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl mb-1 group-hover:scale-110 transition-transform">🛍️</div>
                                    <div>
                                        <span class="block text-sm font-bold text-gray-900">Barang Jadi</span>
                                        <span class="text-[10px] text-gray-500">Produk siap dijual ke kasir</span>
                                    </div>
                                    {{-- Checkmark Icon --}}
                                    <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 text-blue-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    </div>
                                </div>
                            </label>

                            <label class="cursor-pointer group relative">
                                <input type="radio" wire:model.live="type" value="material" class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 transition-all duration-200 flex flex-col items-center text-center gap-2
                                    peer-checked:border-orange-600 peer-checked:bg-orange-50/50 hover:bg-gray-50 border-gray-100">
                                    <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xl mb-1 group-hover:scale-110 transition-transform">📦</div>
                                    <div>
                                        <span class="block text-sm font-bold text-gray-900">Bahan Baku</span>
                                        <span class="text-[10px] text-gray-500">Stok mentah untuk produksi</span>
                                    </div>
                                    <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 text-orange-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                @endif

                {{-- 2. INFORMASI DASAR --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                    <h3 class="font-bold text-gray-900 text-sm mb-5 flex items-center gap-2">
                        <span class="w-1 h-5 bg-blue-600 rounded-full"></span>
                        Informasi Identitas & Foto
                    </h3>
                    
                    {{-- [BARU] AREA UPLOAD FOTO --}}
                    <div class="mb-6 flex justify-center">
                        <div class="relative w-full">
                            <label class="block w-full cursor-pointer group">
                                <input type="file" wire:model="image" class="hidden" accept="image/*">
                                
                                <div class="relative h-48 w-full rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 flex flex-col items-center justify-center overflow-hidden transition-all hover:bg-blue-50 hover:border-blue-400">
                                    
                                    @if ($image)
                                        {{-- Preview Gambar Baru (Temporary) --}}
                                        <img src="{{ $image->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="text-white font-bold text-sm">Ganti Foto</span>
                                        </div>
                                    @elseif($oldImage)
                                        {{-- Preview Gambar Lama (Dari DB) --}}
                                        <img src="{{ asset('storage/' . $oldImage) }}" class="absolute inset-0 w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="text-white font-bold text-sm">Ganti Foto</span>
                                        </div>
                                    @else
                                        {{-- Tampilan Kosong --}}
                                        <div class="text-center p-4">
                                            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-2">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                            <span class="block text-sm font-bold text-gray-500">Upload Foto Produk</span>
                                            <span class="text-[10px] text-gray-400">Tap disini (Maks. 2MB)</span>
                                        </div>
                                    @endif

                                    {{-- Loading Indicator --}}
                                    <div wire:loading wire:target="image" class="absolute inset-0 bg-white/80 flex items-center justify-center z-20">
                                        <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </div>
                                </div>
                            </label>
                            @error('image') <span class="text-red-500 text-xs mt-1 block text-center">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Kategori --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">Kategori Produk</label>
                            <select wire:model="product_line_id" class="w-full rounded-xl border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 transition shadow-sm bg-gray-50 focus:bg-white py-2.5">
                                @foreach($productLines as $line)
                                    <option value="{{ $line->id }}">{{ $line->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kode Produk --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">Kode Produk (Auto)</label>
                            <div class="relative">
                                <input wire:model="code" type="text" readonly class="w-full pl-9 rounded-xl border-gray-200 bg-gray-100 text-gray-500 text-sm font-mono cursor-not-allowed py-2.5">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                        </div>

                        {{-- Nama Produk --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">Nama Produk</label>
                            <input wire:model="name" type="text" 
                                class="w-full rounded-xl border-gray-300 text-sm font-semibold focus:ring-blue-500 focus:border-blue-500 transition shadow-sm py-2.5 placeholder-gray-300"
                                placeholder="{{ $type == 'goods' ? 'Contoh: Kripik Singkong Balado 250gr' : 'Contoh: Tepung Terigu Segitiga Biru' }}">
                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- 3. RESEP (KHUSUS BARANG JADI) --}}
                @if($type == 'goods')
                    <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100 relative overflow-hidden">
                        {{-- Background Decoration --}}
                        <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-100 rounded-full opacity-50 blur-2xl"></div>

                        <div class="relative z-10">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h3 class="font-bold text-blue-900 text-sm flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                        Resep / Komposisi
                                    </h3>
                                    <p class="text-[11px] text-blue-600 mt-0.5">Bahan yang berkurang otomatis saat produk ini terjual.</p>
                                </div>
                                <button type="button" wire:click="addRecipeRow" class="text-xs bg-white text-blue-600 border border-blue-200 px-3 py-1.5 rounded-lg font-bold hover:bg-blue-600 hover:text-white transition shadow-sm flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                    Tambah Bahan
                                </button>
                            </div>

                            <div class="space-y-3">
                                @foreach($recipes as $index => $recipe)
                                    <div class="flex gap-3 items-start animate-fade-in-down bg-white p-3 rounded-xl border border-blue-100 shadow-sm">
                                        <div class="flex-1">
                                            <label class="text-[10px] text-gray-400 font-bold uppercase mb-1 block">Bahan Baku</label>
                                            <select wire:model.live="recipes.{{ $index }}.material_id" class="w-full rounded-lg border-gray-200 text-xs focus:border-blue-500 py-2">
                                                <option value="">-- Pilih Bahan --</option>
                                                @foreach($materials as $mat)
                                                    <option value="{{ $mat->id }}">{{ $mat->name }} ({{ $mat->unit }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="w-28">
                                            <label class="text-[10px] text-gray-400 font-bold uppercase mb-1 block">Takaran</label>
                                            <div class="relative">
                                                <input type="number" step="0.001" wire:model.live="recipes.{{ $index }}.quantity_needed" class="w-full rounded-lg border-gray-200 text-xs font-bold pl-3 pr-8 py-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                                                <span class="absolute right-2 top-2 text-[10px] text-gray-400 font-bold pointer-events-none">
                                                    @php $selectedMat = $materials->firstWhere('id', $recipe['material_id']); @endphp
                                                    {{ $selectedMat ? $selectedMat->unit : '' }}
                                                </span>
                                            </div>
                                        </div>

                                        <button type="button" wire:click="removeRecipeRow({{ $index }})" class="mt-6 text-gray-400 hover:text-red-500 p-1 rounded-md transition">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                @endforeach

                                @if(empty($recipes))
                                    <div class="text-center py-6 border-2 border-dashed border-blue-200 rounded-xl bg-blue-50/50">
                                        <p class="text-xs text-blue-400 font-medium">Belum ada bahan baku dipilih.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- KOLOM KANAN (Harga & Stok) --}}
            <div class="space-y-6">
                
                {{-- 4. HARGA --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                    <h3 class="font-bold text-gray-900 text-sm mb-5 flex items-center gap-2">
                        <span class="w-1 h-5 bg-green-500 rounded-full"></span>
                        Pengaturan Harga
                    </h3>

                    <div class="space-y-4">
                        {{-- HPP / Harga Beli --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">
                                {{ $type == 'material' ? 'Harga Beli (Modal)' : 'HPP (Auto Calculated)' }}
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400 text-xs font-bold">Rp</span>
                                <input wire:model="base_price" type="{{ $type == 'material' ? 'number' : 'text' }}" 
                                    {{ $type == 'goods' ? 'readonly' : '' }}
                                    class="w-full pl-9 rounded-xl border-gray-300 text-sm font-semibold py-2.5 
                                    {{ $type == 'goods' ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : 'focus:ring-green-500 focus:border-green-500' }}" 
                                    placeholder="0">
                            </div>
                            @if($type == 'goods')
                                <p class="text-[10px] text-gray-400 mt-1">Total harga bahan baku di atas.</p>
                            @endif
                        </div>

                        {{-- Harga Jual (Jika Goods) --}}
                        @if($type == 'goods')
                            <div>
                                <label class="text-xs font-bold text-blue-600 mb-1.5 block">Harga Jual Satuan</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-blue-600 text-xs font-bold">Rp</span>
                                    <input wire:model="sell_price" type="number" class="w-full pl-9 rounded-xl border-blue-300 text-sm font-bold text-blue-900 focus:ring-blue-500 focus:border-blue-500 py-2.5 shadow-sm" placeholder="0">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 5. STOK & ALERT --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                    <h3 class="font-bold text-gray-900 text-sm mb-5 flex items-center gap-2">
                        <span class="w-1 h-5 bg-orange-500 rounded-full"></span>
                        Stok & Satuan
                    </h3>

                    <div class="space-y-4">
                        {{-- Satuan --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">Satuan Unit</label>
                            <select wire:model.live="unit" class="w-full rounded-xl border-gray-300 text-sm font-bold text-gray-700 py-2.5">
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

                        {{-- Stok Awal (Hanya saat create) --}}
                        @if(!$product_id)
                            <div>
                                <label class="text-xs font-bold text-gray-500 mb-1.5 block">Stok Awal Fisik</label>
                                <div class="flex items-center gap-2">
                                    <input wire:model="current_stock" type="number" class="w-full rounded-xl border-gray-300 text-sm py-2.5" placeholder="0">
                                    <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-3 rounded-xl border border-gray-200 min-w-[50px] text-center">
                                        {{ $unit }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        {{-- Alert Stok Minim --}}
                        <div class="bg-orange-50 p-3 rounded-xl border border-orange-100">
                            <label class="text-[10px] font-bold text-orange-800 uppercase mb-1 block flex justify-between">
                                <span>Peringatan Stok Minim</span>
                                <span class="text-orange-600">🔔</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-orange-700">Ingatkan jika <</span>
                                <input wire:model="min_stock" type="number" class="w-20 rounded-lg border-orange-200 text-xs font-bold text-center text-orange-800 focus:ring-orange-500 focus:border-orange-500 py-1.5" placeholder="5">
                                <span class="text-xs text-orange-700">{{ $unit }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            {{-- FLOATING ACTION BUTTON (Mobile Sticky Footer) --}}
            {{-- Ubah bottom-0 menjadi bottom-20 (atau sesuaikan tingginya) agar naik di atas navigasi bawah --}}
            <div class="fixed bottom-20 lg:bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 lg:static lg:bg-transparent lg:border-none lg:p-0 lg:col-span-3 z-20 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] lg:shadow-none">
                <div class="max-w-5xl mx-auto flex gap-3">
                    <a href="{{ route('products.index') }}" wire:navigate class="hidden lg:flex px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="flex-1 py-3.5 rounded-xl font-bold text-white shadow-lg shadow-blue-600/30 transform active:scale-[0.98] transition-all flex items-center justify-center gap-2
                        {{ $type == 'goods' ? 'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800' : 'bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        <span>SIMPAN PRODUK</span>
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>