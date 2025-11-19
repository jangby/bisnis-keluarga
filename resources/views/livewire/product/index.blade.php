<div class="space-y-4 min-h-screen pb-24 bg-gray-50">
    
    <div class="bg-white p-4 sticky top-0 z-10 shadow-sm">
        <div class="flex justify-between items-center mb-2">
            <h2 class="font-bold text-lg text-gray-800">Stok Gudang</h2>
            
            <span class="px-2 py-1 bg-gray-100 text-xs rounded-full text-gray-500 border border-gray-200 capitalize">
                {{ Auth::user()->role }}
            </span>
        </div>

        <div class="relative">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari produk..." 
                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm bg-gray-50">
            <div class="absolute left-3 top-2.5 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="px-4 space-y-3">
        
        @forelse($products as $product)
            
            @php
                $canEdit = in_array(Auth::user()->role, ['owner', 'production']);
            @endphp

            @if($canEdit)
                <a href="{{ route('products.edit', $product->id) }}" wire:navigate class="block group relative">
            @else
                <div class="block relative">
            @endif

                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex gap-3 items-center transition hover:shadow-md {{ $canEdit ? 'group-hover:border-blue-400' : '' }}">
                    
                    @if($canEdit)
                        <div class="absolute top-2 right-2 text-gray-300 group-hover:text-blue-500 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </div>
                    @endif

                    <div class="h-12 w-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-xl shrink-0">
                        {{ substr($product->name, 0, 1) }}
                    </div>

                    <div class="flex-1">
                        <div class="flex justify-between items-start pr-4">
                            <h3 class="font-semibold text-gray-800 text-sm line-clamp-1">{{ $product->name }}</h3>
                        </div>
                        
                        <div class="flex gap-2 items-center mt-0.5">
                             <span class="text-[10px] px-2 py-0.5 rounded {{ str_contains(strtolower($product->product_line->name), 'kecap') ? 'bg-yellow-100 text-yellow-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $product->product_line->name ?? 'Umum' }}
                            </span>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider">{{ $product->code }}</p>
                        </div>
                        
                        <div class="flex justify-between items-end mt-2 border-t border-gray-50 pt-2">
                            <div>
                                <p class="text-[10px] text-gray-400">Stok Gudang</p>
                                <p class="font-bold text-sm {{ $product->current_stock < 10 ? 'text-red-600' : 'text-gray-800' }}">
                                    {{ $product->current_stock }} <span class="text-xs font-normal text-gray-500">{{ $product->unit }}</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-400">Harga Jual</p>
                                <p class="font-bold text-sm text-blue-600">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                </div>

            @if($canEdit) </a> @else </div> @endif

        @empty
            <div class="text-center py-16 opacity-50">
                <div class="text-5xl mb-4 grayscale">📦</div>
                <p class="text-gray-600 font-medium">Data produk tidak ditemukan.</p>
                @if(in_array(Auth::user()->role, ['owner', 'production']))
                    <p class="text-xs text-gray-400 mt-1">Klik tombol + untuk menambah.</p>
                @endif
            </div>
        @endforelse

        <div class="mt-4">
            {{ $products->links() }}
        </div>

    </div>

    @if(in_array(Auth::user()->role, ['owner', 'production']))
        <a href="{{ route('products.create') }}" wire:navigate
            class="fixed bottom-20 right-4 bg-blue-600 text-white w-14 h-14 rounded-full shadow-2xl flex items-center justify-center hover:bg-blue-700 focus:outline-none z-40 transition transform hover:scale-110 active:scale-95 ring-4 ring-blue-600/30"
            title="Tambah Produk Baru">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </a>
    @endif

</div>