<div class="p-6">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h4 class="font-bold text-gray-900 text-base">{{ $title ?? 'Daftar Kontak' }}</h4>
            <p class="text-xs text-gray-500">Data relasi bisnis untuk transaksi.</p>
        </div>
        <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded-md">{{ count($contacts) }} Data</span>
    </div>

    {{-- List Kontak (Grid Layout) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
        @forelse($contacts as $contact)
            <div class="flex items-start justify-between p-4 bg-white border border-gray-100 rounded-xl hover:shadow-md hover:border-blue-200 transition group">
                <div class="flex gap-3 overflow-hidden">
                    <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 font-bold flex items-center justify-center shrink-0 border border-gray-200 text-sm">
                        {{ substr($contact->name, 0, 2) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ $contact->name }}</p>
                        <div class="flex flex-col gap-0.5 mt-1">
                            <span class="text-[11px] text-gray-500 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $contact->phone ?: '-' }}
                            </span>
                            @if($contact->address)
                                <span class="text-[11px] text-gray-400 truncate max-w-[150px] block" title="{{ $contact->address }}">
                                    📍 {{ $contact->address }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-1 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                    <button wire:click="edit({{ $contact->id }})" class="text-[10px] font-bold text-blue-600 hover:bg-blue-50 px-2 py-1 rounded">Edit</button>
                    <button wire:click="delete({{ $contact->id }})" class="text-[10px] font-bold text-red-600 hover:bg-red-50 px-2 py-1 rounded" onclick="return confirm('Hapus?')">Hapus</button>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 text-center py-8 text-gray-400 bg-gray-50/50 rounded-xl border-2 border-dashed border-gray-200">
                <p class="text-xs">Belum ada data kontak. Silakan tambah di bawah.</p>
            </div>
        @endforelse
    </div>

    {{-- Form Input --}}
    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
        <form wire:submit="save" class="space-y-3">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="md:col-span-1">
                    <input wire:model="name" type="text" placeholder="Nama Lengkap / Toko" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('name') <span class="text-red-500 text-[10px] ml-1">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-1">
                    <input wire:model="phone" type="text" placeholder="No. HP / WA" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-1">
                    <input wire:model="address" type="text" placeholder="Kota / Alamat" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                @if($isEditing)
                    <button type="button" wire:click="cancel" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 text-sm font-bold rounded-lg hover:bg-gray-100">Batal</button>
                @endif
                <button type="submit" class="w-full md:w-auto px-6 py-2 bg-gray-900 text-white text-sm font-bold rounded-lg hover:bg-black shadow-md transition">
                    {{ $isEditing ? 'Simpan Perubahan' : '+ Tambah Kontak' }}
                </button>
            </div>
        </form>
    </div>

    @if (session()->has('message'))
        <div class="mt-3 bg-green-100 text-green-700 text-xs font-bold px-3 py-2 rounded-lg text-center">
            {{ session('message') }}
        </div>
    @endif
</div>