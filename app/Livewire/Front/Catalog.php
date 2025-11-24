<?php

namespace App\Livewire\Front;

use Livewire\Component;
use App\Models\WebCategory;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class Catalog extends Component
{
    public $activeCategory = 'all'; // 'all' atau ID kategori
    public $search = '';

    public function render()
    {
        // 1. Ambil Kategori Aktif untuk Menu Atas
        $categories = WebCategory::whereHas('products')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // 2. Query Produk
        $query = Product::where('type', 'goods')
            ->where('current_stock', '>', 0); // Hanya tampilkan yang ready

        // Filter Kategori
        if ($this->activeCategory != 'all') {
            $query->whereHas('web_categories', function($q) {
                $q->where('web_categories.id', $this->activeCategory);
            });
        }

        // Filter Search
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $products = $query->latest()->get();

        return view('livewire.front.catalog', [
            'categories' => $categories,
            'products' => $products
        ])->layout('layouts.front'); // PENTING: Pakai layout front yang baru
    }

    // --- LOGIC KERANJANG BELANJA ---
    public function addToCart($productId)
    {
        // [BARU] Cek apakah user sudah login?
        if (!Auth::check()) {
            // Jika belum, simpan pesan notifikasi
            session()->flash('message', 'Silakan Login untuk mulai memesan 🙏');
            
            // Simpan URL Menu agar setelah login otomatis balik ke sini (opsional, tergantung setting login controller)
            session()->put('url.intended', route('front.index'));

            // Redirect paksa ke halaman login
            return $this->redirect(route('login'), navigate: true);
        }

        // --- KODE LAMA DI BAWAH INI TETAP SAMA ---
        $product = Product::find($productId);
        if(!$product || $product->current_stock < 1) return;

        $sessionId = Session::getId();
        $user = Auth::user();

        // Cari/Buat Cart (Sekarang pasti punya User ID)
        $cart = Cart::firstOrCreate(
            ['session_id' => $sessionId],
            ['user_id' => $user->id] 
        );

        // ... (sisanya sama persis) ...
        
        $item = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => 1,
                'price_at_add' => $product->final_price 
            ]);
        }

        $count = CartItem::where('cart_id', $cart->id)->sum('quantity');
        session(['cart_count' => $count]);
        
        $this->dispatch('show-toast', message: 'Masuk keranjang!'); 
    }

    public function changeCategory($id)
    {
        $this->activeCategory = $id;
    }
}