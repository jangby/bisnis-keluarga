<div class="min-h-screen bg-gray-50 pb-24">
    
    <div class="bg-white p-4 shadow-sm sticky top-0 z-10">
        <h2 class="font-bold text-xl text-gray-800">Pengaturan</h2>
        <p class="text-xs text-gray-500">Kelola data master bisnis.</p>
    </div>

    <div class="p-4 space-y-6">

        @if(in_array(Auth::user()->role, ['owner', 'finance']))
            <div class="space-y-4">
                <h3 class="font-bold text-sm text-blue-800 uppercase">Keuangan & Pembayaran</h3>
                
                <livewire:settings.wallet-manager />

                <h3 class="font-bold text-sm text-blue-800 uppercase mt-6">Relasi Bisnis</h3>
                <livewire:settings.contact-manager type="supplier" title="Kelola Supplier" />
            </div>
        @endif

        @if(in_array(Auth::user()->role, ['owner', 'marketing']))
            <div class="space-y-4">
                <h3 class="font-bold text-sm text-green-800 uppercase">Pemasaran</h3>
                <livewire:settings.contact-manager type="customer" title="Kelola Agen / Pelanggan" />
            </div>
        @endif

        @if(in_array(Auth::user()->role, ['owner', 'production']))
            <div class="space-y-4">
                <h3 class="font-bold text-sm text-orange-800 uppercase">Dapur Produksi</h3>
                
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                    <p class="font-bold text-gray-700 mb-2">Bahan Baku (Material)</p>
                    <p class="text-xs text-gray-500 mb-3">Input stok awal tepung, kedelai, gula, dll di sini.</p>
                    <a href="{{ route('products.create', ['type' => 'material']) }}" class="block w-full py-2 bg-orange-100 text-orange-700 font-bold text-center rounded-lg text-xs hover:bg-orange-200">
                        + Input Bahan Baku Baru
                    </a>
                </div>
            </div>
        @endif

        <div class="pt-6 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-3 bg-red-50 text-red-600 font-bold rounded-xl border border-red-100 hover:bg-red-100">
                    Keluar Aplikasi
                </button>
            </form>
        </div>

    </div>
</div>