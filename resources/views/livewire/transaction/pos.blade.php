<div class="pb-32 bg-gray-50 min-h-screen relative">
    
    <div class="bg-white p-4 shadow-sm sticky top-0 z-10 flex justify-between items-center">
        <h2 class="font-bold text-gray-800">Kasir / Penjualan</h2>
        <div class="text-right">
             <select wire:model="wallet_id" class="text-xs border-gray-300 rounded-lg py-1">
                @foreach($wallets as $wallet)
                    <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                @endforeach
             </select>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-500 text-white text-center p-2 text-sm font-bold">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-500 text-white text-center p-2 text-sm font-bold">
            {{ session('error') }}
        </div>
    @endif

    <div class="p-4 grid grid-cols-2 gap-3">
        @foreach($products as $product)
            <div wire:click="addToCart({{ $product->id }})" 
                 class="bg-white p-3 rounded-xl shadow-sm border border-gray-100 cursor-pointer hover:border-blue-500 active:scale-95 transition relative overflow-hidden">
                
                <span class="absolute top-0 right-0 bg-gray-200 text-[10px] px-2 py-1 rounded-bl-lg text-gray-600 font-bold">
                    Stok: {{ $product->current_stock }}
                </span>

                <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold mb-2 text-sm">
                    {{ substr($product->name, 0, 2) }}
                </div>

                <h3 class="text-xs font-bold text-gray-800 line-clamp-2 h-8 leading-tight">{{ $product->name }}</h3>
                <p class="text-sm font-bold text-blue-600 mt-1">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
            </div>
        @endforeach
    </div>

    @if(!empty($cart))
        <div class="fixed bottom-16 left-0 right-0 bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] p-4 rounded-t-2xl z-20 max-w-md mx-auto">
            
            <div class="flex justify-between items-center mb-3 border-b pb-2">
                <span class="font-bold text-gray-700">Keranjang ({{ count($cart) }} Item)</span>
                <span class="font-bold text-xl text-blue-600">Total: Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
            </div>

            <div class="max-h-32 overflow-y-auto space-y-2 mb-3">
                @foreach($cart as $id => $item)
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex-1">
                            <p class="font-medium truncate">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-400">Rp {{ number_format($item['price'], 0, ',', '.') }} x {{ $item['qty'] }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button wire:click="removeFromCart({{ $id }})" class="w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold hover:bg-red-200">-</button>
                            <span class="font-bold w-4 text-center">{{ $item['qty'] }}</span>
                            <button wire:click="addToCart({{ $id }})" class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold hover:bg-green-200">+</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button wire:click="checkout" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg flex justify-between px-6 active:scale-95 transition">
                <span>BAYAR SEKARANG</span>
                <span>Rp {{ number_format($totalAmount, 0, ',', '.') }} &rarr;</span>
            </button>
        </div>
    @endif

</div>