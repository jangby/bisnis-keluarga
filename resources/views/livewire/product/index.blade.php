<div class="min-h-screen bg-gray-50/50 pb-20 font-sans">

    {{-- HEADER & CONTROLS (Sticky) --}}
    <div class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            
            {{-- Title & Role Badge --}}
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Stok</h1>
                    <p class="text-xs text-gray-500 mt-1">Pantau ketersediaan barang secara real-time</p>
                </div>
                
                {{-- Tombol Tambah (Hanya Desktop) - Muncul di Header --}}
                @if(in_array(Auth::user()->role, ['owner', 'production']))
                    <a href="{{ route('products.create', ['type' => $filterType]) }}" wire:navigate class="hidden md:flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold text-sm transition shadow-lg shadow-blue-600/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah {{ $filterType == 'goods' ? 'Produk' : 'Bahan' }}
                    </a>
                @endif
            </div>

            {{-- Search & Filter Tabs --}}
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                
                {{-- Tabs Jenis Produk --}}
                <div class="flex bg-gray-100 p-1 rounded-xl w-full md:w-auto">
                    <button wire:click="$set('filterType', 'goods')" 
                        class="flex-1 md:flex-none px-6 py-2 text-sm font-bold rounded-lg transition-all duration-200 
                        {{ $filterType == 'goods' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        🛍️ Produk Jadi
                    </button>
                    <button wire:click="$set('filterType', 'material')" 
                        class="flex-1 md:flex-none px-6 py-2 text-sm font-bold rounded-lg transition-all duration-200 
                        {{ $filterType == 'material' ? 'bg-white text-orange-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        📦 Bahan Baku
                    </button>
                </div>

                {{-- Search Bar --}}
                <div class="relative w-full md:w-80">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </span>
                    <input wire:model.live.debounce.300ms="search" type="text" 
                        class="block w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 transition" 
                        placeholder="Cari nama atau kode...">
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        {{-- TAMPILAN DESKTOP (TABLE) --}}
        <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Info Produk</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Stok</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50 transition group">
                            {{-- Kolom Info --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-lg flex items-center justify-center font-bold text-lg
                                        {{ $filterType == 'goods' ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-600' }}">
                                        {{ substr($product->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-500 font-mono">CODE: {{ $product->code }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Kolom Kategori --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                    {{ $product->product_line->name ?? '-' }}
                                </span>
                            </td>

                            {{-- Kolom Stok --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php $isLow = $product->current_stock <= 5; @endphp
                                <span class="px-3 py-1 inline-flex items-center gap-1.5 text-sm font-bold rounded-lg
                                    {{ $isLow ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-green-50 text-green-600 border border-green-100' }}">
                                    @if($isLow) <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span></span> @endif
                                    {{ $product->current_stock }} {{ $product->unit }}
                                </span>
                            </td>

                            {{-- Kolom Harga --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-700">
                                Rp {{ number_format($filterType == 'goods' ? $product->sell_price : $product->base_price, 0, ',', '.') }}
                            </td>

                            {{-- Kolom Aksi --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @if(in_array(Auth::user()->role, ['owner', 'production']))
                                    <a href="{{ route('products.edit', $product->id) }}" wire:navigate class="text-blue-600 hover:text-blue-900 font-bold hover:underline">Edit</a>
                                @else
                                    <span class="text-gray-300 cursor-not-allowed">Locked</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    <p class="font-medium">Data tidak ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- TAMPILAN MOBILE (GRID CARDS) --}}
        <div class="grid grid-cols-1 gap-4 md:hidden">
            @forelse($products as $product)
                @php 
                    $canEdit = in_array(Auth::user()->role, ['owner', 'production']);
                    $isLow = $product->current_stock <= 5; 
                @endphp
                
                <div class="relative group">
                    @if($canEdit) <a href="{{ route('products.edit', $product->id) }}" wire:navigate class="block"> @endif

                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm active:scale-[0.99] transition-all flex items-center gap-4 {{ $isLow ? 'ring-2 ring-red-100 bg-red-50/20' : '' }}">
                        {{-- Icon --}}
                        <div class="shrink-0 h-12 w-12 rounded-xl flex items-center justify-center text-lg font-bold shadow-sm
                            {{ $filterType == 'goods' ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-600' }}">
                            {{ substr($product->name, 0, 1) }}
                        </div>

                        {{-- Text Info --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-bold text-gray-900 truncate">{{ $product->name }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="bg-gray-100 text-gray-500 text-[10px] px-2 py-0.5 rounded border border-gray-200">{{ $product->product_line->name ?? 'N/A' }}</span>
                                <span class="text-[10px] text-gray-400 font-mono">#{{ $product->code }}</span>
                            </div>
                        </div>

                        {{-- Price & Stock --}}
                        <div class="text-right">
                            <p class="text-sm font-bold {{ $filterType == 'goods' ? 'text-blue-600' : 'text-orange-600' }}">
                                {{ number_format($filterType == 'goods' ? $product->sell_price/1000 : $product->base_price/1000, 0) }}k
                            </p>
                            <div class="mt-1 inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold border
                                {{ $isLow ? 'bg-red-100 text-red-600 border-red-200' : 'bg-green-100 text-green-600 border-green-200' }}">
                                {{ $product->current_stock }}
                            </div>
                        </div>
                    </div>
                    @if($canEdit) </a> @endif
                </div>
            @empty
                <div class="text-center py-12 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <p class="text-sm">Tidak ada produk ditemukan</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>

    {{-- Floating Action Button (FAB) - Mobile Only --}}
    @if(in_array(Auth::user()->role, ['owner', 'production']))
        <a href="{{ route('products.create', ['type' => $filterType]) }}" wire:navigate
            class="md:hidden fixed bottom-24 right-5 w-14 h-14 rounded-full shadow-xl shadow-blue-600/30 flex items-center justify-center transition-transform active:scale-90 z-40 border-2 border-white
            {{ $filterType == 'goods' ? 'bg-blue-600 text-white' : 'bg-orange-600 text-white' }}">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
        </a>
    @endif

</div>