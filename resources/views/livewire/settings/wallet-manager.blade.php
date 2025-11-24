<div class="p-6">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h4 class="font-bold text-gray-900 text-base">Daftar Akun Kas</h4>
            <p class="text-xs text-gray-500">Kelola tempat penyimpanan uang (Laci, Bank, E-Wallet).</p>
        </div>
        
        {{-- Toggle Form --}}
        @if(!$isEditing)
            <button x-data @click="$refs.walletInput.focus()" class="hidden md:inline-flex text-xs font-bold text-blue-600 hover:underline">
                Langsung ketik di bawah untuk tambah
            </button>
        @endif
    </div>

    {{-- GRID LAYOUT UNTUK DOMPET --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @foreach($wallets as $wallet)
            <div class="group bg-white p-4 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-300 transition-all relative">
                
                {{-- Actions (Hover Only on Desktop) --}}
                <div class="absolute top-3 right-3 flex gap-1 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                    <button wire:click="edit({{ $wallet->id }})" class="p-1.5 bg-gray-100 text-gray-500 hover:bg-blue-100 hover:text-blue-600 rounded-lg">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                    @if($wallet->id != 1)
                        <button wire:click="delete({{ $wallet->id }})" class="p-1.5 bg-gray-100 text-gray-500 hover:bg-red-100 hover:text-red-600 rounded-lg" onclick="return confirm('Hapus?')">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    @endif
                </div>

                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                        {{ substr($wallet->name, 0, 1) }}
                    </div>
                    <div>
                        <h5 class="font-bold text-gray-800 text-sm truncate w-24">{{ $wallet->name }}</h5>
                        <p class="text-[10px] text-gray-400 font-mono">{{ $wallet->account_number ?? 'Tunai' }}</p>
                    </div>
                </div>
                
                <div class="pt-3 border-t border-gray-100">
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Saldo Aktif</p>
                    <p class="text-lg font-black text-gray-900">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
                </div>
            </div>
        @endforeach

        {{-- Add New Card (Visual Placeholder for Input) --}}
        <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl flex flex-col items-center justify-center p-4 text-gray-400">
            <p class="text-xs font-medium text-center">Isi form di bawah<br>untuk tambah baru</p>
            <svg class="w-6 h-6 mt-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7"/></svg>
        </div>
    </div>

    {{-- Form Input --}}
    <div class="bg-blue-50/50 p-5 rounded-xl border border-blue-100">
        <h5 class="text-xs font-bold text-blue-800 uppercase mb-3">{{ $isEditing ? 'Edit Data Dompet' : 'Tambah Dompet Baru' }}</h5>
        <form wire:submit="save" class="flex flex-col md:flex-row gap-3 items-start">
            <div class="flex-1 w-full">
                <input x-ref="walletInput" wire:model="name" type="text" placeholder="Nama Akun (Misal: Bank BCA)" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                @error('name') <span class="text-red-500 text-[10px] ml-1">{{ $message }}</span> @enderror
            </div>
            <div class="flex-1 w-full">
                <input wire:model="account_number" type="text" placeholder="No. Rekening (Opsional)" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                @if($isEditing)
                    <button type="button" wire:click="cancel" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 text-sm font-bold rounded-lg hover:bg-gray-50">Batal</button>
                @endif
                <button type="submit" class="flex-1 md:flex-none px-6 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 shadow-md transition">
                    {{ $isEditing ? 'Simpan' : 'Tambah' }}
                </button>
            </div>
        </form>
    </div>

    @if (session()->has('message'))
        <div class="mt-4 bg-green-100 text-green-700 text-xs font-bold px-4 py-2 rounded-lg animate-fade-in-down">
            {{ session('message') }}
        </div>
    @endif
</div>