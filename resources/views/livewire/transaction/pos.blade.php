<div class="bg-gray-100 min-h-screen pb-32 -mx-4 -mt-4 font-sans">

    {{-- HEADER MOBILE --}}
    <div class="bg-white p-4 sticky top-0 z-30 shadow-sm border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-xl text-gray-900">Kasir Penjualan</h2>
            <div class="text-right">
                <p class="text-[10px] text-gray-500 uppercase font-bold">Total Belanja</p>
                <p class="text-lg font-black text-blue-600">Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- ERROR MESSAGE --}}
    @if (session()->has('error'))
        <div class="bg-red-500 text-white px-4 py-3 text-center text-sm font-bold sticky top-[72px] z-40 animate-pulse">
            {{ session('error') }}
        </div>
    @endif
    
    @if (session()->has('message'))
        <div class="bg-green-500 text-white px-4 py-3 text-center text-sm font-bold sticky top-[72px] z-40">
            {{ session('message') }}
        </div>
    @endif

    <div class="p-4 grid gap-6">
        
        {{-- 1. LIST PRODUK (GRID) --}}
        <div>
            <h3 class="text-xs font-bold text-gray-500 uppercase mb-3">Pilih Produk</h3>
            <div class="grid grid-cols-2 gap-3">
                @foreach($products as $product)
                    <button wire:click="addToCart({{ $product->id }})" 
                            class="bg-white p-3 rounded-xl shadow-sm border border-gray-200 text-left hover:border-blue-500 hover:shadow-md transition group relative overflow-hidden">
                        
                        {{-- Efek Klik --}}
                        <div class="absolute inset-0 bg-blue-50 opacity-0 group-active:opacity-100 transition"></div>

                        <div class="relative z-10">
                            <h4 class="font-bold text-gray-800 text-sm line-clamp-2 leading-tight h-10">{{ $product->name }}</h4>
                            <div class="mt-2 flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] text-gray-400">Stok: {{ $product->current_stock }}</p>
                                    <p class="font-black text-blue-700 text-sm">Rp {{ number_format($product->sell_price/1000, 0) }}k</p>
                                </div>
                                <div class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-lg shadow-blue-600/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- 2. KERANJANG BELANJA --}}
        @if(count($cart) > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gray-800 px-4 py-3 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Keranjang
                    </h3>
                    <span class="bg-gray-700 text-white text-xs px-2 py-1 rounded">{{ count($cart) }} Item</span>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach($cart as $id => $item)
                        <div class="p-3 flex justify-between items-center">
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500">Rp {{ number_format($item['price'], 0, ',', '.') }} x {{ $item['qty'] }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button wire:click="removeFromCart({{ $id }})" class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <span class="font-bold text-gray-900 w-4 text-center">{{ $item['qty'] }}</span>
                                <button wire:click="addToCart({{ $id }})" class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center hover:bg-green-200 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{-- 3. OPSI PEMBAYARAN (KASBON/TUNAI) --}}
                <div class="bg-gray-50 p-4 border-t border-gray-200 space-y-4">
                    
                    {{-- Toggle Tunai / Kasbon --}}
                    <div class="flex bg-white p-1 rounded-xl border border-gray-300 shadow-sm">
                        <button type="button" wire:click="$set('is_debt', false)" 
                            class="flex-1 py-2 rounded-lg text-sm font-bold transition {{ !$is_debt ? 'bg-green-500 text-white shadow' : 'text-gray-500 hover:bg-gray-50' }}">
                            TUNAI (Lunas)
                        </button>
                        <button type="button" wire:click="$set('is_debt', true)" 
                            class="flex-1 py-2 rounded-lg text-sm font-bold transition {{ $is_debt ? 'bg-orange-500 text-white shadow' : 'text-gray-500 hover:bg-gray-50' }}">
                            KASBON (Utang)
                        </button>
                    </div>

                    {{-- Form Khusus Kasbon --}}
                    @if($is_debt)
                        <div class="bg-orange-50 p-3 rounded-xl border border-orange-200 animate-fade-in-down">
                            <div class="mb-3">
                                <label class="text-xs font-bold text-orange-800 uppercase mb-1 block">Siapa yang Ngutang?</label>
                                <select wire:model="contact_id" class="w-full rounded-lg border-orange-300 text-sm focus:ring-orange-500 focus:border-orange-500">
                                    <option value="">-- Pilih Pelanggan --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @if(count($customers) == 0)
                                    <a href="{{ route('settings.index') }}" class="text-[10px] text-blue-600 underline mt-1 block">Belum ada data pelanggan? Tambah di Setting</a>
                                @endif
                            </div>
                            <div>
                                <label class="text-xs font-bold text-orange-800 uppercase mb-1 block">Catatan (Opsional)</label>
                                <input wire:model="notes" type="text" class="w-full rounded-lg border-orange-300 text-sm" placeholder="Contoh: Bayar minggu depan">
                            </div>
                        </div>
                    @else
                        {{-- Pilihan Dompet untuk Tunai --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Uang Masuk Ke:</label>
                            <select wire:model="wallet_id" class="w-full rounded-lg border-gray-300 text-sm bg-white">
                                @foreach($wallets as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Total Akhir --}}
                    <div class="flex justify-between items-center pt-2">
                        <span class="font-bold text-gray-600">Total Bayar</span>
                        <span class="font-black text-xl text-gray-900">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                    </div>

                    {{-- Tombol Checkout --}}
                    <button wire:click="checkout" 
                        class="w-full py-4 rounded-xl font-black text-white text-lg shadow-lg transition transform active:scale-95 flex items-center justify-center gap-2
                        {{ $is_debt ? 'bg-orange-500 hover:bg-orange-600 shadow-orange-500/30' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-600/30' }}">
                        
                        @if($is_debt)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>SIMPAN KASBON</span>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z" />
                            </svg>
                            <span>BAYAR SEKARANG</span>
                        @endif
                    </button>

                </div>
            </div>
        @else
            <div class="text-center py-12 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-sm">Keranjang masih kosong.</p>
                <p class="text-xs">Pilih produk di atas untuk memulai transaksi.</p>
            </div>
        @endif
    </div>
</div>