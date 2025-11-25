<div class="min-h-screen bg-gray-50 pb-20 font-sans text-gray-800">

    {{-- STYLE: Ditaruh di dalam Root Div agar tidak error Livewire --}}
    <style>
        /* Hide Scrollbar for Filters */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
            class="fixed top-20 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg font-bold text-sm animate-bounce">
            ✅ {{ session('message') }}
        </div>
    @endif

    {{-- HEADER & TOTAL SUMMARY (Sticky) --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-3xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" wire:navigate class="p-2 -ml-2 rounded-full hover:bg-gray-100 text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    <h1 class="text-lg font-bold text-gray-900">Riwayat Aktivitas</h1>
                </div>
            </div>

            {{-- Ringkasan Berdasarkan Filter --}}
            <div class="grid grid-cols-2 gap-4 bg-gray-50 p-3 rounded-xl border border-gray-100">
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-bold">Total Masuk (Filter)</p>
                    <p class="text-sm font-black text-green-600">+ Rp {{ number_format($summaryIncome, 0, ',', '.') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-gray-500 uppercase font-bold">Total Keluar (Filter)</p>
                    <p class="text-sm font-black text-red-600">- Rp {{ number_format($summaryExpense, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-4 space-y-6">

        {{-- ADVANCED FILTER SECTION --}}
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm space-y-3">
            
            {{-- Search Bar --}}
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari transaksi, catatan, atau nominal..." 
                    class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Filter Row 1: Tanggal --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Dari</label>
                    <input wire:model.live="dateStart" type="date" class="w-full py-2 px-3 rounded-lg border-gray-200 text-xs font-bold text-gray-700 focus:ring-blue-500">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Sampai</label>
                    <input wire:model.live="dateEnd" type="date" class="w-full py-2 px-3 rounded-lg border-gray-200 text-xs font-bold text-gray-700 focus:ring-blue-500">
                </div>
            </div>

            {{-- Filter Row 2: Chips (Horizontal Scroll) --}}
            <div class="flex gap-2 overflow-x-auto pb-1 no-scrollbar">
                <select wire:model.live="type" class="py-2 px-4 rounded-lg border-gray-200 text-xs font-bold bg-white shadow-sm focus:ring-blue-500">
                    <option value="all">Semua Tipe</option>
                    <option value="income">💰 Pemasukan</option>
                    <option value="expense">💸 Pengeluaran</option>
                </select>

                <select wire:model.live="lineId" class="py-2 px-4 rounded-lg border-gray-200 text-xs font-bold bg-white shadow-sm focus:ring-blue-500">
                    <option value="all">Semua Divisi</option>
                    @foreach($productLines as $line)
                        <option value="{{ $line->id }}">{{ $line->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- TIMELINE TRANSAKSI (Newspaper Style) --}}
        <div class="space-y-6">
            @php $lastDate = null; @endphp

            @forelse($transactions as $trx)
                @php
                    $currentDate = $trx->transaction_date->format('Y-m-d');
                    $isNewDate = $currentDate != $lastDate;
                    $lastDate = $currentDate;
                    
                    $humanDate = $trx->transaction_date->isToday() ? 'Hari Ini' : 
                                 ($trx->transaction_date->isYesterday() ? 'Kemarin' : 
                                 $trx->transaction_date->translatedFormat('l, d F Y'));
                @endphp

                {{-- Sticky Date Header --}}
                @if($isNewDate)
                    <div class="sticky top-[155px] z-20 pt-4 pb-2 bg-gray-50/95 backdrop-blur-sm">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest pl-1 border-l-4 border-blue-500 pl-2">
                            {{ $humanDate }}
                        </h3>
                    </div>
                @endif

                {{-- Transaction Card --}}
                <div class="group bg-white p-4 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-start gap-4 relative overflow-hidden">
                    
                    {{-- Indikator Warna Kiri --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $trx->type == 'income' ? 'bg-green-500' : 'bg-red-500' }}"></div>

                    {{-- Icon --}}
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg shrink-0 mt-1
                        {{ $trx->type == 'income' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                        {{ substr($trx->category, 0, 1) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm line-clamp-1">{{ $trx->category }}</h4>
                                <p class="text-[11px] text-gray-500 mt-0.5 flex items-center gap-1.5">
                                    <span class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-600 font-semibold">
                                        {{ $trx->product_line->name ?? 'Umum' }}
                                    </span>
                                    @if($trx->contact)
                                        <span>• {{ $trx->contact->name }}</span>
                                    @endif
                                </p>
                            </div>
                            <span class="font-black text-sm whitespace-nowrap {{ $trx->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $trx->type == 'income' ? '+' : '-' }} {{ number_format($trx->amount, 0, ',', '.') }}
                            </span>
                        </div>

                        @if($trx->notes)
                            <div class="mt-2 text-xs text-gray-600 bg-gray-50 p-2 rounded-lg border border-gray-100 italic line-clamp-2">
                                "{{ $trx->notes }}"
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-end mt-2">
                            <p class="text-[10px] text-gray-400 font-mono">
                                {{ $trx->created_at->format('H:i') }} WIB
                            </p>
                            
                            {{-- [BARU] Tombol Aksi (Edit & Hapus) --}}
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $trx->id }})" class="text-xs bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg font-bold hover:bg-indigo-100 transition">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $trx->id }})" onclick="return confirm('Yakin hapus? Saldo akan dikembalikan.')" class="text-xs bg-red-50 text-red-600 px-3 py-1 rounded-lg font-bold hover:bg-red-100 transition">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center py-20 opacity-50">
                    <div class="bg-gray-200 p-4 rounded-full mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-600">Tidak ada data ditemukan</p>
                    <p class="text-xs text-gray-500">Coba ubah filter tanggal atau kata kunci pencarian.</p>
                </div>
            @endforelse

            <div class="pt-4 pb-8">
                {{ $transactions->links() }}
            </div>
        </div>

    </div>

    {{-- MODAL EDIT TRANSAKSI --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 backdrop-blur-sm">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all scale-100">
                
                {{-- Modal Header --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Edit Transaksi</h3>
                    <button wire:click="$set('showEditModal', false)" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-4">
                    
                    {{-- Input Nominal --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nominal (Rp)</label>
                        <input type="number" wire:model="editAmount" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-bold text-lg">
                    </div>

                    {{-- Row: Tanggal & Kategori --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal</label>
                            <input type="date" wire:model="editDate" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kategori</label>
                            <input type="text" wire:model="editCategory" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500">
                        </div>
                    </div>

                    {{-- Row: Divisi & Wallet --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Divisi / Produk</label>
                            <select wire:model="editLineId" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500">
                                @foreach($productLines as $line)
                                    <option value="{{ $line->id }}">{{ $line->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Dompet / Akun</label>
                            <select wire:model="editWalletId" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500">
                                @foreach($wallets as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Catatan</label>
                        <textarea wire:model="editNotes" rows="2" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500"></textarea>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="$set('showEditModal', false)" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-200 rounded-lg transition">
                        Batal
                    </button>
                    <button wire:click="update" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg shadow-lg hover:bg-indigo-700 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>