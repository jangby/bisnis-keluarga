<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800">⚙️ Pengaturan Absensi</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 h-fit">
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2 flex items-center gap-2">
                📍 Titik Lokasi Kantor
            </h3>

            <form wire:submit.prevent="saveLocation" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kantor</label>
                    <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase">Latitude</label>
                        <input type="text" wire:model="latitude" readonly class="mt-1 block w-full bg-gray-50 text-sm rounded-md border-gray-300 text-gray-500 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase">Longitude</label>
                        <input type="text" wire:model="longitude" readonly class="mt-1 block w-full bg-gray-50 text-sm rounded-md border-gray-300 text-gray-500 cursor-not-allowed">
                    </div>
                </div>

                <div x-data>
                    <button type="button" 
                            @click="
                                if(navigator.geolocation) {
                                    $el.innerText = 'Sedang mencari titik satelit...';
                                    $el.disabled = true;

                                    navigator.geolocation.getCurrentPosition(
                                        (position) => {
                                            @this.set('latitude', position.coords.latitude);
                                            @this.set('longitude', position.coords.longitude);
                                            
                                            // [UBAH INI] Ganti alert() dengan dispatch event notify
                                            window.dispatchEvent(new CustomEvent('notify', { 
                                                detail: { 
                                                    message: 'Koordinat didapatkan! Akurasi: ' + Math.round(position.coords.accuracy) + 'm', 
                                                    type: 'success' 
                                                } 
                                            }));

                                            $el.innerText = 'Update Titik Lokasi Saya';
                                            $el.disabled = false;
                                        },
                                        (error) => {
                                            // [UBAH INI] Error juga pakai Toast
                                            window.dispatchEvent(new CustomEvent('notify', { 
                                                detail: { 
                                                    message: 'Gagal ambil GPS: ' + error.message, 
                                                    type: 'error' 
                                                } 
                                            }));

                                            $el.innerText = 'Update Titik Lokasi Saya';
                                            $el.disabled = false;
                                        },
                                        {
                                            enableHighAccuracy: true, 
                                            timeout: 20000, 
                                            maximumAge: 0
                                        }
                                    );
                                } else {
                                    alert('Browser tidak support GPS');
                                }
                            "
                            class="w-full py-2 px-3 bg-teal-50 text-teal-700 border border-teal-100 hover:bg-teal-100 rounded-lg flex items-center justify-center gap-2 text-sm font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Update Titik Lokasi Saya
                    </button>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Radius (Meter)</label>
                    <div class="flex items-center gap-2">
                        <input type="number" wire:model="radius_meters" class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <span class="text-sm text-gray-500">Meter dari titik pusat</span>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md transition transform active:scale-95">
                        Simpan Lokasi
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 h-fit">
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2 flex items-center gap-2">
                📅 Kalender Libur
            </h3>
            
            <p class="text-xs text-gray-500 mb-4">
                Pegawai tidak akan dianggap "Alpha" jika tidak absen pada tanggal yang terdaftar di sini.
            </p>

            <form wire:submit.prevent="addHoliday" class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                    <div class="sm:col-span-1">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                        <input type="date" wire:model="holiday_date" class="w-full text-sm rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('holiday_date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Keterangan</label>
                        <div class="flex gap-2">
                            <input type="text" wire:model="holiday_desc" placeholder="Misal: Cuti Bersama" class="w-full text-sm rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <button type="submit" class="bg-indigo-600 text-white px-3 py-2 rounded-md hover:bg-indigo-700 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        </div>
                         @error('holiday_desc') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </form>

            <div class="overflow-hidden rounded-lg border border-gray-100">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium">
                        <tr>
                            <th class="px-4 py-2">Tanggal</th>
                            <th class="px-4 py-2">Keterangan</th>
                            <th class="px-4 py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($holidays as $h)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="font-mono text-gray-700 bg-white border border-gray-200 px-2 py-0.5 rounded">
                                        {{ $h->date->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $h->description }}</td>
                                <td class="px-4 py-3 text-right">
                                    <button wire:click="deleteHoliday({{ $h->id }})" 
                                            wire:confirm="Yakin ingin menghapus hari libur ini?"
                                            class="text-red-500 hover:text-red-700 p-1 hover:bg-red-50 rounded transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-400 italic">
                                    Belum ada jadwal libur.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $holidays->links() }}
            </div>
        </div>

    </div>
</div>