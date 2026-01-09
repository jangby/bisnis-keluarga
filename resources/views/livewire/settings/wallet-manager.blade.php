<div class="p-6 sm:p-8">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h4 class="font-black text-gray-900 text-xl tracking-tight">Akun Kas & Bank</h4>
            <p class="text-sm text-gray-500 mt-1">Kelola sumber dana (Cash, Rekening, E-Wallet).</p>
        </div>
        
        @if(!$isEditing)
            <button x-data @click="$refs.walletInput.focus()" class="hidden md:inline-flex px-4 py-2 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition">
                + Tambah Cepat
            </button>
        @endif
    </div>

    {{-- CARDS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">
        @foreach($wallets as $wallet)
            <div class="group relative overflow-hidden rounded-2xl transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl">
                
                {{-- Dynamic Background based on ID (to vary colors) --}}
                <div class="absolute inset-0 bg-gradient-to-br {{ $loop->even ? 'from-indigo-600 to-blue-700' : 'from-slate-800 to-gray-900' }}"></div>
                
                {{-- Decorative Shapes --}}
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-5 rounded-full blur-2xl group-hover:opacity-10 transition"></div>
                <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-white opacity-5 rounded-full blur-xl"></div>

                {{-- Content --}}
                <div class="relative z-10 p-6 flex flex-col justify-between h-40 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest opacity-60 mb-1">Nama Akun</p>
                            <h5 class="font-bold text-lg tracking-wide truncate w-40">{{ $wallet->name }}</h5>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center font-bold text-sm shadow-inner">
                            {{ substr($wallet->name, 0, 1) }}
                        </div>
                    </div>

                    <div>
                         <p class="text-[10px] font-mono opacity-60 mb-1">{{ $wallet->account_number ?? '****' }}</p>
                         <p class="text-2xl font-black tracking-tight">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Action Buttons (Slide Up on Hover) --}}
                <div class="absolute bottom-0 left-0 w-full bg-white/90 backdrop-blur-md p-3 flex justify-center gap-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 z-20">
                    <button wire:click="edit({{ $wallet->id }})" class="text-xs font-bold text-blue-600 hover:text-blue-800 px-3 py-1">Edit</button>
                    @if($wallet->id != 1)
                        <div class="w-px h-6 bg-gray-300"></div>
                        <button wire:click="delete({{ $wallet->id }})" class="text-xs font-bold text-red-600 hover:text-red-800 px-3 py-1" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    @endif
                </div>
            </div>
        @endforeach

        {{-- Add New Placeholder --}}
        <div class="border-2 border-dashed border-gray-200 rounded-2xl flex flex-col items-center justify-center p-6 text-gray-400 bg-gray-50/50 min-h-[160px]">
            <svg class="w-8 h-8 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <p class="text-xs font-bold text-center">Isi form di bawah<br>untuk akun baru</p>
        </div>
    </div>

    {{-- MODERN INPUT FORM --}}
    <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-200 rounded-full blur-3xl opacity-50"></div>
        
        <h5 class="text-xs font-black text-blue-800 uppercase tracking-widest mb-4 relative z-10">
            {{ $isEditing ? '✏️ Edit Data Dompet' : '✨ Tambah Dompet Baru' }}
        </h5>

        <form wire:submit="save" class="relative z-10 flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full space-y-1">
                <label class="text-[10px] font-bold text-blue-600 uppercase ml-1">Nama Akun</label>
                <input x-ref="walletInput" wire:model="name" type="text" placeholder="Contoh: Bank BCA" class="w-full rounded-xl border-0 shadow-sm ring-1 ring-gray-200 text-sm py-3 focus:ring-2 focus:ring-blue-500 transition-all">
                @error('name') <span class="text-red-500 text-[10px] ml-1 font-bold">{{ $message }}</span> @enderror
            </div>
            <div class="flex-1 w-full space-y-1">
                <label class="text-[10px] font-bold text-blue-600 uppercase ml-1">No. Rekening (Opsional)</label>
                <input wire:model="account_number" type="text" placeholder="Contoh: 123-456-7890" class="w-full rounded-xl border-0 shadow-sm ring-1 ring-gray-200 text-sm py-3 focus:ring-2 focus:ring-blue-500 transition-all">
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                @if($isEditing)
                    <button type="button" wire:click="cancel" class="px-5 py-3 bg-white border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition">Batal</button>
                @endif
                <button type="submit" class="flex-1 md:flex-none px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:shadow-lg hover:shadow-blue-500/30 hover:scale-[1.02] transition-all duration-200">
                    {{ $isEditing ? 'Simpan Perubahan' : 'Simpan Akun' }}
                </button>
            </div>
        </form>
    </div>

    @if (session()->has('message'))
        <div class="mt-4 flex items-center gap-2 bg-emerald-50 text-emerald-700 text-xs font-bold px-4 py-3 rounded-xl border border-emerald-100 animate-enter">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('message') }}
        </div>
    @endif
</div>