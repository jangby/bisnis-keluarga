<div class="p-6">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h4 class="font-bold text-gray-900 text-base">Daftar Pengguna Sistem</h4>
            <p class="text-xs text-gray-500">Kelola akses login karyawan & staff.</p>
        </div>
        <span class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2 py-1 rounded-md">{{ count($users) }} User Aktif</span>
    </div>

    {{-- LIST USER (GRID) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        @foreach($users as $user)
            <div class="group bg-white p-4 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-purple-300 transition-all relative overflow-hidden">
                
                {{-- Role Badge (Corner) --}}
                {{-- Update logika warna badge untuk staff --}}
                <div class="absolute top-0 right-0 px-2 py-1 rounded-bl-lg text-[10px] font-bold uppercase tracking-wider
                    {{ $user->role == 'owner' ? 'bg-purple-600 text-white' : 
                      ($user->role == 'finance' ? 'bg-blue-100 text-blue-700' : 
                      ($user->role == 'marketing' ? 'bg-green-100 text-green-700' : 
                      ($user->role == 'production' ? 'bg-orange-100 text-orange-700' : 
                      'bg-gray-200 text-gray-600'))) }}"> {{-- Default (Staff) jadi abu-abu --}}
                    {{ $user->role }}
                </div>

                <div class="flex items-center gap-3 mb-3 mt-1">
                    {{-- Avatar --}}
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg text-white
                        {{ $user->role == 'owner' ? 'bg-purple-600' : 'bg-gray-400' }}">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <h5 class="font-bold text-gray-900 text-sm truncate w-32">{{ $user->name }}</h5>
                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2 mt-4 pt-3 border-t border-gray-100">
                    <button wire:click="edit({{ $user->id }})" class="flex-1 py-1.5 text-xs font-bold text-gray-600 bg-gray-50 hover:bg-purple-50 hover:text-purple-700 rounded-lg transition">
                        Edit
                    </button>
                    @if($user->id != Auth::id())
                        <button wire:click="delete({{ $user->id }})" onclick="return confirm('Hapus user ini?')" class="flex-1 py-1.5 text-xs font-bold text-red-500 bg-red-50 hover:bg-red-100 rounded-lg transition">
                            Hapus
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- FORM INPUT --}}
    <div class="bg-purple-50/50 p-5 rounded-xl border border-purple-100">
        <h5 class="text-xs font-bold text-purple-800 uppercase mb-4 flex items-center gap-2">
            <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
            {{ $isEditing ? 'Edit Data User' : 'Tambah User Baru' }}
        </h5>
        
        <form wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                {{-- Nama --}}
                <div>
                    <label class="text-[10px] font-bold text-gray-500 uppercase mb-1 block">Nama Lengkap</label>
                    <input wire:model="name" type="text" class="w-full rounded-lg border-gray-300 text-sm focus:ring-purple-500 focus:border-purple-500" placeholder="Nama Staff">
                    @error('name') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="text-[10px] font-bold text-gray-500 uppercase mb-1 block">Email Login</label>
                    <input wire:model="email" type="email" class="w-full rounded-lg border-gray-300 text-sm focus:ring-purple-500 focus:border-purple-500" placeholder="email@contoh.com">
                    @error('email') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="text-[10px] font-bold text-gray-500 uppercase mb-1 block">Password {{ $isEditing ? '(Isi jika ingin ubah)' : '' }}</label>
                    <input wire:model="password" type="text" class="w-full rounded-lg border-gray-300 text-sm focus:ring-purple-500 focus:border-purple-500" placeholder="Minimal 6 karakter">
                    @error('password') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                </div>

                {{-- Role --}}
                <div>
                    <label class="text-[10px] font-bold text-gray-500 uppercase mb-1 block">Posisi / Role</label>
                    <select wire:model="role" class="w-full rounded-lg border-gray-300 text-sm focus:ring-purple-500 focus:border-purple-500 bg-white">
                        <option value="staff">Staff (Karyawan Umum)</option> {{-- Opsi Baru --}}
                        <option value="finance">Finance (Keuangan)</option>
                        <option value="marketing">Marketing (Kasir)</option>
                        <option value="production">Production (Gudang)</option>
                        <option value="owner">Owner (Pemilik)</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                @if($isEditing)
                    <button type="button" wire:click="cancel" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 text-sm font-bold rounded-lg hover:bg-gray-50">Batal</button>
                @endif
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white text-sm font-bold rounded-lg hover:bg-purple-700 shadow-md transition">
                    {{ $isEditing ? 'Simpan Perubahan' : '+ Tambah User' }}
                </button>
            </div>
        </form>
    </div>

    {{-- Notifikasi --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mt-4 bg-green-100 text-green-700 text-xs font-bold px-4 py-3 rounded-xl text-center animate-fade-in-down">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mt-4 bg-red-100 text-red-700 text-xs font-bold px-4 py-3 rounded-xl text-center animate-fade-in-down">
            {{ session('error') }}
        </div>
    @endif
</div>