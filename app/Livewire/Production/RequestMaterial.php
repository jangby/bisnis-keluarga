<?php

namespace App\Livewire\Production;

use App\Models\Product;
use App\Models\ProductionRequest; 
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class RequestMaterial extends Component
{
    public $materials;
    public $cart = [];
    public $notes;

    public function mount()
    {
        // [PERBAIKAN DISINI] 
        // Tambahkan 'inventory' agar akun gudang bisa akses
        if (!in_array(Auth::user()->role, ['owner', 'production', 'inventory'])) {
            return abort(403, 'Akses Ditolak: Anda tidak memiliki izin mengajukan bahan.');
        }

        // Ambil data bahan baku (type = material)
        $this->materials = Product::where('type', 'material')
            ->orderBy('name')
            ->get();
    }

    public function addToCart($id)
    {
        if (isset($this->cart[$id])) {
            $this->cart[$id]['qty']++;
        } else {
            $material = $this->materials->find($id);
            $this->cart[$id] = [
                'id' => $material->id,
                'name' => $material->name,
                'unit' => $material->unit,
                'current_stock' => $material->current_stock,
                'qty' => 1
            ];
        }
    }

    public function removeFromCart($id)
    {
        unset($this->cart[$id]);
    }

    public function updateQty($id, $qty)
    {
        if ($qty > 0) {
            $this->cart[$id]['qty'] = $qty;
        } else {
            unset($this->cart[$id]);
        }
    }

    public function submit()
    {
        $this->validate([
            'cart' => 'required|array|min:1',
            'notes' => 'nullable|string|max:500'
        ]);

        // Simpan Request ke Database
        $request = ProductionRequest::create([
            'user_id' => Auth::id(),
            'status' => 'pending', // Menunggu persetujuan Owner/Finance (opsional) atau langsung diproses
            'notes' => $this->notes,
            'items' => json_encode($this->cart), // Simpan detail barang sebagai JSON
            'requested_at' => now(),
        ]);

        // Opsional: Kirim Notifikasi ke Owner (Log Activity)
        \App\Models\ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Request Bahan',
            'subject_type' => 'ProductionRequest',
            'subject_id' => $request->id,
            'description' => 'Mengajukan restok untuk ' . count($this->cart) . ' item bahan baku.',
            'properties' => ['color' => 'bg-orange-100 text-orange-700', 'icon' => '📝'],
            'ip_address' => request()->ip()
        ]);

        $this->reset(['cart', 'notes']);
        
        // Gunakan dispatch show-toast agar notifikasi muncul
        $this->dispatch('show-toast', type: 'success', message: 'Request bahan berhasil diajukan!');
    }

    public function render()
    {
        return view('livewire.production.request-material')->layout('layouts.app');
    }
}