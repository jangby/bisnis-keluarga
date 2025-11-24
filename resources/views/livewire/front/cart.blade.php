<div class="bg-gray-50 min-h-screen pb-40"> <div class="sticky top-0 z-30 bg-white border-b border-gray-100 px-4 py-4 flex items-center gap-3">
        <h2 class="text-lg font-bold text-gray-800">Tas Belanja</h2>
    </div>

    <div class="p-4 space-y-4">
        @forelse($items as $item)
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 flex gap-3">
                <div class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">🍲</div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-800 text-sm line-clamp-2">{{ $item->product->name }}</h3>
                    <div class="flex items-center justify-between mt-2">
                        <div class="font-bold text-indigo-600 text-sm">Rp {{ number_format($item->subtotal, 0) }}</div>
                        <div class="flex items-center bg-gray-100 rounded-lg p-1">
                            <button wire:click="decrement({{ $item->id }})" class="w-7 h-7 bg-white rounded shadow-sm text-xs font-bold">-</button>
                            <span class="w-8 text-center text-xs font-bold">{{ $item->quantity }}</span>
                            <button wire:click="increment({{ $item->id }})" class="w-7 h-7 bg-indigo-600 text-white rounded shadow-sm text-xs font-bold">+</button>
                        </div>
                    </div>
                </div>
                <button wire:click="removeItem({{ $item->id }})" class="text-red-400 self-start">&times;</button>
            </div>
        @empty
            <div class="text-center py-10 text-gray-400">Keranjang kosong.</div>
        @endforelse
    </div>

    @if(count($items) > 0)
        <div class="px-4 mt-2">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                <h3 class="font-bold text-gray-800 border-b pb-2 mb-2">Informasi Pengiriman</h3>
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Penerima</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-2.5">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nomor WhatsApp</label>
                    <input type="tel" wire:model="phone" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm py-2.5" placeholder="08xxxxxxxxxx">
                    @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Alamat Lengkap</label>
                    <textarea wire:model="address" rows="2" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white text-sm" placeholder="Jalan, No Rumah, Patokan..."></textarea>
                    @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="fixed bottom-16 left-0 w-full bg-white border-t border-gray-100 p-4 shadow-[0_-4px_10px_rgba(0,0,0,0.05)] z-40">
            <div class="max-w-md mx-auto">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-gray-500 text-xs font-bold uppercase">Total Pembayaran</span>
                    <span class="text-xl font-black text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                
                <button wire:click="checkout" 
                   class="w-full bg-[#25D366] hover:bg-[#20bd5a] text-white py-3.5 rounded-xl font-bold text-base shadow-lg shadow-green-200 transition transform active:scale-[0.98] flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    Pesan & Kirim ke WA
                </button>
            </div>
        </div>
    @endif
</div>