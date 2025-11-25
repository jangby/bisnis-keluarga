<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-2xl font-bold text-gray-800">📦 Manajemen Pesanan Web</h2>
            
            <div class="flex bg-white rounded-lg shadow-sm p-1">
                @foreach(['all' => 'Semua', 'pending' => 'Baru', 'processing' => 'Diproses', 'completed' => 'Selesai', 'cancelled' => 'Batal'] as $key => $label)
                    <button wire:click="$set('filterStatus', '{{ $key }}')" 
                        class="px-4 py-2 text-sm font-bold rounded-md transition {{ $filterStatus == $key ? 'bg-indigo-600 text-white shadow' : 'text-gray-500 hover:bg-gray-50' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">
                {{ session('message') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($orders as $order)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row gap-6 relative overflow-hidden">
                    
                    <div class="absolute left-0 top-0 bottom-0 w-2 
                        {{ $order->status == 'pending' ? 'bg-orange-400' : '' }}
                        {{ $order->status == 'processing' ? 'bg-blue-500' : '' }}
                        {{ $order->status == 'completed' ? 'bg-green-500' : '' }}
                        {{ $order->status == 'cancelled' ? 'bg-red-500' : '' }}
                    "></div>

                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold text-gray-400 uppercase">#ORD-{{ $order->id }}</span>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full uppercase
                                {{ $order->status == 'pending' ? 'bg-orange-100 text-orange-600' : '' }}
                                {{ $order->status == 'processing' ? 'bg-blue-100 text-blue-600' : '' }}
                                {{ $order->status == 'completed' ? 'bg-green-100 text-green-600' : '' }}
                                {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-600' : '' }}">
                                {{ $order->status }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">{{ $order->guest_name }}</h3>
                        <div class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                            <span>📞</span> <a href="https://wa.me/{{ $order->guest_phone }}" target="_blank" class="hover:text-green-600">{{ $order->guest_phone }}</a>
                        </div>
                        <div class="text-sm text-gray-500 mt-2 bg-gray-50 p-2 rounded">
                            📍 {{ $order->delivery_address }}
                        </div>
                        <div class="text-xs text-gray-400 mt-2">
                            {{ $order->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <div class="flex-1 border-l border-gray-100 pl-0 md:pl-6">
                        <h4 class="text-sm font-bold text-gray-500 uppercase mb-2">Item Pesanan</h4>
                        <ul class="space-y-2">
                            @foreach($order->items as $item)
                                <li class="flex justify-between text-sm">
                                    <span class="text-gray-700">{{ $item->quantity }}x {{ $item->product_name }}</span>
                                    <span class="font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="border-t border-dashed border-gray-200 mt-3 pt-2 flex justify-between items-center">
                            <span class="font-bold text-gray-600">Total</span>
                            <span class="font-black text-xl text-indigo-600">Rp {{ number_format($order->total_amount, 0) }}</span>
                        </div>
                    </div>

                    <div class="flex flex-row md:flex-col justify-center gap-2 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 pl-0 md:pl-6">
                        
                        @if($order->status == 'pending')
                            <button wire:click="confirmProcess({{ $order->id }})" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                                <span>⚡</span> Proses Order
                            </button>
                            <button wire:click="rejectOrder({{ $order->id }})" onclick="return confirm('Tolak pesanan ini?')" class="bg-white border border-red-200 text-red-600 px-4 py-2 rounded-lg font-bold hover:bg-red-50 transition">
                                ✖ Tolak
                            </button>
                        @endif

                        @if($order->status == 'processing')
                            <div class="text-center text-xs text-gray-500 mb-2">Stok sudah dipotong & Saldo masuk.</div>
                            <button wire:click="markAsCompleted({{ $order->id }})" class="bg-green-600 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-green-700 transition">
                                ✅ Tandai Selesai
                            </button>
                        @endif

                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-white rounded-xl border border-dashed border-gray-300">
                    <div class="text-4xl mb-3">📭</div>
                    <p class="text-gray-500">Belum ada pesanan masuk.</p>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>

        @if($showProcessModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
                <div class="bg-white p-6 rounded-2xl w-full max-w-md shadow-2xl">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Terima Pesanan & Pembayaran</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Stok produk akan berkurang otomatis. Pilih kemana uang pembayaran ini masuk:
                    </p>

                    <div class="space-y-3 mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Pilih Dompet (Wallet)</label>
                        <select wire:model="targetWalletId" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 py-3">
                            @foreach($wallets as $wallet)
                                <option value="{{ $wallet->id }}">{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showProcessModal', false)" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded-lg">Batal</button>
                        <button wire:click="processOrder" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg shadow-lg hover:bg-indigo-700">
                            Simpan & Proses
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>