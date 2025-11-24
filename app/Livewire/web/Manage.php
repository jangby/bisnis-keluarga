<?php

namespace App\Livewire\Web;

use Livewire\Component;
use App\Models\Product;
use App\Models\WebCategory;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class Manage extends Component
{
    use WithPagination;

    public $activeTab = 'categories'; // 'categories' atau 'discounts'

    // Form Kategori
    public $category_id, $name, $icon;
    public $isModalOpen = false;
    public $isProductModalOpen = false;

    // Form Pilih Produk untuk Kategori
    public $selectedCategory;
    public $selectedProducts = []; // ID produk yang dipilih

    // Form Edit Diskon Cepat
    public $editingProductId = null;
    public $editPrice, $editDiscount;

    public function render()
    {
        $data = [];

        if ($this->activeTab == 'categories') {
            $data['categories'] = WebCategory::with('products')->orderBy('sort_order')->get();
        } else {
            // Hanya ambil Barang Jadi (Goods)
            $data['products'] = Product::where('type', 'goods')
                ->orderBy('name')
                ->paginate(10);
        }

        // List semua produk untuk modal selector (kategori)
        $allProducts = Product::where('type', 'goods')->get();

        return view('livewire.web.manage', [
            'data' => $data,
            'allProducts' => $allProducts
        ])->layout('layouts.app');
    }

    // --- LOGIC KATEGORI ---
    public function saveCategory()
    {
        $this->validate(['name' => 'required|string|max:255']);

        WebCategory::updateOrCreate(
            ['id' => $this->category_id],
            [
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'icon' => $this->icon, // Bisa isi Emoji 🌶️ atau nama file
            ]
        );

        $this->reset(['name', 'icon', 'category_id', 'isModalOpen']);
        session()->flash('message', 'Kategori berhasil disimpan.');
    }

    public function editCategory($id)
    {
        $cat = WebCategory::find($id);
        $this->category_id = $cat->id;
        $this->name = $cat->name;
        $this->icon = $cat->icon;
        $this->isModalOpen = true;
    }

    public function deleteCategory($id)
    {
        WebCategory::find($id)->delete();
    }

    public function openProductModal($categoryId)
    {
        $this->selectedCategory = WebCategory::with('products')->find($categoryId);
        // Isi checkbox dengan produk yang sudah ada di kategori ini
        $this->selectedProducts = $this->selectedCategory->products->pluck('id')->toArray();
        $this->isProductModalOpen = true;
    }

    public function saveCategoryProducts()
    {
        if ($this->selectedCategory) {
            $this->selectedCategory->products()->sync($this->selectedProducts);
            session()->flash('message', 'Produk dalam kategori berhasil diupdate!');
        }
        $this->isProductModalOpen = false;
    }

    // --- LOGIC DISKON ---
    public function editDiscount($productId)
    {
        $product = Product::find($productId);
        $this->editingProductId = $product->id;
        $this->editPrice = $product->sell_price; // Harga Asli
        $this->editDiscount = $product->discount_price; // Harga Diskon
    }

    public function saveDiscount()
    {
        $product = Product::find($this->editingProductId);
        
        // Validasi simpel: Harga diskon gaboleh lebih mahal dari harga asli
        if ($this->editDiscount >= $this->editPrice && $this->editDiscount > 0) {
            $this->addError('editDiscount', 'Harga diskon harus lebih murah dari harga asli!');
            return;
        }

        $product->update([
            'sell_price' => $this->editPrice,
            'discount_price' => $this->editDiscount == 0 ? null : $this->editDiscount
        ]);

        $this->editingProductId = null;
        session()->flash('message', 'Harga & Diskon berhasil diupdate.');
    }

    public function cancelEdit()
    {
        $this->editingProductId = null;
    }
}