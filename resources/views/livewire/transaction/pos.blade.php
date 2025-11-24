<div x-data="{ showConfirm: false }" x-on:close-modal.window="showConfirm = false" 
     class="h-[calc(100vh-80px)] md:h-[calc(100vh-64px)] flex flex-col lg:flex-row bg-gray-50 overflow-hidden font-sans relative -m-4 sm:-m-6 lg:-m-8">
    
    {{-- STYLE: Hide Scrollbar & Print Styles --}}
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* PRINT STYLES: Khusus Thermal Printer 58mm/80mm */
        @media print {
            body * {
                visibility: hidden;
            }
            #receipt-area, #receipt-area * {
                visibility: visible;
            }
            #receipt-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
                background: white;
            }
            /* Hilangkan header/footer browser */
            @page { margin: 0; size: auto; }
        }
    </style>

    {{-- BAGIAN KIRI: DAFTAR PRODUK (Sama seperti sebelumnya) --}}
    <div class="flex-1 flex flex-col h-full overflow-hidden relative z-0">
        {{-- Search Bar --}}
        <div class="bg-white p-4 shadow-sm border-b border-gray-200 z-10 shrink-0 space-y-3">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" 
                    class="block w-full pl-10 pr-3 py-2.5 border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none sm:text-sm" 
                    placeholder="Cari produk...">
            </div>
            <div class="flex gap-2 overflow-x-auto pb-1 no-scrollbar scroll-smooth">
                <button wire:click="selectCategory('all')" 
                    class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap border {{ $category_id == 'all' ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-600 border-gray-200' }}">
                    Semua
                </button>
                @foreach($categories as $cat)
                    <button wire:click="selectCategory({{ $cat->id }})" 
                        class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap border {{ $category_id == $cat->id ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-200' }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="flex-1 overflow-y-auto p-4 bg-gray-50">
            @if(count($products) > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 pb-24 lg:pb-4">
                    @foreach($products as $product)
                        @php $isOos = $product->current_stock <= 0; @endphp
                        <button wire:click="addToCart({{ $product->id }})" @if($isOos) disabled @endif
                                class="group bg-white rounded-2xl p-4 border border-gray-100 shadow-sm transition-all duration-200 text-left flex flex-col h-full relative overflow-hidden {{ $isOos ? 'opacity-60 bg-gray-50 cursor-not-allowed' : 'hover:shadow-lg hover:border-blue-400' }}">
                            <div class="absolute top-2 right-2 text-[10px] px-2 py-0.5 rounded-full font-medium {{ $isOos ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600' }}">
                                {{ $isOos ? 'Habis' : 'Stok: ' . $product->current_stock }}
                            </div>
                            <div class="mb-3 mt-1">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center mb-3 transition-colors {{ $isOos ? 'bg-gray-200 text-gray-400' : 'bg-blue-50 text-blue-500 group-hover:bg-blue-600 group-hover:text-white' }}">
                                    <span class="font-bold text-lg">{{ substr($product->name, 0, 1) }}</span>
                                </div>
                                <h4 class="font-bold text-gray-800 text-sm leading-tight line-clamp-2 min-h-[2.5rem]">{{ $product->name }}</h4>
                            </div>
                            <div class="mt-auto">
                                <p class="text-xs text-gray-400 mb-0.5">Harga</p>
                                <p class="font-extrabold {{ $isOos ? 'text-gray-500' : 'text-blue-600' }} text-base">
                                    {{ number_format($product->sell_price/1000, 0) }}k
                                </p>
                            </div>
                            @if(!$isOos) <div class="absolute inset-0 bg-blue-500/0 group-active:bg-blue-500/5 transition-colors duration-75"></div> @endif
                        </button>
                    @endforeach
                </div>
            @else
                <div class="h-full flex flex-col items-center justify-center text-gray-400 pb-20 px-4 text-center">
                    <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <p class="font-medium text-lg text-gray-500">Produk tidak ditemukan</p>
                </div>
            @endif
        </div>
    </div>

    {{-- BAGIAN KANAN: KERANJANG & CHECKOUT --}}
    <div class="w-full lg:w-[400px] bg-white border-l border-gray-200 shadow-xl lg:shadow-none z-30 flex flex-col h-[50vh] lg:h-full absolute bottom-0 lg:relative rounded-t-3xl lg:rounded-none transition-transform duration-300 {{ count($cart) > 0 ? 'translate-y-0' : 'translate-y-[calc(100%-80px)] lg:translate-y-0' }}">
        
        {{-- Handle Mobile --}}
        <div class="lg:hidden w-full flex justify-center pt-3 pb-1 cursor-pointer" onclick="this.parentElement.classList.toggle('translate-y-[calc(100%-80px)]')">
            <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
        </div>

        {{-- Header Keranjang --}}
        <div class="px-5 py-4 border-b border-gray-100 bg-white flex justify-between items-center shrink-0">
            <h3 class="font-black text-gray-800 text-lg flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Current Order
            </h3>
            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-lg">{{ count($cart) }} Items</span>
        </div>

        {{-- List Barang --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50/50">
            @if(count($cart) > 0)
                @foreach($cart as $id => $item)
                    <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-sm flex gap-3">
                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 font-bold shrink-0">
                            {{ substr($item['name'], 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h5 class="font-bold text-gray-800 text-sm truncate">{{ $item['name'] }}</h5>
                            <p class="text-xs text-blue-600 font-semibold mt-0.5">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <div class="flex items-center gap-2 bg-gray-100 rounded-lg p-1">
                                <button wire:click="removeFromCart({{ $id }})" class="w-6 h-6 rounded bg-white text-gray-600 shadow-sm flex items-center justify-center hover:text-red-500 font-bold">-</button>
                                <span class="text-xs font-bold w-4 text-center">{{ $item['qty'] }}</span>
                                <button wire:click="addToCart({{ $id }})" class="w-6 h-6 rounded bg-blue-600 text-white shadow-sm flex items-center justify-center hover:bg-blue-700 font-bold">+</button>
                            </div>
                            <p class="text-xs font-bold text-gray-800">{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="h-full flex flex-col items-center justify-center text-gray-300">
                    <p class="text-sm">Keranjang kosong</p>
                </div>
            @endif
        </div>

        {{-- Footer Checkout --}}
        <div class="bg-white p-4 border-t border-gray-200 shadow-[0_-4px_10px_rgba(0,0,0,0.05)] shrink-0 pb-safe">
            
            {{-- Summary & Toggle --}}
            <div class="flex justify-between items-end mb-4">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Tagihan</p>
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h2>
                </div>
                <div class="flex bg-gray-100 p-1 rounded-lg">
                    <button wire:click="$set('is_debt', false)" class="px-3 py-1.5 text-xs font-bold rounded-md transition-all {{ !$is_debt ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-400' }}">Tunai</button>
                    <button wire:click="$set('is_debt', true)" class="px-3 py-1.5 text-xs font-bold rounded-md transition-all {{ $is_debt ? 'bg-white text-orange-600 shadow-sm' : 'text-gray-400' }}">Bon</button>
                </div>
            </div>

            {{-- Conditional Inputs --}}
            @if($is_debt)
                <div class="mb-3 space-y-2 animate-fade-in-down">
                    <select wire:model="contact_id" class="w-full bg-orange-50 border-orange-200 text-gray-800 text-sm rounded-xl py-2.5">
                        <option value="">Pilih Pelanggan...</option>
                        @foreach($customers as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                    </select>
                    <input wire:model="notes" type="text" class="w-full bg-white border-gray-200 text-gray-800 text-sm rounded-xl py-2.5" placeholder="Catatan...">
                </div>
            @else
                 <div class="mb-3">
                    <select wire:model="wallet_id" class="w-full bg-gray-50 border-gray-200 text-gray-800 text-sm rounded-xl py-2.5">
                        @foreach($wallets as $w) <option value="{{ $w->id }}">{{ $w->name }}</option> @endforeach
                    </select>
                </div>
            @endif

            {{-- [BARU] Checkbox Cetak Struk --}}
            <div class="flex items-center gap-2 mb-4">
                <input type="checkbox" id="print" wire:model="print_receipt" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                <label for="print" class="text-sm text-gray-600 font-medium select-none cursor-pointer">Cetak Struk Transaksi</label>
            </div>

            {{-- Checkout Button (Membuka Modal) --}}
            <button @click="showConfirm = true" 
                class="w-full py-3.5 rounded-xl font-bold text-white shadow-lg transition-all active:scale-[0.98] flex items-center justify-center gap-2 group
                {{ $is_debt ? 'bg-gradient-to-r from-orange-500 to-orange-600 shadow-orange-500/25' : 'bg-gradient-to-r from-blue-600 to-blue-700 shadow-blue-600/25' }}">
                <span>{{ $is_debt ? 'Simpan Utang' : 'Bayar Sekarang' }}</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </div>

    {{-- MODAL KONFIRMASI PEMBAYARAN --}}
    <div x-show="showConfirm" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity" 
             x-show="showConfirm"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="showConfirm = false"></div>

        {{-- Modal Content --}}
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm relative z-10 overflow-hidden"
             x-show="showConfirm"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Konfirmasi Pembayaran</h3>
                <p class="text-gray-500 text-sm mb-6">Pastikan jumlah uang yang diterima sesuai dengan total tagihan.</p>
                
                <div class="bg-gray-50 rounded-xl p-4 mb-6 text-left space-y-2 border border-gray-100">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Metode</span>
                        <span class="font-bold {{ $is_debt ? 'text-orange-600' : 'text-blue-600' }}">{{ $is_debt ? 'KASBON (Utang)' : 'TUNAI' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Item</span>
                        <span class="font-bold text-gray-800">{{ count($cart) }} pcs</span>
                    </div>
                    <div class="border-t border-gray-200 pt-2 flex justify-between items-center mt-2">
                        <span class="text-gray-900 font-bold">Total Bayar</span>
                        <span class="text-xl font-black text-gray-900">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button @click="showConfirm = false" class="flex-1 py-3 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50">Batal</button>
                    <button wire:click="checkout" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-600/30">
                        Proses {{ $is_debt ? 'Utang' : 'Bayar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- AREA CETAK STRUK (HIDDEN DARI TAMPILAN WEB, MUNCUL SAAT PRINT) --}}
    <div id="receipt-area" class="hidden text-black font-mono text-[12px] leading-tight p-2 bg-white max-w-[300px] mx-auto">
        <div class="text-center mb-4">
            <h2 class="font-bold text-lg uppercase mb-1" id="receipt-store">TOKO KITA</h2>
            <p class="text-[10px]" id="receipt-date">01/01/2024 12:00</p>
            <p class="text-[10px]">Kasir: <span id="receipt-cashier">-</span></p>
        </div>
        
        <div class="border-t border-b border-black py-2 my-2 border-dashed">
            <div id="receipt-items"></div>
        </div>

        <div class="flex justify-between font-bold text-sm mb-1">
            <span>TOTAL</span>
            <span id="receipt-total">0</span>
        </div>
        <div class="flex justify-between text-[10px] mb-4">
            <span id="receipt-payment">TUNAI</span>
            <span id="receipt-customer">-</span>
        </div>

        <div class="text-center text-[10px] mt-4">
            <p>Terima Kasih</p>
            <p>Barang yang dibeli tidak dapat ditukar</p>
        </div>
    </div>

</div>

{{-- SCRIPT CETAK --}}
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('trigger-print-receipt', (event) => {
            const data = event.data; // Data dikirim dari PHP
            
            // 1. Isi Data ke Receipt Area
            document.getElementById('receipt-store').innerText = data.store_name;
            document.getElementById('receipt-date').innerText = data.date;
            document.getElementById('receipt-cashier').innerText = data.cashier;
            document.getElementById('receipt-total').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.total);
            document.getElementById('receipt-payment').innerText = data.payment_type;
            document.getElementById('receipt-customer').innerText = data.customer;

            // Render Items
            let itemsHtml = '';
            for (const [key, item] of Object.entries(data.items)) {
                itemsHtml += `
                    <div class="mb-1">
                        <div class="font-bold truncate">${item.name}</div>
                        <div class="flex justify-between">
                            <span>${item.qty} x ${new Intl.NumberFormat('id-ID').format(item.price)}</span>
                            <span>${new Intl.NumberFormat('id-ID').format(item.qty * item.price)}</span>
                        </div>
                    </div>
                `;
            }
            document.getElementById('receipt-items').innerHTML = itemsHtml;

            // 2. Trigger Print
            // Android WebView biasanya akan menangkap window.print()
            setTimeout(() => {
                window.print();
            }, 500);
        });
    });
</script>