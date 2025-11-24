<div>
    <div class="sticky top-0 z-40 bg-white px-4 pt-4 pb-2 shadow-sm">
        <div class="flex items-center gap-3 mb-3">
            <div class="flex-1 relative">
                <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
                <input type="text" wire:model.live.debounce.300ms="search" 
                    placeholder="Mau cari apa kak?" 
                    class="w-full pl-10 pr-4 py-2 bg-gray-100 border-none rounded-full text-sm focus:ring-2 focus:ring-indigo-500 transition">
            </div>
             <a href="{{ route('front.account') }}" class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border border-gray-200">
                <img src="https://ui-avatars.com/api/?name=Guest&background=random" alt="User">
            </a>
        </div>

        <div class="flex overflow-x-auto gap-2 pb-2 no-scrollbar">
            <button wire:click="changeCategory('all')" 
                class="whitespace-nowrap px-4 py-1.5 rounded-full text-xs font-bold border transition 
                {{ $activeCategory == 'all' ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-600 border-gray-300' }}">
                Semua
            </button>
            @foreach($categories as $cat)
                <button wire:click="changeCategory({{ $cat->id }})" 
                    class="whitespace-nowrap px-4 py-1.5 rounded-full text-xs font-bold border transition flex items-center gap-1
                    {{ $activeCategory == $cat->id ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-600 border-gray-300' }}">
                    <span>{{ $cat->icon }}</span> {{ $cat->name }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="p-4 grid grid-cols-2 gap-4">
        @forelse($products as $product)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full relative group">
                
                <div class="h-32 bg-gray-100 w-full object-cover relative">
                    @if($product->has_discount)
                        <div class="absolute top-2 left-2 bg-red-500 text-white text-[9px] font-bold px-2 py-1 rounded-full shadow-sm">
                            PROMO
                        </div>
                    @endif
                    <div class="w-full h-full flex items-center justify-center text-4xl text-gray-300">
                        🍲
                    </div>
                </div>

                <div class="p-3 flex flex-col flex-1">
                    <h3 class="text-sm font-bold text-gray-800 line-clamp-2 leading-tight mb-1">{{ $product->name }}</h3>
                    
                    <div class="mt-auto">
                        @if($product->has_discount)
                            <div class="text-[10px] text-gray-400 line-through">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</div>
                            <div class="text-sm font-bold text-red-600">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                        @else
                            <div class="text-sm font-bold text-gray-900">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</div>
                        @endif

                        <button wire:click="addToCart({{ $product->id }})" 
                            class="mt-2 w-full py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1 active:scale-95">
                            <span>+</span> Tambah
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center py-10 text-gray-400">
                <div class="text-4xl mb-2">😢</div>
                <p class="text-sm">Produk tidak ditemukan.</p>
            </div>
        @endforelse
    </div>

    <div x-data="{ show: false, msg: '' }" 
         x-on:show-toast.window="show = true; msg = $event.detail.message; setTimeout(() => show = false, 2000)"
         x-show="show"
         x-transition.duration.300ms
         class="fixed bottom-20 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-4 py-2 rounded-full shadow-lg z-50 flex items-center gap-2">
         <span class="text-green-400">✔</span> <span x-text="msg"></span>
    </div>

</div>