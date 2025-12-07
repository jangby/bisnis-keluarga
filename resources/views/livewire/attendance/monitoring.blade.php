<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white p-4 rounded-xl shadow-sm border border-gray-200 gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-800">📊 Monitoring Absensi</h2>
            <p class="text-xs text-gray-500">Pantau kehadiran karyawan harian</p>
        </div>
        
        <div class="w-full sm:w-auto flex items-center gap-2 bg-gray-50 p-1 rounded-lg border border-gray-200">
            <span class="text-xs text-gray-500 pl-2">Tanggal:</span>
            <input type="date" wire:model.live="dateFilter" class="bg-transparent border-0 text-sm focus:ring-0 p-1 text-gray-700 w-full sm:w-auto">
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:hidden">
        @forelse($attendances as $row)
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 relative">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                            {{ substr($row->user->name, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm">{{ $row->user->name }}</h3>
                            <span class="text-[10px] uppercase tracking-wider text-gray-500 border border-gray-200 px-1.5 py-0.5 rounded">{{ $row->user->role }}</span>
                        </div>
                    </div>
                    <div>
                        @if($row->status == 'hadir')
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-lg">Hadir</span>
                        @elseif($row->status == 'sakit')
                            <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded-lg">Sakit</span>
                        @elseif($row->status == 'izin')
                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded-lg">Izin</span>
                        @else
                            <span class="bg-gray-100 text-gray-700 text-xs font-bold px-2 py-1 rounded-lg">{{ ucfirst($row->status) }}</span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 py-3 border-t border-b border-gray-100 mb-3">
                    <div class="text-center">
                        <span class="text-xs text-gray-400 block mb-1">Masuk</span>
                        @if($row->clock_in)
                            <span class="text-gray-800 font-mono font-bold">{{ \Carbon\Carbon::parse($row->clock_in)->format('H:i') }}</span>
                        @else
                            <span class="text-gray-300">-</span>
                        @endif
                    </div>
                    <div class="text-center border-l border-gray-100">
                        <span class="text-xs text-gray-400 block mb-1">Pulang</span>
                        @if($row->clock_out)
                            <span class="text-gray-800 font-mono font-bold">{{ \Carbon\Carbon::parse($row->clock_out)->format('H:i') }}</span>
                        @else
                            <span class="text-gray-300 italic text-xs">Belum</span>
                        @endif
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="text-xs text-gray-500">
                        @if($row->lat_in)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $row->lat_in }},{{ $row->long_in }}" target="_blank" class="flex items-center gap-1 text-blue-600 hover:text-blue-800">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Cek Lokasi
                            </a>
                        @else
                            <span class="text-gray-400">Lokasi n/a</span>
                        @endif
                    </div>
                    
                    @if($row->note)
                        <button class="text-xs text-gray-500 underline" title="{{ $row->note }}">Lihat Catatan</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                <p class="text-gray-400 text-sm">Tidak ada data absensi.</p>
            </div>
        @endforelse
    </div>

    <div class="hidden md:block bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3">Pegawai</th>
                        <th class="px-6 py-3">Jam Masuk</th>
                        <th class="px-6 py-3">Jam Pulang</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Lokasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($attendances as $row)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600">
                                    {{ substr($row->user->name, 0, 1) }}
                                </div>
                                <div>
                                    {{ $row->user->name }}
                                    <div class="text-xs text-gray-400 capitalize">{{ $row->user->role }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($row->clock_in)
                                    <span class="bg-green-50 text-green-700 border border-green-200 text-xs font-mono font-bold px-2 py-1 rounded">{{ \Carbon\Carbon::parse($row->clock_in)->format('H:i') }}</span>
                                @else - @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($row->clock_out)
                                    <span class="bg-orange-50 text-orange-700 border border-orange-200 text-xs font-mono font-bold px-2 py-1 rounded">{{ \Carbon\Carbon::parse($row->clock_out)->format('H:i') }}</span>
                                @else <span class="text-gray-300 italic text-xs">Belum pulang</span> @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($row->status == 'hadir')
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Hadir
                                    </span>
                                @elseif($row->status == 'sakit')
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded text-xs font-medium bg-red-100 text-red-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Sakit
                                    </span>
                                @else
                                    <span class="py-1 px-2 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($row->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if($row->lat_in)
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $row->lat_in }},{{ $row->long_in }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Peta
                                    </a>
                                @else - @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 bg-gray-50">
                                Tidak ada data absensi pada tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $attendances->links() }}
    </div>
</div>