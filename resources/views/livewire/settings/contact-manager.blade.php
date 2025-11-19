<div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
    
    <!-- Judul Dinamis (Supplier/Customer) -->
    <h4 class="font-bold text-gray-700 mb-3 flex items-center gap-2">
        <span>👥 {{ $title ?? 'Kelola Kontak' }}</span>
    </h4>

    <!-- Notifikasi -->
    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 text-xs p-2 rounded mb-2 font-bold">
            {{ session('message') }}
        </div>
    @endif

    <!-- Form Input -->
    <div class="bg-gray-50 p-3 rounded-lg mb-4 border border-gray-100">
        <form wire:submit="save" class="space-y-2">
            <div>
                <label class="text-[10px] font-bold text-gray-500">Nama Lengkap</label>
                <input wire:model="name" type="text" placeholder="Nama Toko / Orang" class="w-full rounded-lg border-gray-300 text-xs">
                @error('name') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
            </div>
            
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="text-[10px] font-bold text-gray-500">No. WhatsApp</label>
                    <input wire:model="phone" type="text" placeholder="0812..." class="w-full rounded-lg border-gray-300 text-xs">
                    @error('phone') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-500">Alamat</label>
                    <input wire:model="address" type="text" placeholder="Kota/Jalan" class="w-full rounded-lg border-gray-300 text-xs">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                @if($isEditing)
                    <button type="button" wire:click="cancel" class="text-xs text-gray-500 underline">Batal</button>
                @endif
                <button type="submit" class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold hover:bg-blue-700">
                    {{ $isEditing ? 'Simpan Perubahan' : '+ Tambah Baru' }}
                </button>
            </div>
        </form>
    </div>

    <!-- List Kontak -->
    <div class="space-y-2 max-h-60 overflow-y-auto">
        @forelse($contacts as $contact)
            <div class="flex justify-between items-center p-2 border-b border-gray-100 last:border-0">
                <div>
                    <p class="text-sm font-bold text-gray-800">{{ $contact->name }}</p>
                    <p class="text-[10px] text-gray-500">
                        📱 {{ $contact->phone }} • 📍 {{ $contact->address ?? '-' }}
                    </p>
                </div>
                <div class="flex gap-2 shrink-0">
                    <button wire:click="edit({{ $contact->id }})" class="text-blue-600 text-xs hover:underline">Edit</button>
                    <button wire:click="delete({{ $contact->id }})" class="text-red-600 text-xs hover:underline" onclick="return confirm('Hapus kontak ini?')">Hapus</button>
                </div>
            </div>
        @empty
            <p class="text-center text-xs text-gray-400 py-2">Belum ada data.</p>
        @endforelse
    </div>
</div>