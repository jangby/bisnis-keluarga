<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-sans">
    
    <div class="mb-8">
        <h1 class="text-2xl font-black text-gray-900">Persetujuan & Approval</h1>
        <p class="text-sm text-gray-500">Tinjau pengajuan dari staff Gudang dan Keuangan.</p>
    </div>

    {{-- ========================================== --}}
    {{-- BAGIAN 1: REQUEST BAHAN BAKU (DARI GUDANG) --}}
    {{-- ========================================== --}}
    <div class="mb-10">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="w-2 h-6 bg-orange-500 rounded-full"></span>
            Permintaan Stok Bahan
            @if(count($materialRequests) > 0)
                <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full">{{ count($materialRequests) }} Baru</span>
            @endif
        </h2>

        @if(count($materialRequests) === 0)
            <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-200 border-dashed">
                <p class="text-gray-400 text-sm">Tidak ada permintaan bahan baku baru.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($materialRequests as $req)
                    <div class="bg-white p-5 rounded-2xl border border-orange-100 shadow-sm relative overflow-hidden group hover:border-orange-300 transition">
                        
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Request #{{ $req->id }}</h4>
                                <p class="text-xs text-gray-500">Oleh: {{ $req->user->name ?? 'Unknown' }} • {{ $req->requested_at->format('d M H:i') }}</p>
                            </div>
                            <span class="bg-orange-100 text-orange-700 text-[10px] font-bold px-2 py-1 rounded">PENDING</span>
                        </div>

                        {{-- List Item (Decode JSON) --}}
                        <div class="bg-gray-50 rounded-xl p-3 mb-3">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-2">Daftar Barang</p>
                            <ul class="space-y-1">
                                @foreach(is_string($req->items) ? json_decode($req->items, true) : $req->items as $item)
                                    <li class="flex justify-between text-xs text-gray-700">
                                        <span>{{ $item['name'] }}</span>
                                        <span class="font-bold">{{ $item['qty'] }} {{ $item['unit'] ?? 'Pcs' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            @if($req->notes)
                                <div class="mt-2 pt-2 border-t border-gray-200">
                                    <p class="text-[10px] italic text-gray-500">"{{ $req->notes }}"</p>
                                </div>
                            @endif
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex gap-2">
                            <button wire:click="rejectMaterial({{ $req->id }})" 
                                wire:confirm="Yakin tolak permintaan ini?"
                                class="flex-1 py-2 bg-white border border-gray-300 text-gray-600 text-xs font-bold rounded-lg hover:bg-gray-100">
                                Tolak
                            </button>
                            <button wire:click="approveMaterial({{ $req->id }})" 
                                wire:confirm="Setujui permintaan bahan ini?"
                                class="flex-1 py-2 bg-orange-600 text-white text-xs font-bold rounded-lg hover:bg-orange-700 shadow-lg shadow-orange-600/20">
                                ✓ Setujui
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ========================================== --}}
    {{-- BAGIAN 2: PENGAJUAN DANA (KEUANGAN)        --}}
    {{-- ========================================== --}}
    <div>
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="w-2 h-6 bg-blue-500 rounded-full"></span>
            Pengajuan Keuangan / Expense
            @if(count($requests) > 0)
                <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full">{{ count($requests) }} Baru</span>
            @endif
        </h2>

        @if(count($requests) === 0)
            <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-200 border-dashed">
                <p class="text-gray-400 text-sm">Tidak ada pengajuan keuangan pending.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($requests as $record)
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm relative">
                        {{-- Isi Card Keuangan (Sama seperti sebelumnya) --}}
                        <div class="flex justify-between items-start mb-2">
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider
                                {{ $record->type == 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $record->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $record->created_at->format('d M Y') }}</span>
                        </div>

                        <h3 class="text-xl font-black text-gray-900 mb-1">
                            Rp {{ number_format($record->amount, 0, ',', '.') }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-1">{{ $record->category ?? 'Umum' }}</p>
                        
                        <div class="text-xs text-gray-500 bg-gray-50 p-2 rounded-lg mb-4">
                            Note: {{ $record->notes ?? '-' }}<br>
                            Oleh: <span class="font-bold">{{ $record->user->name ?? 'Unknown' }}</span>
                        </div>

                        <div class="flex gap-2">
                            <button wire:click="reject({{ $record->id }})" 
                                class="flex-1 py-2 bg-red-50 text-red-600 text-xs font-bold rounded-lg hover:bg-red-100 transition">
                                Tolak
                            </button>
                            <button wire:click="approve({{ $record->id }})" 
                                class="flex-1 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition shadow-lg shadow-blue-600/20">
                                Setujui
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>