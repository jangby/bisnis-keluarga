<div class="bg-gray-100 min-h-screen pb-24 font-sans text-gray-900">
    
    {{-- 1. STICKY HEADER & TITLE --}}
    <div class="bg-white px-5 py-4 sticky top-0 z-30 shadow-md border-b border-gray-100">
        <h2 class="font-extrabold text-2xl text-gray-800">Buku Utang & Piutang</h2>
        <p class="text-xs text-gray-500">Kelola tagihan pelanggan & supplier</p>
    </div>

    <div class="px-5 pt-4">
        <div class="bg-gray-200 p-1 rounded-xl flex shadow-inner">
            <button wire:click="$set('activeTab', 'receivable')" 
                class="flex-1 py-2.5 rounded-lg text-sm font-extrabold transition-all duration-200 
                {{ $activeTab == 'receivable' ? 'bg-emerald-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-300' }}">
                PIUTANG (Tagihan)
            </button>
            <button wire:click="$set('activeTab', 'payable')" 
                class="flex-1 py-2.5 rounded-lg text-sm font-extrabold transition-all duration-200 
                {{ $activeTab == 'payable' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-300' }}">
                UTANG (Kewajiban)
            </button>
        </div>
    </div>

    <div class="p-5 space-y-4">
        
        {{-- 3. TOTAL SUMMARY CARD (High Contrast) --}}
        @php
            $colorClass = $activeTab == 'receivable' ? 'bg-emerald-600' : 'bg-indigo-600';
            $shadowColor = $activeTab == 'receivable' ? 'shadow-emerald-500/40' : 'shadow-indigo-500/40';
            $label = $activeTab == 'receivable' ? 'Total Uang di Luar (Piutang)' : 'Total Yang Harus Dibayar (Utang)';
        @endphp

        <div class="{{ $colorClass }} text-white p-5 rounded-2xl shadow-xl {{ $shadowColor }} relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-bl-full"></div>
            
            <p class="text-xs opacity-90 uppercase font-bold tracking-wider">{{ $label }}</p>
            <h3 class="text-3xl font-black mt-1">Rp {{ number_format($debts->sum('remaining'), 0, ',', '.') }}</h3>
        </div>

        {{-- 4. LIST UTANG & PIUTANG --}}
        @forelse($debts as $debt)
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex justify-between items-center relative overflow-hidden active:scale-[0.99] transition-transform group">
                
                @php
                    $mainColor = $activeTab == 'receivable' ? 'text-emerald-600' : 'text-indigo-600';
                    $lineColor = $debt->is_overdue ? 'bg-red-500' : ($activeTab == 'receivable' ? 'bg-emerald-500' : 'bg-indigo-500');
                @endphp
                
                {{-- Garis Indikator Jatuh Tempo --}}
                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $lineColor }}"></div>

                <div class="pl-3 flex-1 min-w-0">
                    <h4 class="font-extrabold text-gray-900 truncate">{{ $debt->contact->name }}</h4>
                    <p class="text-xs text-gray-500 line-clamp-1">{{ $debt->notes ?? 'Transaksi' }}</p>
                    
                    <div class="mt-2 flex items-center gap-2 text-xs">
                        <span class="px-2 py-0.5 rounded-lg font-bold 
                            {{ $debt->is_overdue ? 'bg-red-100 text-red-700' : 'bg-gray-200 text-gray-700' }}">
                            Tempo: {{ $debt->due_date->format('d M') }}
                        </span>
                        @if($debt->is_overdue)
                            <span class="text-red-600 font-extrabold animate-pulse">Telat {{ abs($debt->days_until_due) }} Hari!</span>
                        @endif
                    </div>
                </div>

                <div class="text-right shrink-0 pl-3">
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest">Sisa Tagihan</p>
                    <p class="font-black text-xl {{ $mainColor }}">
                        Rp {{ number_format($debt->remaining / 1000, 0) }}K
                    </p>
                    
                    <button wire:click="selectDebt({{ $debt->id }})" class="mt-2 bg-gray-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg active:scale-95 transition shadow-md hover:bg-gray-700">
                        {{ $activeTab == 'receivable' ? 'LUNASI' : 'BAYAR' }}
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-400 bg-white rounded-2xl border-2 border-dashed border-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-3 opacity-30 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-semibold">Tidak ada {{ $activeTab == 'receivable' ? 'Piutang' : 'Utang' }} aktif.</p>
                <p class="text-xs">Laporan keuangan Anda bersih! 🎉</p>
            </div>
        @endforelse
    </div>

    {{-- 5. MODAL BAYAR (Centered Pop-up Style FIX) --}}
    @if($showPaymentModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 sm:p-6 lg:p-8 animate-fade-in">
            
            {{-- Container Modal (Centered Pop-up) --}}
            <div class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl relative animate-pop-in">
                
                {{-- Tombol Close --}}
                <button wire:click="$set('showPaymentModal', false)" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-2 bg-gray-200 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <h3 class="font-extrabold text-2xl mb-1 text-gray-900">{{ $activeTab == 'receivable' ? 'Penerimaan Piutang' : 'Pembayaran Utang' }}</h3>
                <p class="text-sm text-gray-500 mb-6">Untuk: <span class="font-bold text-gray-800">{{ $selectedDebt->contact->name }}</span></p>

                <div class="space-y-5">
                    
                    {{-- Input Nominal --}}
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Nominal {{ $activeTab == 'receivable' ? 'Diterima' : 'Dibayarkan' }} (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400 font-black text-xl">Rp</span>
                            <input wire:model="paymentAmount" type="number" 
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl border-gray-300 font-black text-2xl text-gray-900 focus:ring-black focus:border-black transition-colors bg-white shadow-sm" 
                                placeholder="0">
                        </div>
                        <p class="text-xs text-gray-400 mt-1 text-right">
                            Sisa: <span class="font-bold text-red-500">Rp {{ number_format($selectedDebt->remaining, 0, ',', '.') }}</span>
                        </p>
                    </div>

                    {{-- Target Dompet --}}
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Target Dompet/Kas</label>
                        <select wire:model="wallet_id" class="w-full py-3 rounded-xl border-gray-300 text-gray-800 font-medium focus:ring-black focus:border-black transition-colors bg-white shadow-sm">
                            @foreach($wallets as $w)
                                <option value="{{ $w->id }}">{{ $w->name }} (Saldo: Rp {{ number_format($w->balance, 0) }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="pt-2 flex gap-3">
                        <button wire:click="$set('showPaymentModal', false)" class="flex-1 py-3.5 rounded-xl font-bold text-gray-600 bg-gray-200 hover:bg-gray-300 transition active:scale-95">
                            Batal
                        </button>
                        <button wire:click="processPayment" class="flex-1 py-3.5 rounded-xl font-extrabold text-white bg-black hover:bg-gray-800 transition shadow-lg active:scale-95">
                            SIMPAN TRANSAKSI
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Notifikasi Sukses --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
            class="fixed top-6 left-4 right-4 z-[110] {{ session('success') ? 'bg-emerald-600' : 'bg-red-600' }} text-white px-4 py-3 rounded-xl shadow-2xl text-center text-sm font-bold animate-bounce-in">
            {{ session('message') }}
        </div>
    @endif
</div>