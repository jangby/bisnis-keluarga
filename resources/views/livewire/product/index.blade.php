<div class="min-h-screen bg-gray-50 pb-28 font-sans text-gray-800">

    <div class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm">
        
        <div class="px-4 pt-4 pb-2 flex justify-between items-center">
            <h2 class="text-lg font-bold tracking-tight text-gray-900">Stok Gudang</h2>
            <span class="px-2.5 py-1 bg-gray-100 text-[10px] font-semibold tracking-wider uppercase text-gray-500 rounded-full border border-gray-200">
                {{ Auth::user()->role }}
            </span>
        </div>

        <div class="px-4 pb-3 space-y-3">
            
            <div class="relative shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Cari {{ $filterType == 'goods' ? 'produk' : 'bahan' }}..." 
                    class="block w-full pl-10 pr-3 py-2.5 border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors placeholder-gray-400">
            </div>

            <div class="flex p-1 bg-gray-100/80 rounded-xl">
                <button wire:click="$set('filterType', 'goods')" 
                    class="flex-1 flex items-center justify-center gap-2 py-2 text-xs font-bold rounded-lg transition-all duration-200 ease-out
                    {{ $filterType == 'goods' ? 'bg-white text-blue-600 shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-600 hover:bg-gray-200/50' }}">
                    <span>🛍️</span> Produk Jadi
                </button>
                <button wire:click="$set('filterType', 'material')" 
                    class="flex-1 flex items-center justify-center gap-2 py-2 text-xs font-bold rounded-lg transition-all duration-200 ease-out
                    {{ $filterType == 'material' ? 'bg-white text-orange-600 shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-600 hover:bg-gray-200/50' }}">
                    <span>📦</span> Bahan Baku
                </button>
            </div>
        </div>
    </div>

    {{-- Perubahan disini: px-4 (lebih lebar pinggirnya) dan space-y-4 (jarak antar kartu lebih jauh) --}}
    <div class="px-4 py-4 space-y-4">
        
        @forelse($products as $product)
            @php
                $canEdit = in_array(Auth::user()->role, ['owner', 'production']);
                $isLowStock = $product->current_stock <= 5;
            @endphp

            <div class="relative group">
                @if($canEdit)
                <a href="{{ route('products.edit', $product->id) }}" wire:navigate class="block">
                @endif

                <div class="bg-white p-3.5 rounded-2xl border border-gray-100 shadow-[0_2px_8px_-4px_rgba(0,0,0,0.05)] active:scale-[0.98] transition-transform duration-100 flex items-center gap-3.5 {{ $isLowStock ? 'ring-1 ring-red-100 bg-red-50/30' : '' }}">
                    
                    <div class="shrink-0">
                        <div class="h-11 w-11 rounded-xl flex items-center justify-center text-sm font-bold shadow-sm border border-gray-50
                            {{ $filterType == 'goods' ? 'bg-blue-50 text-blue-600' : 'bg-orange-50 text-orange-600' }}">
                            {{ substr($product->name, 0, 1) }}
                        </div>
                    </div>

                    <div class="flex-1 min-w-0 grid gap-1">
                        <h3 class="text-sm font-bold text-gray-800 truncate pr-2 leading-snug">
                            {{ $product->name }}
                        </h3>
                        
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-medium px-2 py-0.5 rounded text-gray-500 bg-gray-100 border border-gray-200/60">
                                {{ $product->product_line->name ?? 'N/A' }}
                            </span>
                            <span class="text-[10px] text-gray-400 font-mono tracking-tight">
                                #{{ $product->code }}
                            </span>
                        </div>
                    </div>

                    <div class="text-right shrink-0 flex flex-col justify-center items-end gap-1">
                        <span class="text-sm font-bold tracking-tight {{ $filterType == 'goods' ? 'text-blue-600' : 'text-orange-600' }}">
                            Rp {{ number_format($filterType == 'goods' ? $product->sell_price : $product->base_price, 0, ',', '.') }}
                        </span>
                        
                        <div class="flex items-center justify-end gap-1 text-xs font-medium text-gray-600 bg-gray-50 px-2 py-0.5 rounded-md border border-gray-100">
                            <span class="{{ $isLowStock ? 'text-red-600 font-bold' : '' }}">
                                {{ $product->current_stock + 0 }}
                            </span>
                            <span class="text-[10px] text-gray-400 uppercase">{{ $product->unit }}</span>
                            @if($isLowStock)
                                <span class="relative flex h-2 w-2 ml-0.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($canEdit) </a> @endif
            </div>

        @empty
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="bg-gray-100 p-4 rounded-full mb-3">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <p class="text-sm font-medium text-gray-500">Tidak ada data ditemukan</p>
            </div>
        @endforelse
        
        <div class="pt-4 pb-6 px-1">
             {{ $products->links() }} 
        </div>
    </div>

    @if(in_array(Auth::user()->role, ['owner', 'production']))
        {{-- Fixed Position: bottom-6 right-5, z-50 agar selalu diatas --}}
        <a href="{{ route('products.create', ['type' => $filterType]) }}" wire:navigate
            class="fixed bottom-6 right-5 w-14 h-14 rounded-full shadow-[0_4px_20px_rgba(0,0,0,0.25)] flex items-center justify-center transition-transform active:scale-90 z-50 border-2 border-white
            {{ $filterType == 'goods' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-orange-600 hover:bg-orange-700 text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
        </a>
    @endif

</div>