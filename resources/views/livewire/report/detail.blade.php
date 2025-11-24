<div class="min-h-screen bg-gray-50 font-sans pb-20">

    {{-- HEADER AREA (Gabungan Navigasi & Summary) --}}
    <div class="bg-white border-b border-gray-200 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] sticky top-0 z-40">
        
        {{-- Top Bar --}}
        <div class="px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('report.index') }}" wire:navigate class="p-2 rounded-full hover:bg-gray-100 text-gray-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-lg font-bold text-gray-900 leading-none">{{ $lineName }}</h1>
                    <p class="text-[10px] text-gray-400 font-medium mt-1 uppercase tracking-wide">Laporan Bulanan</p>
                </div>
            </div>

            {{-- Month Picker (Minimalist) --}}
            <div class="relative">
                <select wire:model.live="month" class="appearance-none bg-gray-100 border-transparent text-sm font-bold text-gray-700 py-2 pl-4 pr-8 rounded-xl focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                    @for($i=1; $i<=12; $i++) 
                        <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option> 
                    @endfor
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </div>

        {{-- Summary Card (Integrated) --}}
        <div class="px-6 pb-6 pt-2">
            <div class="grid grid-cols-3 divide-x divide-gray-100">
                <div class="pr-4">
                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Pemasukan</p>
                    <p class="text-sm font-bold text-green-600 truncate">
                        +{{ number_format($finalIncome/1000, 0, ',', '.') }}k
                    </p>
                </div>
                <div class="px-4">
                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Pengeluaran</p>
                    <p class="text-sm font-bold text-red-600 truncate">
                        -{{ number_format($realExpense/1000, 0, ',', '.') }}k
                    </p>
                </div>
                <div class="pl-4">
                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Profit Bersih</p>
                    <p class="text-sm font-black {{ $finalProfit >= 0 ? 'text-gray-900' : 'text-red-600' }} truncate">
                        Rp {{ number_format($finalProfit/1000, 0, ',', '.') }}k
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
        
        {{-- INFO KHUSUS OPERASIONAL (Card Terpisah) --}}
        @if($isOperational && count($injectedProfits) > 0)
            <div class="mx-4 mt-6 mb-2">
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/20 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-white/20 rounded-full blur-lg"></div>
                    
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <h3 class="font-bold text-sm flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Subsidi Silang
                            </h3>
                            <p class="text-[10px] text-blue-100 mt-1 opacity-90">Pemasukan otomatis dari laba divisi lain.</p>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2 relative z-10">
                        @foreach($injectedProfits as $inject)
                            <div class="flex justify-between items-center bg-white/10 rounded-lg px-3 py-2 backdrop-blur-sm border border-white/10">
                                <span class="text-xs font-medium text-blue-50">{{ $inject['name'] }}</span>
                                <span class="text-xs font-bold text-white">+ Rp {{ number_format($inject['amount'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- TIMELINE TRANSAKSI --}}
        <div class="px-4 pb-10 space-y-6 mt-6">
            
            @php $lastDate = null; @endphp

            @forelse($transactions as $trx)
                @php
                    $currentDate = $trx->transaction_date->format('Y-m-d');
                    $isNewDate = $currentDate != $lastDate;
                    $lastDate = $currentDate;
                    
                    // Format Tanggal Manusiawi
                    $humanDate = $trx->transaction_date->isToday() ? 'Hari Ini' : 
                                 ($trx->transaction_date->isYesterday() ? 'Kemarin' : 
                                 $trx->transaction_date->translatedFormat('d F Y'));
                @endphp

                @if($isNewDate)
                    <div class="sticky top-[148px] z-30 pt-4 -mx-4 px-4 bg-gray-50/95 backdrop-blur-sm transition-all">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 pl-1">
                            {{ $humanDate }}
                        </h3>
                    </div>
                @endif

                <div class="bg-white p-3.5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                    
                    {{-- Icon Category (Auto Initials) --}}
                    <div class="h-10 w-10 rounded-full flex items-center justify-center text-sm font-bold shadow-sm shrink-0
                        {{ $trx->type == 'income' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                        {{ substr($trx->category, 0, 1) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-gray-800 text-sm truncate pr-2">{{ $trx->category }}</h4>
                            <span class="font-bold text-sm whitespace-nowrap {{ $trx->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $trx->type == 'income' ? '+' : '-' }} {{ number_format($trx->amount, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center mt-0.5">
                            <p class="text-xs text-gray-400 truncate max-w-[200px]">
                                {{ $trx->notes ?? 'Tanpa catatan' }}
                            </p>
                            <span class="text-[10px] text-gray-300 font-mono">{{ $trx->transaction_date->format('H:i') }}</span>
                        </div>
                    </div>
                </div>

            @empty
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-500">Belum ada transaksi</p>
                    <p class="text-xs text-gray-400 mt-1">Transaksi bulan {{ DateTime::createFromFormat('!m', $month)->format('F') }} akan muncul disini.</p>
                </div>
            @endforelse

            <div class="mt-6 px-2">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>