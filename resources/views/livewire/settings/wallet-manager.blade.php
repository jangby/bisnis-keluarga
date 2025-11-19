<div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
    
    <!-- Header -->
    <h4 class="font-bold text-gray-700 mb-3 flex justify-between items-center">
        <span>💰 Daftar Dompet / Akun</span>
    </h4>

    <!-- Notifikasi -->
    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 text-xs p-2 rounded mb-2 font-bold">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100 text-red-800 text-xs p-2 rounded mb-2 font-bold">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form Input -->
    <div class="bg-gray-50 p-3 rounded-lg mb-4 border border-gray-100">
        <form wire:submit="save" class="space-y-2">
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="text-[10px] font-bold text-gray-500">Nama Akun</label>
                    <input wire:model="name" type="text" placeholder="Contoh: Kas Laci" class="w-full rounded-lg border-gray-300 text-xs">
                    @error('name') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-500">No. Rekening (Opsional)</label>
                    <input wire:model="account_number" type="text" placeholder="123-456-789" class="w-full rounded-lg border-gray-300 text-xs">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                @if($isEditing)
                    <button type="button" wire:click="cancel" class="text-xs text-gray-500 underline">Batal</button>
                @endif
                <button type="submit" class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold hover:bg-blue-700">
                    {{ $isEditing ? 'Update Dompet' : '+ Tambah Dompet' }}
                </button>
            </div>
        </form>
    </div>

    <!-- List Dompet -->
    <div class="space-y-2">
        @foreach($wallets as $wallet)
            <div class="flex justify-between items-center p-2 border-b border-gray-100 last:border-0">
                <div>
                    <p class="text-sm font-bold text-gray-800">{{ $wallet->name }}</p>
                    <p class="text-[10px] text-gray-500">
                        {{ $wallet->account_number ?? '-' }} • Saldo: Rp {{ number_format($wallet->balance, 0, ',', '.') }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <button wire:click="edit({{ $wallet->id }})" class="text-blue-600 text-xs hover:underline">Edit</button>
                    <button wire:click="delete({{ $wallet->id }})" class="text-red-600 text-xs hover:underline" onclick="return confirm('Yakin hapus?')">Hapus</button>
                </div>
            </div>
        @endforeach
    </div>
</div>