<div class="min-h-screen bg-gray-50 pb-32 font-sans text-gray-800">

    {{-- HEADER --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-4 flex items-center gap-3">
            <a href="{{ route('dashboard') }}" wire:navigate class="group p-2 -ml-2 rounded-full hover:bg-gray-100 transition-colors text-gray-500 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-gray-900 leading-none">Belanja Bahan</h1>
                <p class="text-xs text-gray-500 mt-1">Restock gudang & catat pengeluaran.</p>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6">
        
        {{-- FORM --}}
        <div class="space-y-6">

            {{-- 1. TOTAL HARGA --}}
            <div class="bg-gradient-to-br from-indigo-600 to-blue-700 p-6 rounded-3xl shadow-lg shadow-indigo-500/30 text-white relative overflow-hidden text-center group">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>

                <label class="block text-xs font-bold text-indigo-100 uppercase tracking-widest mb-2 relative z-10">Total Belanja (Rupiah)</label>
                
                <div class="relative inline-block w-full max-w-xs mx-auto z-10">
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 text-2xl font-medium text-indigo-200">Rp</span>
                    <input wire:model.live.debounce.500ms="total_cost" type="number" inputmode="numeric" placeholder="0"
                        class="w-full pl-10 pr-4 py-2 text-4xl md:text-5xl font-black text-white bg-transparent border-none focus:ring-0 placeholder-indigo-400/50 text-center tracking-tight">
                </div>
                @error('total_cost') <p class="text-red-200 text-xs mt-2 font-bold bg-red-500/20 py-1 px-3 rounded-full inline-block">{{ $message }}</p> @enderror

                @if($unit_price > 0)
                    <div class="mt-4 bg-white/10 backdrop-blur-sm rounded-xl p-2 inline-flex items-center gap-2 border border-white/10 animate-fade-in-up">
                        <span class="text-xs text-indigo-100">Harga Modal Baru:</span>
                        <span class="text-sm font-bold text-white">Rp {{ number_format($unit_price, 0, ',', '.') }} / Unit</span>
                    </div>
                @endif
            </div>

            {{-- 2. DETAIL BARANG --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 space-y-5">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span> Apa yang dibeli?
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">Nama Bahan Baku</label>
                        <div class="relative">
                            <select wire:model.live="product_id" class="w-full pl-4 pr-10 py-3 rounded-xl border-gray-200 text-sm font-bold text-gray-800 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 focus:bg-white transition-colors">
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} (Sisa: {{ $p->current_stock }} {{ $p->unit }})</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @error('product_id') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">Jumlah Beli</label>
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="quantity" type="number" step="0.01" placeholder="0"
                                class="w-full pl-4 pr-12 py-3 rounded-xl border-gray-200 text-lg font-bold text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 focus:bg-white">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase pointer-events-none">
                                @php $selectedProduct = $products->find($product_id); @endphp
                                {{ $selectedProduct ? $selectedProduct->unit : 'Unit' }}
                            </span>
                        </div>
                        @error('quantity') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- 3. PEMBAYARAN --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 space-y-5">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Pembayaran & Catatan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">Pakai Uang Dompet</label>
                        <select wire:model="wallet_id" class="w-full rounded-xl border-gray-200 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                            @foreach($wallets as $w)
                                <option value="{{ $w->id }}">{{ $w->name }} (Rp {{ number_format($w->balance, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">Supplier (Opsional)</label>
                        <select wire:model="supplier_id" class="w-full rounded-xl border-gray-200 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                            <option value="">-- Beli Umum --</option>
                            @foreach($suppliers as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">Tanggal Beli</label>
                        <input wire:model="date" type="date" class="w-full rounded-xl border-gray-200 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">Catatan</label>
                        <input wire:model="notes" type="text" class="w-full rounded-xl border-gray-200 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 placeholder-gray-400" placeholder="Contoh: Nota No. 123...">
                    </div>
                </div>
            </div>

            {{-- FOOTER BUTTON (Trigger Modal) --}}
            <div class="fixed bottom-[64px] left-0 right-0 p-4 bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] md:static md:bg-transparent md:border-none md:p-0 z-20">
                <div class="max-w-3xl mx-auto flex gap-3">
                    <a href="{{ route('dashboard') }}" class="hidden md:flex px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                        Batal
                    </a>
                    {{-- Perubahan: type="button" dan panggil confirmSave --}}
                    <button type="button" wire:click="confirmSave" 
                        class="flex-1 py-4 rounded-xl font-bold text-white shadow-lg shadow-indigo-500/30 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 transform active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 text-indigo-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>KONFIRMASI BELI</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL KONFIRMASI --}}
    @if($showConfirmationModal)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center" x-data x-init="$el.classList.add('active')">
            {{-- Backdrop --}}
            <div wire:click="$set('showConfirmationModal', false)" class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity cursor-pointer"></div>

            {{-- Modal Content --}}
            <div class="bg-white w-full max-w-sm sm:rounded-2xl rounded-t-2xl p-6 relative z-10 shadow-2xl transform transition-transform animate-slide-up-mobile sm:animate-pop-in">
                
                <div class="w-12 h-1.5 bg-gray-300 rounded-full mx-auto mb-6 sm:hidden"></div>

                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Konfirmasi Belanja</h3>
                    <p class="text-sm text-gray-500">Pastikan data pembelian sudah benar.</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 space-y-3 mb-6 border border-gray-100">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Bayar</span>
                        <span class="font-black text-gray-900 text-lg">Rp {{ number_format($total_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Barang</span>
                        <span class="font-medium text-gray-800 text-right">
                            @php $p = $products->find($product_id); @endphp
                            {{ $p ? $p->name : '-' }}<br>
                            <span class="text-xs text-gray-400">({{ $quantity }} {{ $p ? $p->unit : '' }})</span>
                        </span>
                    </div>
                    <div class="flex justify-between text-sm pt-2 border-t border-gray-200">
                        <span class="text-gray-500">Sumber Dana</span>
                        <span class="font-bold text-gray-800">
                            @php $w = $wallets->find($wallet_id); @endphp
                            {{ $w ? $w->name : '-' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button wire:click="$set('showConfirmationModal', false)" class="py-3.5 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button wire:click="save" class="py-3.5 rounded-xl font-bold text-white shadow-lg bg-indigo-600 hover:bg-indigo-700">
                        Ya, Proses
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>