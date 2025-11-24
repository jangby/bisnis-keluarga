<div class="min-h-screen bg-gray-50 pb-32 font-sans text-gray-800">
    
    {{-- HEADER (Sticky & Clean) --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="w-full md:max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-lg md:text-xl font-bold text-gray-900">Pengaturan</h1>
                <p class="text-xs text-gray-500">Konfigurasi sistem & data master.</p>
            </div>
            <div class="hidden md:block">
                <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                    Role: {{ Auth::user()->role }}
                </span>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT CONTAINER --}}
    <div class="w-full md:max-w-7xl mx-auto md:px-6 lg:px-8 py-0 md:py-8 space-y-0 md:space-y-10">

        {{-- 1. AREA KEUANGAN (Owner & Finance) --}}
        @if(in_array(Auth::user()->role, ['owner', 'finance']))
            <section class="md:rounded-2xl overflow-hidden">
                {{-- Judul Section --}}
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 md:bg-transparent md:border-none md:p-0 md:mb-4 sticky top-[61px] z-20 md:static">
                    <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span> Keuangan & Akun
                    </h3>
                </div>
                
                {{-- Wallet Manager --}}
                <div class="bg-white border-b md:border border-gray-200 md:rounded-2xl md:shadow-sm overflow-hidden">
                    <livewire:settings.wallet-manager />
                </div>

                {{-- Supplier Manager --}}
                <div class="mt-0 md:mt-8">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 md:bg-transparent md:border-none md:p-0 md:mb-4 sticky top-[61px] z-20 md:static">
                        <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span> Relasi Supplier
                        </h3>
                    </div>
                    <div class="bg-white border-b md:border border-gray-200 md:rounded-2xl md:shadow-sm overflow-hidden">
                        <livewire:settings.contact-manager type="supplier" title="Database Supplier" />
                    </div>
                </div>
            </section>
        @endif

        {{-- 2. AREA PEMASARAN (Owner & Marketing) --}}
        @if(in_array(Auth::user()->role, ['owner', 'marketing']))
            <section class="md:rounded-2xl overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 md:bg-transparent md:border-none md:p-0 md:mb-4 sticky top-[61px] z-20 md:static">
                    <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Area Pemasaran
                    </h3>
                </div>
                
                <div class="bg-white border-b md:border border-gray-200 md:rounded-2xl md:shadow-sm overflow-hidden">
                    <livewire:settings.contact-manager type="customer" title="Database Pelanggan" />
                </div>
            </section>
        @endif

        {{-- 3. AREA PRODUKSI (Owner & Production) --}}
        @if(in_array(Auth::user()->role, ['owner', 'production']))
            <section class="md:rounded-2xl overflow-hidden pb-4 bg-white md:bg-transparent">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 md:bg-transparent md:border-none md:p-0 md:mb-4 sticky top-[61px] z-20 md:static">
                    <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span> Dapur Produksi
                    </h3>
                </div>
                
                <div class="p-4 md:p-0 grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Card Input Bahan Baku --}}
                    <a href="{{ route('products.create', ['type' => 'material']) }}" class="flex items-center p-4 bg-white md:rounded-2xl border border-gray-200 shadow-sm active:bg-gray-50 transition group hover:border-orange-300">
                        <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 text-sm">Input Bahan Baku</h4>
                            <p class="text-xs text-gray-500">Tambah stok mentah baru</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-300 group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    {{-- Placeholder Lainnya --}}
                    <div class="flex items-center p-4 bg-gray-50 md:rounded-2xl border border-gray-200 border-dashed opacity-60">
                        <div class="w-12 h-12 rounded-xl bg-gray-200 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-500 text-sm">Fitur Produksi Lain</h4>
                            <p class="text-xs text-gray-400">Segera hadir</p>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- 4. MANAJEMEN USER (KHUSUS OWNER) --}}
        @if(Auth::user()->role === 'owner')
            <section class="md:rounded-2xl overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 md:bg-transparent md:border-none md:p-0 md:mb-4 sticky top-[61px] z-20 md:static">
                    <h3 class="font-bold text-xs text-gray-500 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-purple-500 rounded-full"></span> Akses Pengguna
                    </h3>
                </div>
                
                <div class="bg-white border-b md:border border-gray-200 md:rounded-2xl md:shadow-sm overflow-hidden">
                    <livewire:settings.user-manager />
                </div>
            </section>
        @endif

        {{-- TOMBOL LOGOUT --}}
        <div class="px-4 py-8 md:px-0 border-t border-gray-200 md:border-none bg-white md:bg-transparent">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-red-50 text-red-600 font-bold text-sm rounded-xl border border-red-100 hover:bg-red-100 hover:text-red-700 transition flex items-center justify-center gap-2 active:scale-[0.98]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar Aplikasi
                </button>
            </form>
            <p class="text-center md:text-left text-[10px] text-gray-400 mt-4">
                App Version 1.0.0 &bull; Bisnis Keluarga
            </p>
        </div>

    </div>
</div>