<div class="p-6 bg-white shadow-sm sm:rounded-lg border border-gray-200"
     x-data="{
        loadingLoc: true,
        geoId: null,
        
        initLocation() {
            if (navigator.geolocation) {
                // Gunakan watchPosition agar terus update sampai akurasi tinggi didapat
                this.geoId = navigator.geolocation.watchPosition(
                    (position) => {
                        // Kirim koordinat ke Livewire Component
                        @this.setLocation(position.coords.latitude, position.coords.longitude);
                        this.loadingLoc = false;
                        console.log('Akurasi GPS: ' + position.coords.accuracy + ' meter');
                    },
                    (error) => {
                        // Jangan alert terus menerus, cukup console log atau handle sekali
                        console.error('Error GPS:', error);
                        // Jika timeout/error, loading tetap dimatikan agar user tau
                        this.loadingLoc = false; 
                    },
                    {
                        enableHighAccuracy: true, // WAJIB: Paksa pakai GPS Hardware
                        timeout: 20000,           // Tunggu sampai 20 detik jika sinyal susah
                        maximumAge: 0             // Jangan pakai cache lokasi lama
                    }
                );
            } else {
                alert('Browser Anda tidak mendukung Geolocation.');
            }
        },
        stopLocation() {
            if (this.geoId !== null) {
                navigator.geolocation.clearWatch(this.geoId);
            }
        }
     }"
     x-init="initLocation()"
     x-on:livewire:navigating.window="stopLocation()" {{-- Matikan GPS saat pindah halaman agar hemat baterai --}}
     >

    <div class="text-center">
        <h2 class="text-lg font-bold text-gray-800 mb-2">📍 Mesin Absensi</h2>
        
        <div x-show="loadingLoc" class="flex flex-col items-center justify-center p-4">
            <svg class="animate-spin h-8 w-8 text-blue-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm text-gray-500 animate-pulse">Sedang mencari titik GPS akurat...</span>
            <p class="text-xs text-gray-400 mt-1">(Pastikan GPS aktif & Izinkan Lokasi)</p>
        </div>

        @if($latitude && $longitude)
            <div class="mt-4" wire:transition.fade>
                <p class="text-sm text-gray-600">Jarak ke Kantor:</p>
                <p class="text-3xl font-bold {{ $is_within_radius ? 'text-green-600' : 'text-red-600' }}">
                    {{ $distance }} Meter
                </p>
                @if(!$is_within_radius)
                    <p class="text-xs text-red-500 mt-1 font-bold">Anda terlalu jauh! Radius max: {{ $office->radius_meters }}m.</p>
                    <button @click="window.location.reload()" class="mt-2 text-xs text-blue-600 underline">Refresh GPS</button>
                @else
                    <p class="text-xs text-green-500 mt-1 font-bold">Posisi Aman. Silakan absen.</p>
                @endif
            </div>

            <div class="mt-6 space-y-3">
                @if(!$todayAttendance)
                    <button wire:click="attendance('in')" 
                            wire:loading.attr="disabled"
                            {{-- Disable tombol jika diluar radius --}}
                            {{ !$is_within_radius ? 'disabled' : '' }} 
                            class="w-full py-3 px-4 text-white font-bold rounded-lg shadow-lg transition transform hover:scale-105 disabled:opacity-50 disabled:scale-100 disabled:cursor-not-allowed
                            {{ $is_within_radius ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400' }}">
                        <span wire:loading.remove>🕒 ABSEN MASUK</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                @elseif(!$todayAttendance->clock_out)
                    <div class="bg-blue-50 p-3 rounded text-sm text-blue-800 mb-4 border border-blue-100">
                        Anda masuk pukul: <strong>{{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}</strong>
                    </div>

                    <button wire:click="attendance('out')" 
                            wire:loading.attr="disabled"
                            {{ !$is_within_radius ? 'disabled' : '' }}
                            class="w-full py-3 px-4 text-white font-bold rounded-lg shadow-lg transition transform hover:scale-105 disabled:opacity-50 disabled:scale-100 disabled:cursor-not-allowed
                            {{ $is_within_radius ? 'bg-orange-500 hover:bg-orange-600' : 'bg-gray-400' }}">
                        <span wire:loading.remove>👋 ABSEN PULANG</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                @else
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Selesai!</strong>
                        <span class="block sm:inline">Absen hari ini tuntas.</span>
                        <div class="mt-2 text-sm">
                            Masuk: {{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }} <br>
                            Pulang: {{ \Carbon\Carbon::parse($todayAttendance->clock_out)->format('H:i') }}
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if($error_message)
            <div class="mt-4 p-2 bg-red-100 text-red-700 rounded text-sm border border-red-200">
                {{ $error_message }}
            </div>
        @endif
    </div>
</div>