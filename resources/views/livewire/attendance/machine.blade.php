<div class="p-6 bg-white shadow-sm sm:rounded-lg border border-gray-200"
     x-data="{
        loadingLoc: true,
        initLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        // Kirim koordinat ke Livewire Component
                        @this.setLocation(position.coords.latitude, position.coords.longitude);
                        this.loadingLoc = false;
                    },
                    (error) => {
                        alert('Gagal mengambil lokasi. Pastikan GPS aktif!');
                        this.loadingLoc = false;
                    }
                );
            } else {
                alert('Browser Anda tidak mendukung Geolocation.');
            }
        }
     }"
     x-init="initLocation()">

    <div class="text-center">
        <h2 class="text-lg font-bold text-gray-800 mb-2">📍 Mesin Absensi</h2>
        
        <div x-show="loadingLoc" class="text-sm text-gray-500 animate-pulse">
            Sedang mencari titik lokasi Anda...
        </div>

        @if($latitude && $longitude)
            <div class="mt-4">
                <p class="text-sm text-gray-600">Jarak ke Kantor:</p>
                <p class="text-3xl font-bold {{ $is_within_radius ? 'text-green-600' : 'text-red-600' }}">
                    {{ $distance }} Meter
                </p>
                @if(!$is_within_radius)
                    <p class="text-xs text-red-500 mt-1">Anda terlalu jauh dari radius kantor ({{ $office->radius_meters }}m).</p>
                @else
                    <p class="text-xs text-green-500 mt-1">Posisi Aman. Silakan absen.</p>
                @endif
            </div>

            <div class="mt-6 space-y-3">
                @if(!$todayAttendance)
                    <button wire:click="attendance('in')" 
                            wire:loading.attr="disabled"
                            class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg transition transform hover:scale-105 disabled:opacity-50">
                        <span wire:loading.remove>🕒 ABSEN MASUK</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                @elseif(!$todayAttendance->clock_out)
                    <div class="bg-blue-50 p-3 rounded text-sm text-blue-800 mb-4">
                        Anda masuk pukul: <strong>{{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}</strong>
                    </div>

                    <button wire:click="attendance('out')" 
                            wire:loading.attr="disabled"
                            class="w-full py-3 px-4 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-lg shadow-lg transition transform hover:scale-105 disabled:opacity-50">
                        <span wire:loading.remove>👋 ABSEN PULANG</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                @else
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Selesai!</strong>
                        <span class="block sm:inline">Anda sudah menyelesaikan absen hari ini.</span>
                        <div class="mt-2 text-sm">
                            Masuk: {{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }} <br>
                            Pulang: {{ \Carbon\Carbon::parse($todayAttendance->clock_out)->format('H:i') }}
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if($error_message)
            <div class="mt-4 p-2 bg-red-100 text-red-700 rounded text-sm">
                {{ $error_message }}
            </div>
        @endif
    </div>
</div>