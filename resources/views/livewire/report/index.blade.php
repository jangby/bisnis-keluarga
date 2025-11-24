{{-- 
    FIX KONTRAS & WARNA & FITUR UTANG:
    1. bg-gray-100 : Latar belakang lebih gelap sedikit agar kartu putih terlihat jelas.
    2. dark:bg-white : Memaksa tampilan tetap terang meski HP mode gelap.
    3. Shortcut Buku Utang : Ditambahkan di bawah kartu biru.
--}}
<div class="bg-gray-100 dark:bg-gray-100 min-h-[110vh] pb-32 -mx-4 -mt-4 text-gray-900 dark:text-gray-900 font-sans">
    
    {{-- HEADER & FILTER --}}
    <div class="bg-white dark:bg-white px-5 py-6 rounded-b-3xl shadow-md border-b border-gray-300 sticky top-0 z-30">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-extrabold text-xl text-black dark:text-black">Laporan Keuangan</h2>
            
            {{-- Filter Bulan --}}
            <div class="flex gap-2">
                <select wire:model.live="month" class="text-xs font-bold border-gray-300 rounded-lg bg-gray-50 text-black focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-50 dark:text-black">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endforeach
                </select>
                <select wire:model.live="year" class="text-xs font-bold border-gray-300 rounded-lg bg-gray-50 text-black focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-50 dark:text-black">
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>
            </div>
        </div>

        {{-- GLOBAL SUMMARY CARD --}}
        <div class="bg-blue-700 rounded-2xl p-5 text-white shadow-lg shadow-blue-900/20 relative overflow-hidden border border-blue-800">
            {{-- Hiasan --}}
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-white/20 blur-2xl"></div>
            
            <p class="text-blue-100 text-xs mb-1 font-bold relative z-10 uppercase tracking-wide">Total Laba Bersih</p>
            <h1 class="text-3xl font-black tracking-tight relative z-10 drop-shadow-md">Rp {{ number_format($netProfit, 0, ',', '.') }}</h1>
            
            <div class="mt-4 flex gap-4 border-t border-blue-400/50 pt-3 relative z-10">
                <div>
                    <span class="text-[10px] text-blue-100 block font-medium">Total Omzet</span>
                    <span class="font-bold text-sm text-white">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</span>
                </div>
                <div class="w-px bg-blue-400/50"></div>
                <div>
                    <span class="text-[10px] text-blue-100 block font-medium">Total Beban</span>
                    <span class="font-bold text-sm text-white">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- [BARU] TOMBOL PINTASAN KE BUKU UTANG --}}
        <a href="{{ route('finance.debts') }}" wire:navigate class="mt-4 mb-2 block bg-white dark:bg-white border border-gray-300 p-4 rounded-2xl shadow-sm flex items-center justify-between hover:bg-gray-50 transition group">
            <div class="flex items-center gap-3">
                <div class="bg-orange-100 p-2 rounded-full text-orange-600 border border-orange-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 text-sm group-hover:text-blue-600 transition">Buku Utang & Piutang</h4>
                    <p class="text-[10px] text-gray-500 font-medium">Kelola kasbon pelanggan & supplier</p>
                </div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        
        {{-- INFO SALDO REAL --}}
        <div class="mt-2 flex items-center justify-between bg-white dark:bg-white px-4 py-3 rounded-xl border border-gray-300 shadow-sm">
            <div class="flex items-center gap-2">
                <div class="bg-green-100 p-1.5 rounded-full text-green-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </div>
                <span class="text-xs text-gray-700 font-bold uppercase">Saldo Kas Real</span>
            </div>
            <span class="text-base font-black text-green-700">Rp {{ number_format($realBalance, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="p-5 space-y-8">
        
        {{-- SECTION 1: PERFORMA DIVISI --}}
        <div>
            <h3 class="text-sm font-black text-gray-800 uppercase mb-4 tracking-wider border-l-4 border-blue-600 pl-3">Performa Divisi</h3>
            
            <div class="grid gap-5">
                @foreach($reportPerLine as $line)
                    {{-- Kartu Divisi dengan Border Tegas --}}
                    <div class="bg-white dark:bg-white p-0 rounded-2xl shadow-md border border-gray-300 overflow-hidden">
                        
                        {{-- Header Kartu --}}
                        <div class="p-4 flex justify-between items-start border-b border-gray-100 
                            {{ $line['name'] == 'Divisi Kecap' ? 'bg-amber-50' : ($line['name'] == 'Divisi Sistik' ? 'bg-yellow-50' : 'bg-gray-50') }}">
                            
                            <div class="flex items-center gap-3">
                                {{-- Icon Huruf Depan --}}
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center font-black text-lg shadow-sm border
                                    {{ $line['name'] == 'Divisi Kecap' ? 'bg-amber-600 text-white border-amber-700' : ($line['name'] == 'Divisi Sistik' ? 'bg-yellow-500 text-white border-yellow-600' : 'bg-gray-600 text-white') }}">
                                    {{ substr($line['name'], 7, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-black text-lg leading-tight">{{ $line['name'] }}</h4>
                                    <p class="text-[10px] text-gray-600 font-medium">{{ $line['description'] }}</p>
                                </div>
                            </div>

                            {{-- Badge Status --}}
                            @if($line['profit'] >= 0)
                                <span class="bg-green-100 text-green-800 text-[10px] font-bold px-3 py-1 rounded-full border border-green-300">UNTUNG</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-[10px] font-bold px-3 py-1 rounded-full border border-red-300">RUGI</span>
                            @endif
                        </div>

                        {{-- Body Kartu --}}
                        <div class="p-4">
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <span class="text-[10px] text-gray-500 font-bold uppercase">Pemasukan</span>
                                    <span class="block font-bold text-gray-900 text-sm mt-1">+ {{ number_format($line['income'], 0, ',', '.') }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-gray-500 font-bold uppercase">Beban (HPP)</span>
                                    <span class="block font-bold text-red-600 text-sm mt-1">- {{ number_format($line['expense'], 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-50 p-3 rounded-xl border border-gray-200 flex justify-between items-center">
                                <span class="text-xs font-bold text-gray-600 uppercase">Laba Bersih</span>
                                <span class="font-black text-lg {{ $line['profit'] >= 0 ? 'text-blue-700' : 'text-red-600' }}">
                                    Rp {{ number_format($line['profit'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- SECTION 2: RIWAYAT TRANSAKSI --}}
        <div>
            <h3 class="text-sm font-black text-gray-800 uppercase mb-4 tracking-wider border-l-4 border-gray-600 pl-3">Riwayat Transaksi</h3>
            
            <div class="bg-white dark:bg-white rounded-2xl shadow-md border border-gray-300 divide-y divide-gray-200 overflow-hidden">
                @forelse($transactions as $trx)
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition group">
                        <div class="flex items-center gap-3">
                            {{-- Icon Panah --}}
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border shadow-sm
                                {{ $trx->type == 'income' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700' }}">
                                @if($trx->type == 'income')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M7 11l5-5m0 0l5 5m-5-5v12" /></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 13l-5 5m0 0l-5-5m5 5V6" /></svg>
                                @endif
                            </div>
                            
                            <div>
                                <p class="font-bold text-sm text-black group-hover:text-blue-700 transition">{{ $trx->notes ?? $trx->category }}</p>
                                <p class="text-[10px] text-gray-500 font-semibold mt-0.5">
                                    {{ \Carbon\Carbon::parse($trx->transaction_date)->format('d M') }} • 
                                    <span class="{{ $trx->product_line->name == 'Divisi Kecap' ? 'text-amber-700' : ($trx->product_line->name == 'Divisi Sistik' ? 'text-yellow-700' : 'text-gray-500') }}">
                                        {{ $trx->product_line->name }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <span class="font-extrabold text-sm {{ $trx->type == 'income' ? 'text-green-700' : 'text-red-700' }}">
                            {{ $trx->type == 'income' ? '+' : '-' }} {{ number_format($trx->amount, 0, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500 text-sm font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Belum ada transaksi bulan ini.
                    </div>
                @endforelse
            </div>
            
            @if(count($transactions) > 0)
                <p class="text-center text-[10px] text-gray-400 mt-4 mb-2 font-medium">Menampilkan transaksi terbaru bulan ini</p>
            @endif
        </div>

    </div>
</div>