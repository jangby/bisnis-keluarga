<div class="p-6 bg-white shadow-sm sm:rounded-lg border border-gray-200">
    <h2 class="text-lg font-bold text-gray-800 mb-4">📝 Pengajuan Izin / Sakit</h2>

    <form wire:submit.prevent="submit" class="space-y-4">
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" wire:model="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" wire:model="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jenis</label>
            <select wire:model="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="sakit">Sakit 🤒</option>
                <option value="izin">Izin Pribadi 🏠</option>
                <option value="cuti">Cuti ✈️</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Alasan Lengkap</label>
            <textarea wire:model="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Jelaskan alasan Anda..."></textarea>
            @error('reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Bukti Foto (Wajib untuk Sakit)</label>
            <input type="file" wire:model="photo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
            
            <div wire:loading wire:target="photo" class="text-xs text-blue-500 mt-1">Sedang mengupload...</div>
            
            @if ($photo)
                <div class="mt-2">
                    <p class="text-xs text-gray-500 mb-1">Preview:</p>
                    <img src="{{ $photo->temporaryUrl() }}" class="w-24 h-24 object-cover rounded-lg border">
                </div>
            @endif
            @error('photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <button type="submit" 
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
            <span wire:loading.remove>Kirim Pengajuan</span>
            <span wire:loading>Mengirim...</span>
        </button>
    </form>
</div>