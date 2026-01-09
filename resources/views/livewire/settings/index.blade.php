<div class="-mt-12 -mx-4 sm:-mx-6 lg:-mx-8 min-h-screen bg-[#F6F8FD] font-sans relative overflow-x-hidden">
    
    {{-- CSS Animasi (Copy dari Dashboard agar senada) --}}
    <style>
        @keyframes fade-in-up { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fade-in-up 0.5s ease-out forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
    </style>

    {{-- 1. HERO HEADER (Fixed Style) --}}
    <div class="relative w-full bg-[#1e1b4b] rounded-b-[40px] shadow-2xl overflow-hidden pb-16 pt-12">
        {{-- Abstract Background Aura --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div class="absolute top-[-20%] right-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-600 blur-[80px] opacity-30"></div>
            <div class="absolute bottom-[0%] left-[-10%] w-[40%] h-[40%] rounded-full bg-purple-600 blur-[80px] opacity-30"></div>
        </div>

        <div class="relative z-10 px-6 sm:px-8">
            <div class="flex justify-between items-center text-white">
                <div>
                    <h1 class="text-3xl font-black tracking-tight leading-tight mb-1">Pengaturan</h1>
                    <p class="text-sm text-indigo-200 opacity-80">Pusat Kontrol & Konfigurasi Sistem</p>
                </div>
                <div class="hidden md:block">
                    <span class="bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider shadow-lg">
                        {{ Auth::user()->role }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CONTENT (Floating Up) --}}
    <div class="px-4 sm:px-8 -mt-10 relative z-20 pb-24 space-y-8">

        {{-- A. AREA KEUANGAN (Owner & Finance) --}}
        @if(in_array(Auth::user()->role, ['owner', 'finance']))
            <section class="animate-enter delay-100">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg">Keuangan & Aset</h3>
                </div>
                
                {{-- Wallet Manager Component --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-100/50 border border-white overflow-hidden mb-6 relative">
                    <livewire:settings.wallet-manager />
                </div>

                {{-- Supplier Manager --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-100/50 border border-white overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-indigo-500"></div> {{-- Accent Line --}}
                    <livewire:settings.contact-manager type="supplier" title="Database Supplier" />
                </div>
            </section>
        @endif

        {{-- B. AREA PEMASARAN (Owner & Marketing) --}}
        @if(in_array(Auth::user()->role, ['owner', 'marketing']))
            <section class="animate-enter delay-200">
                <div class="flex items-center gap-3 mb-4 px-2">
                     <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg">Area Pemasaran</h3>
                </div>
                
                <div class="bg-white rounded-[2rem] shadow-xl shadow-emerald-100/50 border border-white overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500"></div>
                    <livewire:settings.contact-manager type="customer" title="Database Pelanggan" />
                </div>
            </section>
        @endif

        {{-- C. AREA PRODUKSI (Owner & Production) --}}
        @if(in_array(Auth::user()->role, ['owner', 'production']))
            <section class="animate-enter delay-200">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center text-white shadow-lg shadow-orange-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg">Dapur Produksi</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('products.create', ['type' => 'material']) }}" class="flex items-center p-6 bg-white rounded-3xl shadow-sm border border-gray-100 hover:border-orange-500 hover:shadow-lg transition-all group cursor-pointer">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center mr-5 group-hover:scale-110 transition-transform shadow-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 text-lg">Input Bahan Baku</h4>
                            <p class="text-xs text-gray-500 mt-1">Stok masuk (Gula, Tepung, dll)</p>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                </div>
            </section>
        @endif

        {{-- D. MANAJEMEN USER (Owner Only) --}}
        @if(Auth::user()->role === 'owner')
            <section class="animate-enter delay-300">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg">Manajemen User</h3>
                </div>
                
                <div class="bg-white rounded-[2rem] shadow-xl shadow-purple-100/50 border border-white overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-purple-600"></div>
                    <livewire:settings.user-manager />
                </div>
            </section>
        @endif

        {{-- LOGOUT ZONE --}}
        <div class="animate-enter delay-300 pt-8 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full md:w-auto px-8 py-4 bg-white text-rose-600 font-bold text-sm rounded-2xl border border-rose-100 hover:bg-rose-50 hover:border-rose-200 shadow-sm hover:shadow-lg hover:shadow-rose-500/10 transition-all flex items-center justify-center gap-3 active:scale-[0.98]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar dari Aplikasi
                </button>
            </form>
            <p class="text-center md:text-left text-[10px] text-gray-400 mt-6 tracking-widest uppercase">
                Secure System v2.0 &bull; 2024
            </p>
        </div>

    </div>
</div>