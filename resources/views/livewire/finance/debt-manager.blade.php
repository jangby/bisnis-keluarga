<div class="bg-gray-50/50 min-h-screen pb-24 font-sans text-gray-800">
    
    {{-- 1. HEADER --}}
    <div class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-200">
        <div class="max-w-lg mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" wire:navigate class="p-2 -ml-2 rounded-full hover:bg-gray-100 transition text-gray-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <h1 class="text-lg font-bold text-gray-900">Buku Utang</h1>
            </div>
            <span class="text-xs font-bold px-2.5 py-1 rounded-full border 
                {{ $activeTab == 'receivable' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-orange-50 text-orange-700 border-orange-200' }}">
                {{ $activeTab == 'receivable' ? 'Piutang' : 'Utang' }}
            </span>
        </div>
    </div>

    <div class="max-w-lg mx-auto px-4 py-6 space-y-6">

        {{-- 2. TAB SWITCHER --}}
        <div class="bg-gray-200/60 p-1.5 rounded-xl flex relative">
            <button wire:click="$set('activeTab', 'receivable')" 
                class="flex-1 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2
                {{ $activeTab == 'receivable' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                <span>💰</span> Piutang
            </button>
            <button wire:click="$set('activeTab', 'payable')" 
                class="flex-1 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2
                {{ $activeTab == 'payable' ? 'bg-white text-orange-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                <span>💸</span> Utang
            </button>
        </div>

        {{-- 3. SUMMARY CARD --}}
        @php
            $isReceivable = $activeTab == 'receivable';
            $totalAmount = $debts->sum('remaining');
        @endphp

        <div class="relative overflow-hidden rounded-2xl p-6 shadow-lg shadow-gray-200/50 text-white
             {{ $isReceivable ? 'bg-gradient-to-br from-green-500 to-emerald-700' : 'bg-gradient-to-br from-orange-500 to-red-600' }}">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/20 rounded-full blur-xl"></div>
            <p class="relative z-10 text-xs font-medium opacity-90 uppercase tracking-wider mb-1">
                {{ $isReceivable ? 'Total Uang yg Harus Ditagih' : 'Total Kewajiban Pembayaran' }}
            </p>
            <h2 class="relative z-10 text-3xl font-black tracking-tight">
                Rp {{ number_format($totalAmount, 0, ',', '.') }}
            </h2>
            <p class="relative z-10 text-xs mt-2 opacity-80 flex items-center gap-1">
                <span>{{ count($debts) }} Transaksi Aktif</span>
            </p>
        </div>

        {{-- 4. DAFTAR TAGIHAN --}}
        <div class="space-y-3">
            <h3 class="text-sm font-bold text-gray-500 px-1">Daftar {{ $isReceivable ? 'Pelanggan / Karyawan' : 'Supplier' }}</h3>
            
            @forelse($debts as $debt)
                @php
                    // [PERBAIKAN UTAMA DI SINI]
                    // Cek apakah ini utang Kontak atau Karyawan
                    $name = $debt->contact ? $debt->contact->name : ($debt->employee ? $debt->employee->name : 'Tanpa Nama');
                    $roleLabel = $debt->employee ? 'Karyawan' : 'Eksternal';
                    
                    $initial = substr($name, 0, 1);
                    $daysLeft = now()->diffInDays($debt->due_date, false);
                    $isOverdue = $daysLeft < 0;
                    $isNearDue = $daysLeft >= 0 && $daysLeft <= 3;
                @endphp

                <div wire:click="selectDebt({{ $debt->id }})" 
                     class="group bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all cursor-pointer relative overflow-hidden">
                    
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $isOverdue ? 'bg-red-500' : ($isReceivable ? 'bg-green-500' : 'bg-orange-500') }}"></div>

                    <div class="flex items-center gap-4 pl-2">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg shrink-0
                             {{ $isReceivable ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ $initial }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-gray-900 truncate">{{ $name }}</h4>
                                {{-- Badge Karyawan --}}
                                @if($debt->employee)
                                    <span class="bg-blue-100 text-blue-700 text-[9px] px-1.5 py-0.5 rounded font-bold uppercase">Staff</span>
                                @endif
                            </div>
                            
                            <p class="text-xs text-gray-400 truncate mb-1">{{ $debt->notes ?: $debt->description ?: 'Tanpa catatan' }}</p>
                            
                            @if($isOverdue)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600">
                                    ⚠️ Telat {{ abs((int)$daysLeft) }} Hari
                                </span>
                            @elseif($isNearDue)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-100 text-yellow-700">
                                    ⏳ {{ (int)$daysLeft == 0 ? 'Hari Ini' : (int)$daysLeft . ' Hari Lagi' }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-500">
                                    📅 {{ $debt->due_date->format('d M Y') }}
                                </span>
                            @endif
                        </div>

                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 uppercase">Sisa</p>
                            <p class="font-black text-sm md:text-base {{ $isReceivable ? 'text-green-600' : 'text-orange-600' }}">
                                Rp {{ number_format($debt->remaining / 1000, 0) }}k
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 px-4">
                    <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="font-bold text-gray-900">Tidak ada tagihan aktif</h3>
                    <p class="text-sm text-gray-500 mt-1">Semua beres!</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- 5. MODAL BAYAR --}}
    @if($showPaymentModal && $selectedDebt)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center" x-data x-init="$el.classList.add('active')">
            <div wire:click="$set('showPaymentModal', false)" class="absolute inset-0 bg-black/60 backdrop-blur-sm cursor-pointer"></div>

            <div class="bg-white w-full max-w-sm sm:rounded-2xl rounded-t-2xl p-6 relative z-10 shadow-2xl">
                <div class="w-12 h-1.5 bg-gray-300 rounded-full mx-auto mb-6 sm:hidden"></div>

                <div class="text-center mb-6">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl
                        {{ $activeTab == 'receivable' ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600' }}">
                        {{ $activeTab == 'receivable' ? '💰' : '💸' }}
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">
                        {{ $activeTab == 'receivable' ? 'Terima Pembayaran' : 'Bayar Utang' }}
                    </h3>
                    {{-- [PERBAIKAN DI SINI JUGA] --}}
                    <p class="text-sm text-gray-500">
                        {{ $selectedDebt->contact ? $selectedDebt->contact->name : ($selectedDebt->employee ? $selectedDebt->employee->name : 'Tanpa Nama') }}
                    </p>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase block mb-2 text-center">Nominal (Rp)</label>
                        <input wire:model="paymentAmount" type="number" 
                            class="w-full text-center text-3xl font-black text-gray-900 border-none focus:ring-0 p-0 placeholder-gray-200" 
                            placeholder="0">
                        <div class="h-px w-full bg-gray-200 mt-2"></div>
                        <p class="text-xs text-center mt-2 text-gray-500">
                            Sisa Tagihan: <span class="font-bold">Rp {{ number_format($selectedDebt->remaining, 0, ',', '.') }}</span>
                        </p>
                    </div>

                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-200">
                        <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Masuk/Keluar via:</label>
                        <select wire:model="wallet_id" class="w-full bg-transparent border-none text-sm font-bold text-gray-800 focus:ring-0 p-0">
                            @foreach($wallets as $w)
                                <option value="{{ $w->id }}">{{ $w->name }} (Rp {{ number_format($w->balance, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <button wire:click="processPayment" 
                        class="w-full py-4 rounded-xl font-bold text-white shadow-lg transform active:scale-[0.98] transition-all
                        {{ $activeTab == 'receivable' ? 'bg-green-600 hover:bg-green-700' : 'bg-orange-600 hover:bg-orange-700' }}">
                        KONFIRMASI
                    </button>
                    
                    <button wire:click="$set('showPaymentModal', false)" class="w-full py-3 text-sm font-bold text-gray-400 hover:text-gray-600">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>