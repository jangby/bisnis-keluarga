<div class="p-6 sm:p-8">
    
    <div class="flex justify-between items-end mb-8">
        <div>
            <h4 class="font-black text-gray-900 text-xl tracking-tight">{{ $title ?? 'Daftar Kontak' }}</h4>
            <p class="text-sm text-gray-500 mt-1">Kelola data relasi bisnis Anda.</p>
        </div>
        <div class="bg-gray-100 text-gray-800 text-xs font-bold px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm">
            {{ count($contacts) }} Terdaftar
        </div>
    </div>

    {{-- LIST KONTAK (Modern List) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
        @forelse($contacts as $contact)
            <div class="group relative bg-white border border-gray-100 rounded-2xl p-4 flex items-center gap-4 hover:shadow-lg hover:shadow-gray-200/50 hover:border-blue-200 transition-all duration-300">
                
                {{-- Avatar --}}
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 text-gray-600 font-bold flex items-center justify-center shrink-0 border border-gray-200 text-base shadow-sm group-hover:scale-110 group-hover:bg-blue-50 group-hover:text-blue-600 transition-all duration-300">
                    {{ substr($contact->name, 0, 2) }}
                </div>

                <div class="flex-1 min-w-0">
                    <h5 class="text-sm font-bold text-gray-900 truncate">{{ $contact->name }}</h5>
                    <div class="flex flex-col gap-0.5 mt-1">
                        <div class="flex items-center gap-1.5 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span class="font-medium">{{ $contact->phone ?: 'Tidak ada nomor' }}</span>
                        </div>
                        @if($contact->address)
                            <div class="flex items-center gap-1.5 text-[10px] text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="truncate">{{ $contact->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="flex gap-2 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                    <button wire:click="edit({{ $contact->id }})" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                    <button wire:click="delete({{ $contact->id }})" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition" onclick="return confirm('Hapus?')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 flex flex-col items-center justify-center py-10 text-gray-400 bg-gray-50/50 rounded-2xl border-2 border-dashed border-gray-200">
                <svg class="w-10 h-10 mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <p class="text-xs font-medium">Belum ada data kontak.</p>
            </div>
        @endforelse
    </div>

    {{-- MODERN FORM --}}
    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200 relative">
        <h5 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">
            {{ $isEditing ? 'Mode Edit Data' : 'Form Tambah Baru' }}
        </h5>

        <form wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-1 space-y-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase ml-1">Nama / Toko</label>
                    <input wire:model="name" type="text" placeholder="Misal: Toko Berkah" class="w-full rounded-xl border-0 shadow-sm ring-1 ring-gray-200 text-sm py-2.5 focus:ring-2 focus:ring-gray-800 transition-all">
                    @error('name') <span class="text-red-500 text-[10px] ml-1">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-1 space-y-1">
                     <label class="text-[10px] font-bold text-gray-500 uppercase ml-1">Kontak</label>
                    <input wire:model="phone" type="text" placeholder="0812..." class="w-full rounded-xl border-0 shadow-sm ring-1 ring-gray-200 text-sm py-2.5 focus:ring-2 focus:ring-gray-800 transition-all">
                </div>
                <div class="md:col-span-1 space-y-1">
                     <label class="text-[10px] font-bold text-gray-500 uppercase ml-1">Lokasi</label>
                    <input wire:model="address" type="text" placeholder="Kota / Wilayah" class="w-full rounded-xl border-0 shadow-sm ring-1 ring-gray-200 text-sm py-2.5 focus:ring-2 focus:ring-gray-800 transition-all">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                @if($isEditing)
                    <button type="button" wire:click="cancel" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-100 transition">Batal</button>
                @endif
                <button type="submit" class="w-full md:w-auto px-8 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-black hover:shadow-lg transition-all active:scale-95">
                    {{ $isEditing ? 'Simpan Perubahan' : '+ Tambah Kontak' }}
                </button>
            </div>
        </form>
    </div>

    @if (session()->has('message'))
        <div class="mt-4 bg-emerald-50 text-emerald-700 text-xs font-bold px-4 py-3 rounded-xl border border-emerald-100 text-center animate-enter">
            {{ session('message') }}
        </div>
    @endif
</div>