<div class="bg-gray-50 min-h-screen pb-24">
    
    <div class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white pt-10 pb-16 px-6 rounded-b-[2.5rem] shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10"></div>
        
        <div class="relative z-10 flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm border-2 border-white/50 flex items-center justify-center text-2xl shadow-inner">
                {{ Auth::check() ? substr(Auth::user()->name, 0, 1) : '?' }}
            </div>
            
            <div>
                @auth
                    <h2 class="text-xl font-bold">{{ Auth::user()->name }}</h2>
                    <p class="text-indigo-100 text-sm">{{ Auth::user()->email }}</p>
                @else
                    <h2 class="text-xl font-bold">Halo, Tamu!</h2>
                    <p class="text-indigo-100 text-sm">Masuk untuk simpan riwayat.</p>
                @endauth
            </div>
        </div>
    </div>

    <div class="px-4 -mt-10 relative z-20">
        
        @guest
            <div class="bg-white p-6 rounded-3xl shadow-md text-center space-y-4">
                <div class="text-5xl mb-2">🔐</div>
                <h3 class="font-bold text-gray-800 text-lg">Akses Penuh Member</h3>
                <p class="text-gray-500 text-sm">Login untuk melihat riwayat pesanan, melacak status, dan menyimpan alamat pengiriman.</p>
                
                <div class="grid grid-cols-2 gap-3 pt-4">
                    <a href="{{ route('login') }}" class="py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-200">Masuk</a>
                    <a href="{{ route('register') }}" class="py-3 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold">Daftar</a>
                </div>
            </div>
        @else
            <div class="flex bg-white rounded-2xl shadow-sm p-1 mb-4">
                <button wire:click="$set('activeTab', 'history')" 
                    class="flex-1 py-2 rounded-xl text-sm font-bold transition {{ $activeTab == 'history' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-400' }}">
                    📦 Riwayat
                </button>
                <button wire:click="$set('activeTab', 'profile')" 
                    class="flex-1 py-2 rounded-xl text-sm font-bold transition {{ $activeTab == 'profile' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-400' }}">
                    👤 Profil
                </button>
            </div>

            @if($activeTab == 'history')
                <div class="space-y-4">
                    @forelse($orders as $order)
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 px-3 py-1 rounded-bl-xl text-[10px] font-bold uppercase tracking-wider
                                {{ $order->status == 'pending' ? 'bg-gray-100 text-gray-500' : '' }}
                                {{ $order->status == 'processing' ? 'bg-yellow-100 text-yellow-600' : '' }}
                                {{ $order->status == 'shipping' ? 'bg-blue-100 text-blue-600' : '' }}
                                {{ $order->status == 'completed' ? 'bg-green-100 text-green-600' : '' }}
                                {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-600' : '' }}">
                                {{ $order->status }}
                            </div>

                            <div class="mb-3">
                                <div class="text-xs text-gray-400">Order #{{ $order->id }}</div>
                                <div class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </div>

                            <div class="space-y-1 mb-3">
                                @foreach($order->items->take(2) as $item)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-700">{{ $item->quantity }}x {{ $item->product_name }}</span>
                                    </div>
                                @endforeach
                                @if($order->items->count() > 2)
                                    <div class="text-xs text-gray-400 italic">+ {{ $order->items->count() - 2 }} item lainnya...</div>
                                @endif
                            </div>

                            <div class="border-t border-gray-50 pt-3 flex justify-between items-center">
                                <span class="font-bold text-gray-800">Rp {{ number_format($order->total_amount, 0) }}</span>
                                <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20mau%20tanya%20status%20order%20%23{{ $order->id }}" target="_blank" class="text-xs font-bold text-indigo-600 flex items-center gap-1">
                                    Tanya Admin <span>💬</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 bg-white rounded-3xl border border-dashed border-gray-200">
                            <div class="text-4xl mb-2 grayscale opacity-50">🧾</div>
                            <p class="text-gray-500 text-sm">Belum ada pesanan.</p>
                            <a href="{{ route('front.index') }}" wire:navigate class="text-indigo-600 font-bold text-sm mt-2 block">Mulai Belanja</a>
                        </div>
                    @endforelse
                </div>
            @endif

            @if($activeTab == 'profile')
                <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 space-y-6">
                    <button wire:click="logout" class="w-full flex items-center justify-between p-3 bg-red-50 text-red-600 rounded-xl font-bold text-sm hover:bg-red-100 transition">
                        <span>Keluar Akun</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>

                    <div class="text-center text-xs text-gray-300 pt-4">
                        Versi Aplikasi v1.0
                    </div>
                </div>
            @endif

        @endguest
    </div>

</div>