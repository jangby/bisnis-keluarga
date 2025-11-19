<div class="bg-gray-50 min-h-screen pb-24 p-4 space-y-6">
    
    <div class="flex items-center gap-2">
        <h2 class="font-bold text-xl text-gray-800">Ajukan Bahan Baku</h2>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Barang yang dibutuhkan</label>
                <select wire:model="product_id" class="w-full mt-1 rounded-lg border-gray-300 text-sm">
                    <option value="">-- Pilih Bahan --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} (Sisa: {{ $p->current_stock }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex gap-3">
                <div class="flex-1">
                    <label class="text-xs font-bold text-gray-500 uppercase">Jumlah</label>
                    <input wire:model="quantity" type="number" class="w-full mt-1 rounded-lg border-gray-300 text-sm">
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Alasan / Catatan</label>
                <input wire:model="notes" type="text" class="w-full mt-1 rounded-lg border-gray-300 text-sm" placeholder="Misal: Stok menipis">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition">
                Kirim Pengajuan 🚀
            </button>
        </form>

        @if (session()->has('message'))
            <div class="mt-3 bg-green-100 text-green-800 text-xs p-2 rounded text-center font-bold">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <div>
        <h3 class="font-bold text-gray-600 mb-3">Riwayat Pengajuan Anda</h3>
        <div class="space-y-3">
            @foreach($myRequests as $req)
                <div class="bg-white p-3 rounded-lg border border-gray-100 flex justify-between items-center">
                    <div>
                        <p class="font-bold text-sm">{{ $req->product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $req->quantity }} {{ $req->product->unit }} • {{ $req->created_at->diffForHumans() }}</p>
                    </div>
                    
                    @if($req->status == 'pending')
                        <span class="bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-1 rounded">Menunggu</span>
                    @elseif($req->status == 'approved')
                        <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-1 rounded">Disetujui</span>
                    @else
                        <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2 py-1 rounded">Ditolak</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>