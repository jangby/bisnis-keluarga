<div class="min-h-screen bg-gray-50 pb-20"> <div class="sticky top-0 z-30 bg-white shadow-sm border-b border-gray-100 px-4 py-4 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800 tracking-tight">
            Kelola Web
        </h2>
        <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 font-bold text-xs border border-pink-200">
            {{ substr(auth()->user()->name, 0, 2) }}
        </div>
    </div>

    <div class="px-4 mt-4">
        <div class="bg-gray-200 p-1 rounded-xl flex shadow-inner">
            <button wire:click="$set('activeTab', 'categories')" 
                class="flex-1 py-2 text-sm font-bold rounded-lg transition-all duration-200 flex items-center justify-center gap-2
                {{ $activeTab == 'categories' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}">
                <span>📂</span> Etalase
            </button>
            <button wire:click="$set('activeTab', 'discounts')" 
                class="flex-1 py-2 text-sm font-bold rounded-lg transition-all duration-200 flex items-center justify-center gap-2
                {{ $activeTab == 'discounts' ? 'bg-white text-pink-600 shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}">
                <span>🏷️</span> Diskon
            </button>
        </div>
    </div>

    <div class="px-4 mt-4 space-y-4">

        {{-- ALERT MESSAGE --}}
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded-r shadow-sm text-sm flex items-center justify-between animate-fade-in-down">
                <span>{{ session('message') }}</span>
                <button @click="show = false" class="text-green-800 font-bold">&times;</button>
            </div>
        @endif

        @if($activeTab == 'categories')
            
            <button wire:click="$set('isModalOpen', true)" 
                class="w-full bg-white border-2 border-dashed border-gray-300 text-gray-500 py-3 rounded-xl font-semibold hover:border-indigo-500 hover:text-indigo-600 transition flex items-center justify-center gap-2 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Kategori Baru
            </button>

            <div class="space-y-3">
                @foreach($data['categories'] as $cat)
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
                        <div class="absolute right-0 top-0 w-16 h-16 bg-gradient-to-br from-indigo-50 to-white rounded-bl-3xl -z-0"></div>

                        <div class="relative z-10">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-2xl shadow-sm">
                                        {{ $cat->icon ?? '📦' }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800 text-lg leading-tight">{{ $cat->name }}</h4>
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full mt-1 inline-block">
                                            {{ $cat->products->count() }} Produk
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex gap-1">
                                    <button wire:click="editCategory({{ $cat->id }})" class="p-2 text-gray-400 hover:text-indigo-600 transition bg-gray-50 rounded-full">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>
                                    <button wire:click="deleteCategory({{ $cat->id }})" onclick="return confirm('Hapus kategori?')" class="p-2 text-gray-400 hover:text-red-600 transition bg-gray-50 rounded-full">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-gray-50">
                                <button wire:click="openProductModal({{ $cat->id }})" 
                                    class="w-full py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold shadow-md shadow-indigo-200 active:bg-indigo-700 active:scale-95 transition-transform flex items-center justify-center gap-2">
                                    <span>⚙️</span> Atur Isi Etalase
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($activeTab == 'discounts')
            <div class="relative">
                <input type="text" placeholder="Cari produk..." class="w-full pl-10 pr-4 py-3 rounded-xl border-none bg-white shadow-sm ring-1 ring-gray-200 focus:ring-2 focus:ring-pink-500 transition text-sm">
                <span class="absolute left-3 top-3.5 text-gray-400">🔍</span>
            </div>

            <div class="space-y-3 pb-10">
                @foreach($data['products'] as $product)
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col gap-3 transition hover:shadow-md">
                        
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-gray-800 text-base">{{ $product->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $product->unit }}</p>
                            </div>
                            @if($product->has_discount)
                                <span class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-1 rounded-full animate-pulse">
                                    HEMAT {{ number_format($product->sell_price - $product->discount_price) }}
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-1 rounded-full">
                                    NORMAL
                                </span>
                            @endif
                        </div>

                        <div class="bg-gray-50 rounded-xl p-3">
                            @if($editingProductId === $product->id)
                                <div class="grid grid-cols-2 gap-3 animate-fade-in">
                                    <div>
                                        <label class="text-[10px] font-bold text-gray-500 uppercase">Harga Asli</label>
                                        <input type="number" wire:model="editPrice" class="w-full p-2 text-sm border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-red-500 uppercase">Harga Diskon</label>
                                        <input type="number" wire:model="editDiscount" placeholder="0" class="w-full p-2 text-sm border-red-300 rounded-lg focus:ring-red-500 focus:border-red-500 bg-red-50">
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-3">
                                    <button wire:click="saveDiscount" class="flex-1 bg-green-600 text-white py-2 rounded-lg text-xs font-bold shadow-sm active:scale-95 transition">SIMPAN</button>
                                    <button wire:click="cancelEdit" class="px-4 bg-gray-200 text-gray-600 py-2 rounded-lg text-xs font-bold active:scale-95 transition">BATAL</button>
                                </div>
                            @else
                                <div class="flex items-center justify-between">
                                    <div>
                                        @if($product->discount_price > 0)
                                            <div class="text-xs text-gray-400 line-through">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</div>
                                            <div class="text-lg font-bold text-red-600">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                                        @else
                                            <div class="text-lg font-bold text-gray-800">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</div>
                                        @endif
                                    </div>
                                    <button wire:click="editDiscount({{ $product->id }})" class="w-10 h-10 rounded-full bg-white border border-gray-200 text-gray-600 shadow-sm flex items-center justify-center hover:bg-pink-50 hover:text-pink-600 transition active:scale-90">
                                        ✏️
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                
                <div class="pt-4">
                    {{ $data['products']->links() }} 
                </div>
            </div>
        @endif
    </div>

    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 backdrop-blur-sm bg-black/40 transition-opacity">
            <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden transform transition-all scale-100 p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $category_id ? 'Edit' : 'Buat' }} Kategori</h3>
                <p class="text-sm text-gray-500 mb-6">Kelompokkan produk agar mudah dicari.</p>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Kategori</label>
                        <input type="text" wire:model="name" placeholder="Contoh: Paket Hemat" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-indigo-500 transition py-3">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Ikon / Emoji</label>
                        <div class="flex gap-2">
                            <input type="text" wire:model="icon" placeholder="🔥" class="w-16 text-center rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-indigo-500 transition py-3 text-xl">
                            <div class="flex-1 flex items-center text-xs text-gray-400 bg-gray-50 px-3 rounded-xl border border-dashed border-gray-200">
                                Gunakan emoji HP Anda.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button wire:click="$set('isModalOpen', false)" class="flex-1 py-3 text-gray-600 font-bold hover:bg-gray-100 rounded-xl transition">Batal</button>
                    <button wire:click="saveCategory" class="flex-1 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 active:scale-95 transition">Simpan</button>
                </div>
            </div>
        </div>
    @endif

    @if($isProductModalOpen)
        <div class="fixed inset-0 z-50 flex justify-center items-end sm:items-center sm:p-4 backdrop-blur-sm bg-black/50 transition-opacity">
            <div class="bg-white w-full max-w-lg rounded-t-3xl sm:rounded-3xl shadow-2xl h-[85vh] sm:h-[80vh] flex flex-col transform transition-transform translate-y-0">
                
                <div class="w-full flex justify-center pt-3 pb-1">
                    <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
                </div>

                <div class="px-6 pb-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">Isi Etalase</h3>
                    <p class="text-sm text-gray-500">Pilih produk untuk kategori <span class="font-bold text-indigo-600">{{ $selectedCategory->name }}</span></p>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-2 bg-gray-50">
                    @foreach($allProducts as $p)
                        <label class="flex items-center space-x-3 bg-white p-3 rounded-xl border {{ in_array($p->id, $selectedProducts) ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50' : 'border-gray-200' }} shadow-sm cursor-pointer transition active:scale-[0.98]">
                            <div class="relative flex items-center justify-center">
                                <input type="checkbox" wire:model="selectedProducts" value="{{ $p->id }}" class="w-5 h-5 text-indigo-600 rounded-md border-gray-300 focus:ring-indigo-500 transition">
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-sm text-gray-800">{{ $p->name }}</div>
                                <div class="text-xs text-gray-500">Stok: {{ $p->current_stock }} {{ $p->unit }}</div>
                            </div>
                            <div class="font-bold text-sm text-gray-700">
                                Rp {{ number_format($p->sell_price/1000, 0) }}k
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="p-4 bg-white border-t border-gray-100 flex gap-3 pb-8 sm:pb-4">
                    <button wire:click="$set('isProductModalOpen', false)" class="w-1/3 py-3 text-gray-600 font-bold bg-gray-100 rounded-xl hover:bg-gray-200 transition">Tutup</button>
                    <button wire:click="saveCategoryProducts" class="flex-1 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-300 active:scale-95 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>