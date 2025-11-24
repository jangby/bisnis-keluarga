<div class="min-h-screen bg-gray-50 pb-24 font-sans">
    
    {{-- HEADER --}}
    <div class="bg-white p-4 sticky top-0 z-30 border-b border-gray-200 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-lg font-bold text-gray-900">Laporan Keuangan</h1>
            
            {{-- Filter Bulan (Sederhana) --}}
            <div class="flex gap-2">
                <select wire:model.live="month" class="text-xs border-gray-300 rounded-lg py-1.5">
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                    @endfor
                </select>
                <select wire:model.live="year" class="text-xs border-gray-300 rounded-lg py-1.5">
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>
            </div>
        </div>

        {{-- 1. INFO SALDO WALLET (Minimalis & Scrollable) --}}
        <div class="flex gap-3 overflow-x-auto pb-2 no-scrollbar">
            @foreach($wallets as $wallet)
                <div class="bg-gray-100 px-3 py-2 rounded-lg border border-gray-200 shrink-0 min-w-[120px]">
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-0.5">{{ $wallet->name }}</p>
                    <p class="text-sm font-black text-gray-800">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="p-4 space-y-4">
        
        {{-- 2. DAFTAR DIVISI (CARD KLIKABLE) --}}
        @foreach($reportData as $data)
            {{-- Kita bungkus dalam tag A agar bisa diklik ke detail --}}
            <a href="{{ route('report.detail', $data['id']) }}" wire:navigate 
               class="block bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all active:scale-[0.98] group relative overflow-hidden">
                
                {{-- Hiasan background --}}
                <div class="absolute right-0 top-0 w-20 h-full bg-gradient-to-l from-gray-50 to-transparent opacity-50"></div>

                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg">{{ $data['name'] }}</h3>
                        <p class="text-xs text-gray-500">Klik untuk rincian detail &rarr;</p>
                    </div>
                    
                    {{-- Badge Laba/Rugi --}}
                    <div class="text-right">
                        <p class="text-[10px] text-gray-400 uppercase font-bold">Laba Bersih</p>
                        <p class="text-lg font-black {{ $data['profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $data['profit'] >= 0 ? '+' : '' }} Rp {{ number_format($data['profit'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- Baris Ringkasan --}}
                <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-3 relative z-10">
                    <div>
                        <span class="text-[10px] text-gray-400 block">Pemasukan</span>
                        <span class="text-sm font-bold text-gray-700">Rp {{ number_format($data['income'], 0, ',', '.') }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-gray-400 block">Pengeluaran</span>
                        <span class="text-sm font-bold text-red-500">Rp {{ number_format($data['expense'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </a>
        @endforeach

    </div>
</div>