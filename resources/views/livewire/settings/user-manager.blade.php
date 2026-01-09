<div class="p-6 sm:p-8">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h4 class="font-black text-gray-900 text-xl tracking-tight">Tim & Akses</h4>
            <p class="text-sm text-gray-500 mt-1">Kelola akun login untuk karyawan.</p>
        </div>
        <span class="bg-purple-50 text-purple-700 text-xs font-bold px-3 py-1.5 rounded-lg border border-purple-100">
            {{ count($users) }} User Aktif
        </span>
    </div>

    {{-- ID CARD GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">
        @foreach($users as $user)
            <div class="group bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-purple-100/50 hover:border-purple-200 transition-all duration-300 relative overflow-hidden">
                
                {{-- Decorative Header --}}
                <div class="absolute top-0 right-0 px-2 py-1 rounded-bl-lg text-[10px] font-bold uppercase tracking-wider
                    {{ $user->role == 'owner' ? 'bg-purple-600 text-white' : 
                      ($user->role == 'finance' ? 'bg-blue-100 text-blue-700' : 
                      ($user->role == 'marketing' ? 'bg-green-100 text-green-700' : 
                      ($user->role == 'production' ? 'bg-orange-100 text-orange-700' : 
                      ($user->role == 'inventory' ? 'bg-teal-100 text-teal-700' : 
                      'bg-gray-200 text-gray-600')))) }}">
                    {{ $user->role }}
                </div>

                {{-- Role Badge (Redundant but kept for consistent layout if needed, otherwise removed or simplified) --}}
                <div class="flex justify-end mb-2 opacity-0">
                     <span class="text-[10px] font-bold uppercase tracking-wider py-1 px-2 rounded-md">
                        {{ $user->role }}
                    </span>
                </div>

                <div class="flex items-center gap-4 mb-4">
                    {{-- Avatar --}}
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-bold text-xl text-white shadow-md
                        {{ $user->role == 'owner' ? 'bg-gradient-to-br from-purple-500 to-indigo-600' : 'bg-gray-800' }}">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <h5 class="font-bold text-gray-900 text-base truncate">{{ $user->name }}</h5>
                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                        @if($user->phone)
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $user->phone }}</p>
                        @endif
                    </div>
                </div>

                {{-- Salary Info (Small) --}}
                @if($user->daily_salary > 0)
                    <div class="bg-gray-50 rounded-lg p-2 mb-4 flex justify-between items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Gaji/Hari</span>
                        <span class="text-xs font-bold text-gray-700">Rp {{ number_format($user->daily_salary, 0, ',', '.') }}</span>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="grid grid-cols-2 gap-2 mt-auto">
                    <button wire:click="edit({{ $user->id }})" class="py-2 text-xs font-bold text-gray-600 bg-gray-50 hover:bg-purple-600 hover:text-white rounded-xl transition-colors">
                        Edit Profil
                    </button>
                    @if($user->id != Auth::id())
                        <button wire:click="delete({{ $user->id }})" 
                                wire:confirm="Apakah Anda yakin ingin menghapus user ini?" 
                                class="py-2 text-xs font-bold text-red-500 bg-red-50 hover:bg-red-600 hover:text-white rounded-xl transition-colors">
                            Hapus
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- MODERN FORM --}}
    <div class="bg-purple-50/50 p-6 sm:p-8 rounded-[2rem] border border-purple-100 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-purple-200 rounded-full blur-3xl opacity-20 -mr-16 -mt-16 pointer-events-none"></div>

        <h5 class="text-sm font-black text-purple-900 uppercase tracking-widest mb-6 flex items-center gap-2 relative z-10">
            <span class="w-2 h-2 bg-purple-600 rounded-full animate-pulse"></span>
            {{ $isEditing ? 'Mode Edit User' : 'Form User Baru' }}
        </h5>
        
        <form wire:submit="save" class="space-y-5 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                {{-- Nama --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-purple-700 uppercase ml-1">Nama Lengkap</label>
                    <input wire:model="name" type="text" class="w-full rounded-xl border-0 ring-1 ring-purple-200 shadow-sm text-sm py-3 px-4 focus:ring-2 focus:ring-purple-600 transition" placeholder="Nama Staff">
                    @error('name') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                </div>

                {{-- Email --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-purple-700 uppercase ml-1">Email Login</label>
                    <input wire:model="email" type="email" class="w-full rounded-xl border-0 ring-1 ring-purple-200 shadow-sm text-sm py-3 px-4 focus:ring-2 focus:ring-purple-600 transition" placeholder="email@sistem.com">
                    @error('email') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-purple-700 uppercase ml-1">Password {{ $isEditing ? '(Opsional)' : '' }}</label>
                    <input wire:model="password" type="text" class="w-full rounded-xl border-0 ring-1 ring-purple-200 shadow-sm text-sm py-3 px-4 focus:ring-2 focus:ring-purple-600 transition" placeholder="Minimal 6 karakter">
                </div>

                {{-- Role --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-purple-700 uppercase ml-1">Posisi / Role</label>
                    <div class="relative">
                        <select wire:model="role" class="w-full rounded-xl border-0 ring-1 ring-purple-200 shadow-sm text-sm py-3 px-4 focus:ring-2 focus:ring-purple-600 transition bg-white appearance-none cursor-pointer">
                            <option value="staff">Staff (Karyawan Umum)</option>
                            <option value="finance">Finance (Keuangan)</option>
                            <option value="marketing">Marketing (Kasir)</option>
                            <option value="production">Production (Produksi)</option>
                            <option value="inventory">Inventory (Gudang)</option>
                            <option value="owner">Owner (Pemilik)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-purple-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Gaji --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-purple-700 uppercase ml-1">Gaji Harian (Rp)</label>
                    <input wire:model="daily_salary" type="number" class="w-full rounded-xl border-0 ring-1 ring-purple-200 shadow-sm text-sm py-3 px-4 focus:ring-2 focus:ring-purple-600 transition" placeholder="100000">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-purple-100 mt-2">
                @if($isEditing)
                    <button type="button" wire:click="cancel" class="px-6 py-3 bg-white border border-gray-300 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition">Batalkan</button>
                @endif
                <button type="submit" class="px-8 py-3 bg-purple-600 text-white text-sm font-bold rounded-xl hover:bg-purple-700 shadow-lg shadow-purple-500/30 hover:scale-[1.02] transition-all">
                    {{ $isEditing ? 'Simpan Data User' : '+ Tambah User Baru' }}
                </button>
            </div>
        </form>
    </div>

</div>