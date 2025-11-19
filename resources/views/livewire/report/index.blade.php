<div class="bg-gray-50 min-h-screen pb-24">

    <div class="bg-blue-700 p-6 pb-12 rounded-b-3xl shadow-lg text-white sticky top-0 z-10">
        <h2 class="font-bold text-lg mb-4 text-center">Laporan Laba Rugi</h2>
        
        <div class="flex gap-2">
            <select wire:model.live="month" class="flex-1 bg-white/20 border-none rounded-xl text-sm text-white font-bold focus:ring-0 cursor-pointer">
                @foreach($months as $k => $v)
                    <option value="{{ $k }}" class="text-gray-800">{{ $v }}</option>
                @endforeach
            </select>
            <select wire:model.live="year" class="w-24 bg-white/20 border-none rounded-xl text-sm text-white font-bold focus:ring-0 cursor-pointer">
                <option value="2024" class="text-gray-800">2024</option>
                <option value="2025" class="text-gray-800">2025</option>
                <option value="2026" class="text-gray-800">2026</option>
            </select>
        </div>

        <div class="mt-6 text-center">
            <p class="text-xs opacity-80 uppercase tracking-wider">Keuntungan Bersih Keluarga</p>
            <h1 class="text-3xl font-extrabold mt-1">
                Rp {{ number_format($grandTotalProfit, 0, ',', '.') }}
            </h1>
        </div>
    </div>

    <div class="px-4 -mt-8 space-y-4">
        
        @foreach($reportData as $data)
            <div class="bg-white rounded-xl shadow-md p-4 border border-gray-100">
                
                <div class="flex justify-between items-start mb-3 border-b border-gray-100 pb-2">
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $data['name'] }}</h3>
                        <p class="text-[10px] text-gray-400">{{ $data['desc'] }}</p>
                    </div>
                    @if($data['profit'] > 0)
                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-full">Untung</span>
                    @elseif($data['profit'] < 0)
                        <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded-full">Rugi</span>
                    @else
                        <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-1 rounded-full">Impas</span>
                    @endif
                </div>

                <div class="grid grid-cols-3 gap-2 text-center">
                    
                    <div class="bg-green-50 p-2 rounded-lg">
                        <p class="text-[10px] text-green-600 mb-1">Pemasukan</p>
                        <p class="font-bold text-xs text-gray-700 truncate">
                            {{ number_format($data['income'], 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="bg-red-50 p-2 rounded-lg">
                        <p class="text-[10px] text-red-600 mb-1">Pengeluaran</p>
                        <p class="font-bold text-xs text-gray-700 truncate">
                            {{ number_format($data['expense'], 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="bg-blue-50 p-2 rounded-lg border border-blue-100">
                        <p class="text-[10px] text-blue-600 mb-1 font-bold">Bersih</p>
                        <p class="font-bold text-xs {{ $data['profit'] < 0 ? 'text-red-600' : 'text-blue-700' }} truncate">
                            {{ number_format($data['profit'], 0, ',', '.') }}
                        </p>
                    </div>

                </div>
            </div>
        @endforeach

    </div>

    <div class="px-4 mt-6">
        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-xl flex gap-3 items-start">
            <span class="text-2xl">💡</span>
            <div>
                <h4 class="font-bold text-sm text-yellow-800">Analisa Singkat</h4>
                <p class="text-xs text-yellow-700 mt-1 leading-relaxed">
                    Jika ada divisi yang <b>Rugi</b>, cek pengeluaran bahan baku. Jika <b>Untung</b> besar, pertimbangkan tambah stok bulan depan.
                </p>
            </div>
        </div>
    </div>

</div>