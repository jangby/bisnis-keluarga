<div class="bg-gray-50 min-h-screen pb-32">

    <div class="bg-white p-4 sticky top-0 shadow-sm z-20">
        <div class="flex bg-gray-100 p-1 rounded-xl">
            <button wire:click="$set('type', 'income')" 
                class="flex-1 py-2 rounded-lg text-sm font-bold transition flex items-center justify-center gap-2
                {{ $type === 'income' ? 'bg-green-500 text-white shadow-md' : 'text-gray-500 hover:bg-gray-200' }}">
                <span>⬇️</span> Pemasukan
            </button>
            <button wire:click="$set('type', 'expense')" 
                class="flex-1 py-2 rounded-lg text-sm font-bold transition flex items-center justify-center gap-2
                {{ $type === 'expense' ? 'bg-red-500 text-white shadow-md' : 'text-gray-500 hover:bg-gray-200' }}">
                <span>⬆️</span> Pengeluaran
            </button>
        </div>
    </div>

    <form wire:submit="save" class="p-4 space-y-5">
        
        <div class="bg-white p-4 rounded-xl border border-gray-200">
            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Total Nominal</label>
            <div class="relative">
                <span class="absolute left-0 top-2 text-lg font-bold text-gray-400">Rp</span>
                <input wire:model="amount" type="number" inputmode="numeric" placeholder="0"
                    class="w-full pl-8 pr-4 py-2 text-2xl font-bold text-gray-800 border-none focus:ring-0 border-b border-gray-200 placeholder-gray-300">
            </div>
            @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
            <select wire:model.live="category" class="w-full rounded-xl border-gray-300 text-sm py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        @if($this->needsProductInput)
        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 space-y-3 animate-fade-in-down">
            <h4 class="text-xs font-bold text-blue-700 uppercase flex items-center gap-1">
                📦 Detail Barang
            </h4>
            
            <div>
                <label class="text-[10px] text-blue-600 font-bold">Nama Barang</label>
                <select wire:model="product_id" class="w-full mt-1 rounded-lg border-blue-200 text-sm focus:ring-blue-500">
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} (Stok: {{ $p->current_stock }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <div class="flex-1">
                    <label class="text-[10px] text-blue-600 font-bold">Jumlah</label>
                    <input wire:model="quantity" type="number" class="w-full mt-1 rounded-lg border-blue-200 text-sm">
                </div>
                <div class="w-1/3">
                    <label class="text-[10px] text-blue-600 font-bold">Satuan</label>
                    <select wire:model="unit" class="w-full mt-1 rounded-lg border-blue-200 text-sm">
                        <option value="Pcs">Pcs</option>
                        <option value="Lusin">Lusin</option>
                        <option value="Dus">Dus</option>
                        <option value="Kg">Kg</option>
                        <option value="Liter">Liter</option>
                        <option value="Order">Order</option>
                    </select>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Kontak (Opsional)</label>
                <select wire:model="contact_id" class="w-full rounded-lg border-gray-300 text-sm">
                    <option value="">-- Umum --</option>
                    @foreach($contacts as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Divisi/Produk</label>
                <select wire:model="product_line_id" class="w-full rounded-lg border-gray-300 text-sm bg-yellow-50">
                    @foreach($productLines as $line)
                        <option value="{{ $line->id }}">{{ $line->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3">
            <div class="col-span-1">
                <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal</label>
                <input wire:model="date" type="date" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div class="col-span-2">
                <label class="block text-xs font-medium text-gray-500 mb-1">Catatan</label>
                <input wire:model="notes" type="text" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Ket. Singkat">
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <label class="block text-xs font-bold text-gray-800 uppercase mb-3">Metode Pembayaran</label>
            
            <div class="grid grid-cols-3 gap-2 mb-4">
                <button type="button" wire:click="$set('payment_method', 'cash')" 
                    class="py-2 rounded-lg text-xs font-bold border {{ $payment_method == 'cash' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-200' }}">
                    💵 Tunai
                </button>
                <button type="button" wire:click="$set('payment_method', 'transfer')" 
                    class="py-2 rounded-lg text-xs font-bold border {{ $payment_method == 'transfer' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-200' }}">
                    💳 Transfer
                </button>
                <button type="button" wire:click="$set('payment_method', 'debt')" 
                    class="py-2 rounded-lg text-xs font-bold border {{ $payment_method == 'debt' ? 'bg-orange-500 text-white border-orange-500' : 'bg-white text-gray-600 border-gray-200' }}">
                    ⏳ Hutang
                </button>
            </div>

            @if($payment_method == 'debt')
                <div class="bg-orange-50 p-3 rounded-lg animate-pulse">
                    <label class="block text-xs font-bold text-orange-800 mb-1">Jatuh Tempo (Wajib)</label>
                    <input wire:model="due_date" type="date" class="w-full rounded-lg border-orange-300 text-sm">
                </div>
            @else
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Masuk/Keluar Dompet</label>
                    <select wire:model="wallet_id" class="w-full rounded-lg border-gray-300 text-sm">
                        @foreach($wallets as $w)
                            <option value="{{ $w->id }}">{{ $w->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <button type="submit" 
            class="fixed bottom-4 left-4 right-4 py-4 rounded-xl font-bold text-white shadow-2xl transform active:scale-95 transition z-30
            {{ $type === 'income' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
            SIMPAN {{ $type === 'income' ? 'PEMASUKAN' : 'PENGELUARAN' }}
        </button>

    </form>
</div>