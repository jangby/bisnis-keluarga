<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\ProductLine;
use App\Models\ProductRecipe;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    // 1. Properti Identitas Produk
    public $product_id; // Jika terisi = Mode Edit
    public $name;
    public $code;
    public $product_line_id;
    public $type = 'goods'; // Default: Barang Jadi (goods), Opsi lain: material (Bahan Baku)
    
    // 2. Properti Harga & Stok
    public $base_price; // HPP (Harga Modal)
    public $sell_price; // Harga Jual
    public $unit = 'Pcs';
    public $current_stock = 0;

    // 3. Properti Resep (Array Dinamis)
    public $recipes = [];

    // 4. Lifecycle Mount (Saat halaman dibuka)
    public function mount($id = null)
    {
        // Cek keamanan: Hanya Owner & Produksi yang boleh akses
        if (!in_array(Auth::user()->role, ['owner', 'production'])) {
            return abort(403, 'Akses Ditolak');
        }

        // Tangkap parameter ?type=material dari URL (jika ada)
        if (request()->has('type')) {
            $this->type = request('type');
        }

        // Jika Mode EDIT (ada ID)
        if ($id) {
            $product = Product::with('recipes')->findOrFail($id);
            
            $this->product_id = $product->id;
            $this->name = $product->name;
            $this->code = $product->code;
            $this->type = $product->type;
            $this->product_line_id = $product->product_line_id;
            $this->base_price = $product->base_price;
            $this->sell_price = $product->sell_price;
            $this->unit = $product->unit;
            $this->current_stock = $product->current_stock;

            // Load Resep Lama ke Form
            foreach ($product->recipes as $r) {
                $this->recipes[] = [
                    'material_id' => $r->material_id,
                    'quantity_needed' => $r->quantity_needed
                ];
            }
        } else {
            // Jika Mode BARU
            $this->product_line_id = ProductLine::first()->id ?? null;
            // Tambah 1 baris resep kosong untuk pancingan
            $this->addRecipeRow();
        }
    }

    // Tambah baris input resep
    public function addRecipeRow()
    {
        $this->recipes[] = ['material_id' => '', 'quantity_needed' => ''];
    }

    // Hapus baris input resep
    public function removeRecipeRow($index)
    {
        unset($this->recipes[$index]);
        $this->recipes = array_values($this->recipes); // Reset urutan array
    }

    public function save()
    {
        // Validasi Form
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:products,code,' . $this->product_id,
            'product_line_id' => 'required',
            'base_price' => 'required|numeric|min:0',
            'sell_price' => 'nullable|numeric|min:0', // Bisa 0 kalau bahan baku
            'unit' => 'required',
        ]);

        DB::transaction(function () {
            // 1. Simpan Data Produk Utama
            $product = Product::updateOrCreate(
                ['id' => $this->product_id],
                [
                    'name' => $this->name,
                    'code' => $this->code,
                    'type' => $this->type,
                    'product_line_id' => $this->product_line_id,
                    'base_price' => $this->base_price,
                    'sell_price' => $this->sell_price ?? 0,
                    'unit' => $this->unit,
                    // Stok hanya diset saat create baru, edit stok harus lewat opname/transaksi
                    'current_stock' => $this->product_id ? $this->current_stock : ($this->current_stock ?? 0),
                ]
            );

            // 2. Simpan Resep (Hanya jika tipe = Barang Jadi)
            if ($this->type == 'goods') {
                // Hapus semua resep lama dulu (Reset)
                ProductRecipe::where('product_id', $product->id)->delete();

                // Masukkan resep baru
                foreach ($this->recipes as $row) {
                    // Hanya simpan yang datanya lengkap
                    if (!empty($row['material_id']) && !empty($row['quantity_needed'])) {
                        ProductRecipe::create([
                            'product_id' => $product->id,
                            'material_id' => $row['material_id'],
                            'quantity_needed' => $row['quantity_needed']
                        ]);
                    }
                }
            }
        });

        session()->flash('message', 'Produk berhasil disimpan!');
        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.product.form', [
            'productLines' => ProductLine::all(),
            // Ambil daftar produk yang tipenya 'material' untuk dropdown resep
            'materials' => Product::where('type', 'material')->orderBy('name')->get()
        ])->layout('layouts.app');
    }
}