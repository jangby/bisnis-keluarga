{{-- Tentukan Warna Tema Berdasarkan Tipe --}}
@php
    $isIncome = $type === 'income';
    $themeColor = $isIncome ? 'emerald' : 'rose';
    $bgColor = $isIncome ? 'bg-emerald-50' : 'bg-rose-50';
    $btnColor = $isIncome ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-rose-600 hover:bg-rose-700';
@endphp

<div class="{{ $bgColor }} min-h-screen pb-32 font-sans transition-colors duration-300">

    {{-- HEADER --}}
    <div class="bg-white/80 backdrop-blur-md p-4 sticky top-0 z-30 shadow-sm border-b border-gray-100">
        <div class="max-w-2xl mx-auto">
            <div class="flex bg-gray-100 p-1 rounded-xl relative">
                <button wire:click="$set('type', 'income')" 
                    class="flex-1 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2 relative z-10
                    {{ $isIncome ? 'bg-white text-emerald-600 shadow-md ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}">
                    <span>⬇️</span> Pemasukan
                </button>
                <button wire:click="$set('type', 'expense')" 
                    class="flex-1 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2 relative z-10
                    {{ !$isIncome ? 'bg-white text-rose-600 shadow-md ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}">
                    <span>⬆️</span> Pengeluaran
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-6 space-y-6">
        
        {{-- FORM AREA --}}
        <div class="space-y-6">

            {{-- 1. INPUT NOMINAL --}}
            <div class="bg-white p-6 rounded-3xl shadow-lg shadow-gray-200/50 border border-white relative overflow-hidden text-center">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Total Nominal</label>
                
                <div class="relative inline-block w-full">
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 text-2xl font-black text-gray-300">Rp</span>
                    <input wire:model="amount" type="number" inputmode="numeric" placeholder="0"
                        class="w-full pl-10 pr-4 py-2 text-4xl md:text-5xl font-black text-gray-800 text-center border-none focus:ring-0 placeholder-gray-200 bg-transparent tracking-tight">
                </div>
                @error('amount') <span class="text-red-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
            </div>

            {{-- 2. DETAIL TRANSAKSI --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-5">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <span class="w-1 h-5 bg-{{ $themeColor }}-500 rounded-full"></span>
                    Detail Transaksi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">Kategori</label>
                        <select wire:model.live="category" class="w-full rounded-xl border-gray-200 text-sm font-medium focus:border-{{ $themeColor }}-500 focus:ring-{{ $themeColor }}-500 py-2.5 bg-gray-50">
                            @foreach($categories as $cat) <option value="{{ $cat }}">{{ $cat }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">Tanggal</label>
                        <input wire:model="date" type="date" class="w-full rounded-xl border-gray-200 text-sm font-medium focus:border-{{ $themeColor }}-500 focus:ring-{{ $themeColor }}-500 py-2.5 bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">Kontak (Opsional)</label>
                        <select wire:model="contact_id" class="w-full rounded-xl border-gray-200 text-sm font-medium focus:border-{{ $themeColor }}-500 focus:ring-{{ $themeColor }}-500 py-2.5 bg-gray-50">
                            <option value="">-- Umum / Tanpa Nama --</option>
                            @foreach($contacts as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">Divisi</label>
                        <select wire:model="product_line_id" class="w-full rounded-xl border-gray-200 text-sm font-medium focus:border-{{ $themeColor }}-500 focus:ring-{{ $themeColor }}-500 py-2.5 bg-gray-50">
                            @foreach($productLines as $line) <option value="{{ $line->id }}">{{ $line->name }}</option> @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1.5">Catatan</label>
                    <textarea wire:model="notes" rows="2" class="w-full rounded-xl border-gray-200 text-sm focus:border-{{ $themeColor }}-500 focus:ring-{{ $themeColor }}-500 bg-gray-50 placeholder-gray-300" placeholder="Keterangan singkat..."></textarea>
                </div>
            </div>

            {{-- 3. METODE PEMBAYARAN --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <span class="w-1 h-5 bg-gray-800 rounded-full"></span>
                    Metode Pembayaran
                </h3>

                <div class="grid grid-cols-3 gap-3">
                    <button type="button" wire:click="$set('payment_method', 'cash')" class="py-3 px-2 rounded-xl border text-xs font-bold flex flex-col items-center gap-1 transition-all {{ $payment_method == 'cash' ? 'bg-blue-50 border-blue-500 text-blue-700 ring-1 ring-blue-500' : 'border-gray-200 text-gray-500' }}">
                        <span class="text-lg">💵</span> Tunai
                    </button>
                    <button type="button" wire:click="$set('payment_method', 'transfer')" class="py-3 px-2 rounded-xl border text-xs font-bold flex flex-col items-center gap-1 transition-all {{ $payment_method == 'transfer' ? 'bg-purple-50 border-purple-500 text-purple-700 ring-1 ring-purple-500' : 'border-gray-200 text-gray-500' }}">
                        <span class="text-lg">💳</span> Transfer
                    </button>
                    <button type="button" wire:click="$set('payment_method', 'debt')" class="py-3 px-2 rounded-xl border text-xs font-bold flex flex-col items-center gap-1 transition-all {{ $payment_method == 'debt' ? 'bg-orange-50 border-orange-500 text-orange-700 ring-1 ring-orange-500' : 'border-gray-200 text-gray-500' }}">
                        <span class="text-lg">⏳</span> {{ $isIncome ? 'Piutang' : 'Utang' }}
                    </button>
                </div>

                @if($payment_method == 'debt')
                    <div class="bg-orange-50 p-4 rounded-xl border border-orange-200 animate-fade-in-down">
                        <label class="block text-xs font-bold text-orange-800 mb-1.5 uppercase">Jatuh Tempo</label>
                        <input wire:model="due_date" type="date" class="w-full rounded-xl border-orange-300 text-sm focus:ring-orange-500 focus:border-orange-500">
                    </div>
                @else
                    <div class="animate-fade-in-up">
                        <label class="block text-xs font-bold text-gray-500 mb-1.5 uppercase">Sumber Dana</label>
                        <select wire:model="wallet_id" class="w-full rounded-xl border-gray-200 text-sm bg-gray-50">
                            @foreach($wallets as $w) <option value="{{ $w->id }}">{{ $w->name }} (Rp {{ number_format($w->balance, 0) }})</option> @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- FOOTER BUTTON --}}
    <div class="fixed bottom-[64px] left-0 right-0 p-4 bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] md:static md:bg-transparent md:border-none md:p-0 z-20">
        <div class="max-w-2xl mx-auto flex gap-3">
            <a href="{{ route('dashboard') }}" class="hidden md:flex px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">Batal</a>
            
            {{-- Perubahan: Menggunakan type="button" dan memanggil confirmSave --}}
            <button type="button" wire:click="confirmSave" 
                class="flex-1 py-4 rounded-xl font-bold text-white shadow-lg transform active:scale-[0.98] transition-all flex items-center justify-center gap-2 {{ $btnColor }} shadow-{{ $themeColor }}-500/30">
                <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>SIMPAN {{ $isIncome ? 'PEMASUKAN' : 'PENGELUARAN' }}</span>
            </button>
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
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 {{ $isIncome ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Konfirmasi {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}</h3>
                    <p class="text-sm text-gray-500">Pastikan data berikut sudah benar.</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 space-y-3 mb-6 border border-gray-100">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Jumlah</span>
                        <span class="font-black text-gray-800 text-lg">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Kategori</span>
                        <span class="font-medium text-gray-800">{{ $category }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Metode</span>
                        <span class="font-bold uppercase {{ $payment_method == 'debt' ? 'text-orange-600' : 'text-gray-800' }}">
                            {{ $payment_method == 'debt' ? ($isIncome ? 'Piutang' : 'Utang') : $payment_method }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button wire:click="$set('showConfirmationModal', false)" class="py-3.5 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button wire:click="save" class="py-3.5 rounded-xl font-bold text-white shadow-lg {{ $btnColor }}">
                        Ya, Simpan
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- NOTIFIKASI SUKSES --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed top-6 left-1/2 transform -translate-x-1/2 z-[100] bg-gray-900 text-white px-6 py-3 rounded-full shadow-2xl flex items-center gap-3 animate-bounce-in">
            <div class="bg-green-500 rounded-full p-1">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-sm font-bold">{{ session('message') }}</span>
        </div>
    @endif

</div>