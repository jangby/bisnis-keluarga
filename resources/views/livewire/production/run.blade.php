<div class="bg-gray-50/50 min-h-screen pb-32 font-sans">

    {{-- ========================================================== --}}
    {{-- 1. HEADER HALAMAN UTAMA (CLEAN)                            --}}
    {{-- ========================================================== --}}
    <div class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" wire:navigate class="group p-2 rounded-full hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-gray-900 leading-tight">Produksi Harian</h1>
                <p class="text-xs text-gray-500">Rekap aktivitas produksi hari ini</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            {{-- IKON JAM (TOMBOL MENUJU RIWAYAT) --}}
            <a href="{{ route('production.history') }}" wire:navigate class="p-2.5 rounded-full text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition active:scale-95 border border-transparent hover:border-blue-100 relative group" title="Lihat Semua Riwayat">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                
                {{-- Tooltip kecil (opsional) --}}
                <span class="absolute top-10 right-0 bg-gray-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">Riwayat Lengkap</span>
            </a>

            {{-- Tombol TAMBAH (Membuka Modal) --}}
            <button wire:click="openModal" class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-xl font-bold shadow-lg shadow-blue-600/20 transform active:scale-95 transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span class="hidden sm:inline">Input Baru</span>
            </button>
        </div>
    </div>
    </div>

    {{-- ========================================================== --}}
    {{-- 2. KONTEN DASHBOARD PRODUKSI (STATISTIK & LOG)             --}}
    {{-- ========================================================== --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 space-y-6">

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="bg-green-50 p-4 rounded-2xl border border-green-100 flex flex-col justify-center">
                <span class="text-xs font-bold text-green-600 uppercase">Total Produksi</span>
                <span class="text-2xl font-black text-green-700 mt-1">{{ number_format($this->todayStats['total_pcs']) }} <span class="text-sm font-medium text-green-500">Pcs</span></span>
            </div>
            <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 flex flex-col justify-center">
                <span class="text-xs font-bold text-blue-600 uppercase">Input Batch</span>
                <span class="text-2xl font-black text-blue-700 mt-1">{{ $this->todayStats['total_batch'] }} <span class="text-sm font-medium text-blue-500">x</span></span>
            </div>
            <div class="col-span-2 md:col-span-1 bg-purple-50 p-4 rounded-2xl border border-purple-100 flex flex-col justify-center">
                <span class="text-xs font-bold text-purple-600 uppercase">Varian</span>
                <span class="text-2xl font-black text-purple-700 mt-1">{{ $this->todayStats['products_count'] }} <span class="text-sm font-medium text-purple-500">Jenis</span></span>
            </div>
        </div>

        {{-- Timeline Log --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-sm">Riwayat Input Hari Ini</h3>
                <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-1 rounded-md font-bold">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($this->todayLogs as $log)
                    <div class="p-4 hover:bg-gray-50 transition flex items-center gap-4">
                        <div class="flex flex-col items-center min-w-[50px]">
                            <span class="text-sm font-bold text-gray-800">{{ $log->created_at->format('H:i') }}</span>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-800 text-sm truncate">{{ $log->product->name ?? 'Produk Dihapus' }}</h4>
                            <p class="text-xs text-gray-500">{{ $log->notes }}</p>
                        </div>
                        <div class="text-right">
                            <span class="block font-bold text-green-600 text-sm">+{{ number_format($log->quantity) }}</span>
                            <span class="text-[10px] text-gray-400 font-bold">{{ $log->product->unit ?? 'Pcs' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <p class="text-sm font-medium">Belum ada data produksi hari ini.</p>
                        <p class="text-xs mt-1">Tekan tombol "Input Baru" di atas untuk mulai.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>


    {{-- ========================================================== --}}
    {{-- 3. MODAL FORM INPUT PRODUKSI                               --}}
    {{-- ========================================================== --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            {{-- Backdrop Gelap --}}
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

            <div class="flex min-h-full items-end justify-center p-0 sm:items-center sm:p-4 text-center">
                {{-- Panel Modal --}}
                <div class="relative transform overflow-hidden rounded-t-2xl sm:rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl w-full border border-gray-100">
                    
                    {{-- Header Modal --}}
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span class="w-2 h-6 bg-orange-500 rounded-full"></span>
                            Input Produksi Baru
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    {{-- Body Modal (Scrollable) --}}
                    <div class="px-4 py-5 sm:p-6 max-h-[75vh] overflow-y-auto bg-white">
                        <form wire:submit.prevent="save" id="productionForm">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                
                                {{-- KIRI: Input Utama --}}
                                <div class="md:col-span-1 space-y-4">
                                    <div>
                                        <label class="text-xs font-bold text-gray-500 mb-1 block">Produk</label>
                                        <select wire:model.live="product_id" class="w-full rounded-xl border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5">
                                            <option value="">-- Pilih --</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('product_id') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-gray-500 mb-1 block">Jumlah (Pcs)</label>
                                        <input wire:model.live.debounce.500ms="quantity_produced" type="number" class="w-full rounded-xl border-gray-300 font-bold text-gray-900 text-lg py-2" placeholder="0">
                                        @error('quantity_produced') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-gray-500 mb-1 block">Tanggal</label>
                                        <input wire:model="date" type="date" class="w-full rounded-xl border-gray-300 text-sm py-2">
                                    </div>
                                </div>

                                {{-- KANAN: Kalkulasi Bahan --}}
                                <div class="md:col-span-2 bg-gray-50 rounded-xl p-4 border border-gray-100">
                                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-3 flex justify-between">
                                        <span>Estimasi Bahan Baku</span>
                                        @if(count($materialsUsed) > 0) 
                                            <span class="bg-orange-200 text-orange-800 px-1.5 rounded">{{ count($materialsUsed) }} Item</span> 
                                        @endif
                                    </h4>

                                    @if(count($materialsUsed) > 0)
                                        <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1">
                                            @foreach($materialsUsed as $index => $item)
                                                @php $isLow = $item['current_stock'] < $item['actual_qty']; @endphp
                                                <div class="bg-white p-3 rounded-lg border {{ $isLow ? 'border-red-300 ring-1 ring-red-100' : 'border-gray-200' }}">
                                                    <div class="flex justify-between mb-2">
                                                        <div>
                                                            <div class="text-sm font-bold text-gray-800">{{ $item['name'] }}</div>
                                                            <div class="text-[10px] text-gray-400">Stok: {{ $item['current_stock'] }} {{ $item['unit'] }}</div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="text-[10px] text-gray-400">Resep:</div>
                                                            <div class="text-xs font-bold">{{ $item['standard_qty'] }} {{ $item['unit'] }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-[10px] font-bold text-gray-500 uppercase">Aktual:</span>
                                                        <input type="number" step="0.001" wire:model="materialsUsed.{{ $index }}.actual_qty" class="w-24 text-right py-1 text-sm rounded border-gray-300 focus:ring-blue-500 font-bold">
                                                        <span class="text-xs text-gray-500">{{ $item['unit'] }}</span>
                                                    </div>
                                                    @if($isLow)
                                                        <div class="mt-2 text-[10px] font-bold text-red-600 bg-red-50 p-1 rounded flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                            Stok Kurang!
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($product_id)
                                        <div class="h-40 flex flex-col items-center justify-center text-gray-400 opacity-60">
                                            <p class="text-xs font-bold">Produk ini tidak memiliki resep bahan.</p>
                                        </div>
                                    @else
                                        <div class="h-40 flex flex-col items-center justify-center text-gray-400 opacity-60">
                                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                            <p class="text-xs font-bold">Pilih produk di sebelah kiri</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Footer Modal --}}
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-row-reverse gap-2 border-t border-gray-100">
                        <button wire:click="save" type="button" class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-transparent bg-blue-600 px-4 py-2 text-base font-bold text-white shadow-sm hover:bg-blue-700 focus:outline-none sm:text-sm">
                            Simpan Data
                        </button>
                        <button wire:click="closeModal" type="button" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl border border-gray-300 bg-white px-4 py-2 text-base font-bold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Notifikasi Sukses --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-4 left-1/2 transform -translate-x-1/2 z-[60] bg-green-600 text-white px-6 py-3 rounded-full shadow-2xl flex items-center gap-2 animate-bounce-in">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-bold text-sm">{{ session('message') }}</span>
        </div>
    @endif

</div>