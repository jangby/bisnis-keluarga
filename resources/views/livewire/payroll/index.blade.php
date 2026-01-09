<div class="space-y-6">
    
    {{-- ========================================== --}}
    {{-- 1. HEADER & FILTER                         --}}
    {{-- ========================================== --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-5 rounded-2xl shadow-sm border border-gray-200 gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                💰 Penggajian
                <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-100">
                    {{ date('F Y', mktime(0, 0, 0, $month, 10, $year)) }}
                </span>
            </h2>
            <p class="text-xs text-gray-500 mt-1">Kelola gaji bulanan, kehadiran, dan kasbon karyawan.</p>
        </div>
        
        <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
            {{-- Tombol Kasbon Baru --}}
            <button wire:click="openKasbonModal" class="px-4 py-2.5 bg-amber-50 text-amber-700 rounded-xl text-sm font-bold hover:bg-amber-100 flex items-center justify-center gap-2 transition border border-amber-200 shadow-sm active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Kasbon Baru
            </button>

            <div class="flex gap-2">
                <select wire:model.live="month" class="flex-1 rounded-xl border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500 cursor-pointer shadow-sm">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                    @endforeach
                </select>
                <select wire:model.live="year" class="flex-1 rounded-xl border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500 cursor-pointer shadow-sm">
                    @foreach(range(date('Y')-1, date('Y')+1) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 2. TAMPILAN MOBILE (CARD VIEW)             --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-1 gap-4 md:hidden">
        @forelse($payrollData as $data)
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 relative overflow-hidden group hover:shadow-md transition-all">
                {{-- Hiasan Background --}}
                <div class="absolute -top-6 -right-6 p-4 opacity-5 group-hover:opacity-10 transition transform group-hover:scale-110 duration-500">
                    <svg class="w-32 h-32 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>

                <div class="flex items-center gap-4 mb-5 relative z-10">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-100 to-teal-200 flex items-center justify-center text-lg font-bold text-emerald-800 shadow-inner">
                        {{ substr($data['user']->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $data['user']->name }}</h3>
                        <span class="text-[10px] uppercase font-bold tracking-wider text-gray-500 bg-gray-100 px-2 py-0.5 rounded border border-gray-200">{{ $data['user']->role }}</span>
                    </div>
                </div>

                <div class="bg-gray-50/50 rounded-xl p-3 border border-gray-100 relative z-10">
                    <div class="flex justify-between items-center mb-2 border-b border-gray-200 pb-2">
                        <span class="text-xs text-gray-500 font-medium">Kehadiran</span>
                        <span class="font-bold text-emerald-700 bg-emerald-100/50 px-2 py-0.5 rounded text-xs">{{ $data['present_count'] }} Hari</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500 font-medium">Estimasi Gaji</span>
                        <span class="font-bold text-gray-800 font-mono">Rp {{ number_format($data['total_salary'], 0, ',', '.') }}</span>
                    </div>
                </div>

                <button wire:click="openSlip({{ $data['user']->id }})" class="mt-4 w-full py-3 bg-gray-900 text-white rounded-xl text-sm font-bold shadow-lg shadow-gray-200 active:scale-[0.98] transition flex items-center justify-center gap-2 hover:bg-black">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Proses Slip Gaji
                </button>
            </div>
        @empty
            <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                <p class="text-gray-400 text-sm">Tidak ada data karyawan.</p>
            </div>
        @endforelse
    </div>

    {{-- ========================================== --}}
    {{-- 3. TAMPILAN DESKTOP (TABEL)                --}}
    {{-- ========================================== --}}
    <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Karyawan</th>
                    <th class="px-6 py-4 text-center">Kehadiran</th>
                    <th class="px-6 py-4 text-right">Gaji Harian</th>
                    <th class="px-6 py-4 text-right">Estimasi THP</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payrollData as $data)
                    <tr class="hover:bg-gray-50 transition group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                                    {{ substr($data['user']->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-800">{{ $data['user']->name }}</div>
                                    <div class="text-xs text-gray-500 capitalize bg-gray-100 px-1.5 py-0.5 rounded inline-block mt-0.5 border border-gray-200">{{ $data['user']->role }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold text-xs border border-green-200">
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
                            <button wire:click="openSlip({{ $data['user']->id }})" class="px-3 py-1.5 bg-gray-900 text-white rounded-lg text-xs font-bold shadow hover:bg-black transition transform active:scale-95">
                                Proses
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ================================================= --}}
    {{-- 4. MODAL SLIP GAJI (DESAIN CETAK PREMIUM)         --}}
    {{-- ================================================= --}}
    @if($showSlipModal && $slipData)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 overflow-y-auto animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[480px] overflow-hidden relative">
                
                {{-- AREA YANG AKAN DICETAK --}}
                <div id="printable-area" class="p-8 bg-white text-gray-900 font-sans border-t-8 border-gray-900">
                    
                    {{-- Kop Surat Modern --}}
                    <div class="flex justify-between items-start mb-8 border-b-2 border-gray-100 pb-4">
                        <div>
                            <h1 class="text-xl font-black uppercase tracking-tight text-gray-900">SLIP GAJI</h1>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">Bisnis Keluarga</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400 font-medium uppercase">Periode</p>
                            <p class="text-sm font-bold text-gray-800">{{ $slipData['period'] }}</p>
                        </div>
                    </div>

                    {{-- Info Pegawai --}}
                    <div class="bg-gray-50 rounded-lg p-3 mb-6 border border-gray-100">
                        <table class="w-full text-xs font-mono">
                            <tr>
                                <td class="py-1 text-gray-500 w-24">NAMA</td>
                                <td class="py-1 font-bold uppercase text-gray-800">: {{ $slipData['user']->name }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-500">JABATAN</td>
                                <td class="py-1 uppercase text-gray-800">: {{ $slipData['user']->role }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-500">TANGGAL</td>
                                <td class="py-1 text-gray-800">: {{ $slipData['print_date'] }}</td>
                            </tr>
                        </table>
                    </div>

                    {{-- Tabel Rincian --}}
                    <div class="mb-6">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 border-b border-gray-200 pb-1">Rincian Penerimaan</h4>
                        
                        {{-- Baris Gaji --}}
                        <div class="flex justify-between items-center text-sm py-1">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-700">Gaji Pokok</span>
                                <span class="text-[10px] text-gray-400 font-mono">{{ $slipData['present_count'] }} Hari x Rp {{ number_format($slipData['daily_salary']/1000) }}k</span>
                            </div>
                            <span class="font-mono font-bold text-gray-800">Rp {{ number_format($slipData['gross_salary'], 0, ',', '.') }}</span>
                        </div>

                        {{-- Baris Potongan --}}
                        <div class="mt-3 pt-2 border-t border-dashed border-gray-200">
                            <div class="flex justify-between items-center text-sm py-1">
                                <span class="font-medium text-gray-700">Potongan Kasbon</span>
                                @if((int)$kasbon_deduction > 0)
                                    <span class="font-mono font-bold text-red-600">- Rp {{ number_format((int)$kasbon_deduction, 0, ',', '.') }}</span>
                                @else
                                    <span class="font-mono text-gray-400">-</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Total Final --}}
                    {{-- [PERBAIKAN] Tambahkan casting (int) pada $kasbon_deduction --}}
                    <div class="bg-gray-900 text-white p-4 rounded-xl flex justify-between items-center mb-8 print:bg-black print:text-white shadow-lg">
                        <span class="text-xs font-bold uppercase tracking-wider opacity-80">Take Home Pay</span>
                        <span class="text-xl font-black font-mono tracking-tight">Rp {{ number_format($slipData['gross_salary'] - (int)$kasbon_deduction, 0, ',', '.') }}</span>
                    </div>

                    {{-- Tanda Tangan --}}
                    <div class="flex justify-between mt-12 pt-4">
                        <div class="text-center w-24">
                            <p class="text-[9px] text-gray-400 uppercase mb-12">Penerima</p>
                            <div class="border-t border-gray-300"></div>
                            <p class="text-[9px] font-bold uppercase mt-1">{{ substr($slipData['user']->name, 0, 10) }}</p>
                        </div>
                        <div class="text-center w-24">
                            <p class="text-[9px] text-gray-400 uppercase mb-12">Keuangan</p>
                            <div class="border-t border-gray-300"></div>
                            <p class="text-[9px] font-bold uppercase mt-1">Admin</p>
                        </div>
                    </div>
                    
                    {{-- Footer Note --}}
                    <div class="text-center mt-8">
                        <p class="text-[8px] text-gray-300 italic">Dokumen ini sah dan dicetak otomatis oleh sistem.</p>
                    </div>
                </div>
                {{-- END AREA CETAK --}}

                {{-- PANEL KONTROL KASBON & DOMPET (Hanya di Layar) --}}
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 no-print space-y-4">
                    
                    {{-- Info Sisa Utang --}}
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-gray-500 uppercase">Sisa Utang Karyawan</span>
                        <span class="font-bold {{ $total_kasbon > 0 ? 'text-amber-600' : 'text-green-600' }}">
                            Rp {{ number_format($total_kasbon, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    {{-- Input Potong Gaji (Jika ada utang) --}}
                    @if($total_kasbon > 0)
                        <div class="relative">
                            <label class="text-[10px] font-bold text-gray-400 absolute -top-2 left-2 bg-gray-50 px-1">Potong Gaji</label>
                            <input type="number" wire:model.live="kasbon_deduction" class="w-full text-sm border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 font-mono font-bold text-gray-800" placeholder="0">
                            @error('kasbon_deduction') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    {{-- Pilihan Sumber Dana --}}
                    <div class="relative">
                        <label class="text-[10px] font-bold text-gray-400 absolute -top-2 left-2 bg-gray-50 px-1">Sumber Dana</label>
                        <select wire:model="selected_wallet_id" class="w-full text-sm border-gray-300 rounded-lg focus:ring-gray-500 focus:border-gray-500 font-medium text-gray-700 pt-3">
                            @foreach($wallets as $wallet)
                                <option value="{{ $wallet->id }}">{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                        @error('selected_wallet_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- FOOTER TOMBOL --}}
                <div class="bg-white p-4 flex justify-end gap-3 no-print border-t border-gray-100">
                    <button wire:click="closeSlip" class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition">
                        Batal
                    </button>
                    
                    <button wire:click="processPayment" wire:loading.attr="disabled" class="px-6 py-2.5 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-black shadow-lg shadow-gray-400/30 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed transition transform active:scale-95">
                        <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <svg wire:loading class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span wire:loading.remove>Simpan & Cetak</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ================================================= --}}
    {{-- 5. MODAL INPUT KASBON BARU                        --}}
    {{-- ================================================= --}}
    @if($showKasbonModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
                <button wire:click="$set('showKasbonModal', false)" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <div class="flex items-center gap-3 mb-6">
                    <div class="p-3 bg-amber-100 rounded-full text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Input Kasbon Baru</h3>
                        <p class="text-xs text-gray-500">Catat peminjaman uang oleh karyawan.</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Pilih Karyawan</label>
                        <select wire:model="new_kasbon_user_id" class="w-full rounded-xl border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                            <option value="">-- Nama Karyawan --</option>
                            @foreach($allEmployees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Nominal (Rp)</label>
                        <input type="number" wire:model="new_kasbon_amount" class="w-full rounded-xl border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500 font-bold" placeholder="Contoh: 50000">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Ambil Dari Saldo</label>
                        <select wire:model="selected_wallet_id" class="w-full rounded-xl border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                            @foreach($wallets as $wallet)
                                <option value="{{ $wallet->id }}">{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Keperluan / Catatan</label>
                        <textarea wire:model="new_kasbon_notes" class="w-full rounded-xl border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500" rows="2" placeholder="Contoh: Beli pulsa"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-100">
                    <button wire:click="$set('showKasbonModal', false)" class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50">Batal</button>
                    <button wire:click="saveKasbon" class="px-6 py-2.5 bg-amber-500 text-white rounded-xl text-sm font-bold hover:bg-amber-600 shadow-md transform active:scale-95 transition">Simpan Kasbon</button>
                </div>
            </div>
        </div>
    @endif

    {{-- SCRIPT PRINT: TEKNIK SWAP BODY (ANTI PUTIH POLOS) --}}
    <script>
        window.addEventListener('trigger-print', event => {
            setTimeout(() => {
                var printContents = document.getElementById('printable-area').innerHTML;
                var originalContents = document.body.innerHTML;

                // Ganti isi body dengan hanya slip gaji
                document.body.innerHTML = printContents;

                // Print
                window.print();

                // Kembalikan isi body original (Reload agar event livewire aman)
                window.location.reload(); 
            }, 500);
        })
    </script>
    
    {{-- CSS KHUSUS PRINT --}}
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: white; margin: 0; padding: 0; }
            /* Pastikan background hitam untuk total tetap tercetak */
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>

</div>