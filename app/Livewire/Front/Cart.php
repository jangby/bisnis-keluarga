<?php

namespace App\Livewire\Front;

use Livewire\Component;
use App\Models\Cart as CartModel;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Cart extends Component
{
    // Form Data Pemesan
    public $name;
    public $phone;
    public $address;

    public function mount()
    {
        // Jika sudah login, isi otomatis datanya
        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->phone = $user->phone ?? ''; // Asumsi ada kolom phone di user, jika belum biarkan kosong
            // $this->address = $user->address; // Jika nanti ada fitur alamat tersimpan
        }
    }

    public function render()
    {
        $sessionId = Session::getId();
        $user = Auth::user();

        $cart = CartModel::with(['items.product'])
            ->where(function($q) use ($sessionId, $user) {
                $q->where('session_id', $sessionId);
                if ($user) $q->orWhere('user_id', $user->id);
            })->first();

        $total = 0;
        $items = [];
        
        if ($cart) {
            $items = $cart->items;
            foreach($items as $item) {
                $total += $item->subtotal;
            }
        }

        return view('livewire.front.cart', [
            'cart' => $cart,
            'items' => $items,
            'total' => $total
        ])->layout('layouts.front');
    }

    // --- FITUR EDIT KERANJANG ---

    public function increment($itemId)
    {
        $item = CartItem::find($itemId);
        if ($item) {
            $item->increment('quantity');
            $this->updateCartBadge($item->cart_id);
        }
    }

    public function decrement($itemId)
    {
        $item = CartItem::find($itemId);
        if ($item && $item->quantity > 1) {
            $item->decrement('quantity');
        } elseif ($item) {
            $item->delete(); // Hapus jika sisa 0
        }
        $this->updateCartBadge($item->cart_id ?? null);
    }

    public function removeItem($itemId)
    {
        $item = CartItem::find($itemId);
        if ($item) {
            $cartId = $item->cart_id;
            $item->delete();
            $this->updateCartBadge($cartId);
        }
    }

    private function updateCartBadge($cartId)
    {
        if(!$cartId) return;
        $count = CartItem::where('cart_id', $cartId)->sum('quantity');
        session(['cart_count' => $count]);
    }

    // --- LOGIC WHATSAPP ---

    public function getWhatsappUrlProperty()
    {
        // GANTI NOMOR INI DENGAN NOMOR ADMIN ANDA (Format 628xxx)
        $adminPhone = '6281234567890'; 
        
        $cart = $this->render()->getData()['cart']; // Ambil data cart yg sudah di-query
        if (!$cart || $cart->items->isEmpty()) return '#';

        // Susun Pesan
        $message = "Halo Admin, saya mau pesan:\n\n";
        $total = 0;

        foreach ($cart->items as $item) {
            $subtotal = $item->quantity * $item->price_at_add;
            $message .= "- {$item->product->name} ({$item->quantity}x) : Rp " . number_format($subtotal, 0, ',', '.') . "\n";
            $total += $subtotal;
        }

        $message .= "\n*Total Biaya: Rp " . number_format($total, 0, ',', '.') . "*";
        $message .= "\n\nMohon diproses ya, terima kasih!";

        // Encode URL agar bisa dikirim
        return "https://wa.me/{$adminPhone}?text=" . urlencode($message);
    }

    public function checkout()
    {
        $this->validate([
            'name' => 'required|min:3',
            'phone' => 'required|numeric|min_digits:10',
            'address' => 'required|min:10',
        ], [
            'name.required' => 'Nama wajib diisi ya kak.',
            'phone.required' => 'Nomor WA wajib diisi agar mudah dihubungi.',
            'address.required' => 'Alamat kirimnya jangan lupa ya.',
        ]);

        $cartData = $this->render()->getData();
        $cart = $cartData['cart'];
        $total = $cartData['total'];

        if (!$cart || $cart->items->isEmpty()) return;

        DB::transaction(function () use ($cart, $total) {
            // 1. Simpan Order ke Database
            $order = Order::create([
                'user_id' => Auth::id(),
                'guest_name' => $this->name,
                'guest_phone' => $this->phone,
                'delivery_address' => $this->address,
                'total_amount' => $total,
                'status' => 'pending'
            ]);

            // 2. Pindahkan Item Cart ke Order Item
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price_at_add,
                    'subtotal' => $item->subtotal
                ]);
            }

            // 3. Kosongkan Keranjang
            $cart->items()->delete();
            $cart->delete();
            session()->forget('cart_count');

            // 4. Redirect ke WhatsApp
            $this->redirectToWhatsapp($order);
        });
    }

    protected function redirectToWhatsapp($order)
    {
        $adminPhone = '6281313972866'; // Ganti No Admin

        // Format Pesan Lebih Profesional
        $msg  = "Halo Admin, saya mau konfirmasi pesanan baru via Web.\n\n";
        $msg .= "*No. Order: #{$order->id}*\n";
        $msg .= "Nama: {$order->guest_name}\n";
        $msg .= "Alamat: {$order->delivery_address}\n\n";
        $msg .= "Detail Pesanan:\n";
        
        foreach ($order->items as $item) {
            $msg .= "- {$item->product_name} ({$item->quantity}x)\n";
        }
        
        $msg .= "\n*Total: Rp " . number_format($order->total_amount, 0, ',', '.') . "*\n";
        $msg .= "Mohon diproses ya, terima kasih!";

        $url = "https://wa.me/{$adminPhone}?text=" . urlencode($msg);
        
        // Redirect Livewire
        $this->redirect($url, navigate: false);
    }
}