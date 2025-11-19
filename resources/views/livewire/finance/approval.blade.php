<div class="bg-gray-50 min-h-screen pb-24 p-4">
    
    <h2 class="font-bold text-xl text-gray-800 mb-4">Persetujuan Belanja</h2>

    <div class="space-y-4">
        @forelse($requests as $req)
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-1 rounded">REQUEST #{{ $req->id }}</span>
                        <h3 class="font-bold text-gray-800 mt-1">{{ $req->product->name }}</h3>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-lg">{{ $req->quantity }} <span class="text-xs text-gray-500">{{ $req->product->unit }}</span></p>
                    </div>
                </div>

                <div class="flex items-center gap-2 text-xs text-gray-500 mb-4">
                    <span>👤 {{ $req->user->name }}</span>
                    <span>•</span>
                    <span>📝 {{ $req->notes ?? '-' }}</span>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <button wire:click="reject({{ $req->id }})" class="py-2 rounded-lg border border-red-200 text-red-600 font-bold text-xs hover:bg-red-50">
                        TOLAK
                    </button>
                    <button wire:click="approve({{ $req->id }})" class="py-2 rounded-lg bg-blue-600 text-white font-bold text-xs hover:bg-blue-700 shadow-lg shadow-blue-200">
                        SETUJUI & BAYAR
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-10 text-gray-400">
                <p>Tidak ada permintaan baru.</p>
            </div>
        @endforelse
    </div>

    @if($selectedRequest)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-sm p-5">
                <h3 class="font-bold text-lg mb-4">Konfirmasi Pembayaran</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-bold text-gray-500">Total Bayar (Real)</label>
                        <input wire:model="pay_amount" type="number" class="w-full rounded-lg border-gray-300 font-bold">
                        <p class="text-[10px] text-gray-400">Sesuaikan nominal dengan struk belanja asli.</p>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Ambil dari Dompet</label>
                        <select wire:model="wallet_id" class="w-full rounded-lg border-gray-300 text-sm">
                            @foreach($wallets as $w)
                                <option value="{{ $w->id }}">{{ $w->name }} ({{ number_format($w->balance) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 mt-6">
                    <button wire:click="$set('selectedRequest', null)" class="py-3 rounded-xl text-gray-600 font-bold bg-gray-100">Batal</button>
                    <button wire:click="confirmApprove" class="py-3 rounded-xl text-white font-bold bg-green-600 hover:bg-green-700">BAYAR SEKARANG</button>
                </div>
            </div>
        </div>
    @endif

</div>