<div class="space-y-6">
    
    {{-- HEADER & FILTER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-5 rounded-xl shadow-sm border border-gray-200 gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                💰 Penggajian
                <span class="text-xs font-normal text-gray-500 bg-gray-100 px-2 py-1 rounded-full border border-gray-200">
                    {{ date('F Y', mktime(0, 0, 0, $month, 10, $year)) }}
                </span>
            </h2>
            <p class="text-xs text-gray-500 mt-1">Estimasi gaji berdasarkan kehadiran "Hadir".</p>
        </div>
        
        <div class="w-full md:w-auto flex gap-2">
            <select wire:model.live="month" class="flex-1 md:flex-none rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500 cursor-pointer">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                @endforeach
            </select>
            <select wire:model.live="year" class="flex-1 md:flex-none rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500 cursor-pointer">
                @foreach(range(date('Y')-1, date('Y')+1) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- TAMPILAN MOBILE (CARD VIEW - DESIGN BARU)  --}}
    {{-- Muncul hanya di layar kecil (md:hidden)    --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-1 gap-4 md:hidden">
        @forelse($payrollData as $data)
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 relative overflow-hidden">
                
                {{-- Hiasan Background --}}
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-16 h-16 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>

                {{-- Header Kartu: Nama --}}
                <div class="flex items-center gap-3 mb-4 relative z-10">
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-lg font-bold text-gray-600 border border-gray-200">
                        {{ substr($data['user']->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $data['user']->name }}</h3>
                        <span class="text-xs uppercase tracking-wider text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded">{{ $data['user']->role }}</span>
                    </div>
                </div>

                {{-- Detail Gaji (Grid) --}}
                <div class="grid grid-cols-2 gap-y-3 gap-x-4 text-sm border-t border-dashed border-gray-200 pt-4 mb-4 relative z-10">
                    <div>
                        <span class="block text-xs text-gray-400 mb-0.5">Kehadiran</span>
                        <span class="font-bold text-gray-800 bg-green-50 text-green-700 px-2 py-0.5 rounded-md inline-block">
                            {{ $data['present_count'] }} Hari
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="block text-xs text-gray-400 mb-0.5">Gaji Harian</span>
                        <span class="font-medium text-gray-600">Rp {{ number_format($data['daily_salary'], 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Total & Tombol --}}
                <div class="flex justify-between items-end border-t border-gray-100 pt-3 relative z-10">
                    <div>
                        <span class="block text-[10px] uppercase font-bold text-gray-400">Total Terima (THP)</span>
                        <span class="text-xl font-black text-emerald-600">
                            Rp {{ number_format($data['total_salary'], 0, ',', '.') }}
                        </span>
                    </div>
                    
                    {{-- TOMBOL PRINT (Sudah diubah untuk membuka Modal Slip) --}}
                    <button wire:click="openSlip({{ $data['user']->id }})" class="bg-gray-900 text-white p-2 rounded-lg shadow-lg active:scale-95 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <p class="text-gray-400 text-sm">Belum ada data karyawan.</p>
            </div>
        @endforelse
    </div>

    {{-- ========================================== --}}
    {{-- TAMPILAN DESKTOP (TABEL)                   --}}
    {{-- Muncul hanya di layar besar (hidden md:block) --}}
    {{-- ========================================== --}}
    <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Karyawan</th>
                    <th class="px-6 py-4 text-center">Kehadiran</th>
                    <th class="px-6 py-4 text-right">Gaji Harian</th>
                    <th class="px-6 py-4 text-right">Total Terima (THP)</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payrollData as $data)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                                    {{ substr($data['user']->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-800">{{ $data['user']->name }}</div>
                                    <div class="text-xs text-gray-500 capitalize bg-gray-100 px-1.5 py-0.5 rounded inline-block mt-0.5">{{ $data['user']->role }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold text-xs">
                                {{ $data['present_count'] }} Hari
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-mono text-gray-600">
                            Rp {{ number_format($data['daily_salary'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-black text-emerald-600 text-base font-mono">
                                Rp {{ number_format($data['total_salary'], 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{-- TOMBOL PRINT DESKTOP --}}
                            <button wire:click="openSlip({{ $data['user']->id }})" class="text-gray-400 hover:text-emerald-600 transition tooltip" title="Cetak Slip">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span>Belum ada data karyawan yang sesuai kriteria.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ================================================= --}}
    {{-- MODAL SLIP GAJI                                   --}}
    {{-- ================================================= --}}
    @if($showSlipModal && $slipData)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto">
            
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden relative">
                
                {{-- AREA YANG AKAN DIPRINT --}}
                <div id="printable-area" class="p-8 bg-white text-gray-800">
                    
                    {{-- Header Slip --}}
                    <div class="text-center border-b-2 border-gray-800 pb-4 mb-4">
                        <h1 class="text-2xl font-black uppercase tracking-widest text-gray-900">SLIP GAJI</h1>
                        <p class="text-sm font-bold text-gray-600 uppercase">Bisnis Keluarga</p>
                        <p class="text-xs text-gray-500">Periode: {{ $slipData['period'] }}</p>
                    </div>

                    {{-- Info Karyawan --}}
                    <div class="flex justify-between text-xs mb-6 font-mono">
                        <div>
                            <p class="text-gray-500">Karyawan:</p>
                            <p class="font-bold text-sm uppercase">{{ $slipData['user']->name }}</p>
                            <p>{{ $slipData['user']->role }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-500">Tanggal Cetak:</p>
                            <p class="font-bold">{{ $slipData['print_date'] }}</p>
                        </div>
                    </div>

                    {{-- Tabel Rincian --}}
                    <div class="border-t border-dashed border-gray-300 pt-2 mb-2">
                        <h4 class="text-xs font-bold uppercase mb-2 text-gray-500">Penghasilan</h4>
                        
                        <div class="flex justify-between items-center text-sm mb-1">
                            <span>Gaji Harian</span>
                            <span class="font-mono">Rp {{ number_format($slipData['daily_salary'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm mb-1">
                            <span>Jumlah Kehadiran</span>
                            <span class="font-mono">{{ $slipData['present_count'] }} Hari</span>
                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="border-t-2 border-gray-800 pt-3 mt-4 mb-8">
                        <div class="flex justify-between items-center text-lg font-black">
                            <span>TOTAL DITERIMA</span>
                            <span>Rp {{ number_format($slipData['total_salary'], 0, ',', '.') }}</span>
                        </div>
                        <p class="text-[10px] text-gray-400 italic mt-1">* Gaji dihitung berdasarkan kehadiran fisik saja.</p>
                    </div>

                    {{-- Tanda Tangan --}}
                    <div class="grid grid-cols-2 gap-8 mt-8 text-center">
                        <div>
                            <p class="text-xs mb-10">Penerima,</p>
                            <p class="text-xs font-bold border-t border-gray-400 pt-1 inline-block min-w-[100px]">{{ $slipData['user']->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs mb-10">Keuangan / Owner,</p>
                            <p class="text-xs font-bold border-t border-gray-400 pt-1 inline-block min-w-[100px]">( ....................... )</p>
                        </div>
                    </div>
                </div>
                {{-- END AREA PRINT --}}

                {{-- Footer Tombol --}}
                <div class="bg-gray-50 p-4 border-t border-gray-200 flex justify-end gap-3">
                    <button wire:click="closeSlip" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-100">
                        Tutup
                    </button>
                    {{-- Tombol Print dengan JS Khusus --}}
                    <button onclick="printSlip()" class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-bold hover:bg-black flex items-center gap-2 shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak Slip
                    </button>
                </div>

            </div>
        </div>
    @endif

    {{-- SCRIPT PRINT ANTI-PUTIH-POLOS --}}
    <script>
        function printSlip() {
            var printContents = document.getElementById('printable-area').innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            window.location.reload();
        }
    </script>

</div>