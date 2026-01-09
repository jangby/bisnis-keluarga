<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\ProductLine;
use App\Models\ProductRecipe;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Form extends Component
{
    use WithFileUploads;

    // 1. Identitas
    public $product_id;
    public $name;
    public $code;
    public $product_line_id;
    public $type = 'goods'; 
    
    // Variabel Gambar
    public $image;          
    public $oldImage;       
    
    // 2. Harga & Stok
    public $base_price = 0;
    public $sell_price = 0;
    public $unit = 'Pcs';
    public $current_stock = 0;
    
    // Stok Minimum
    public $min_stock = 5; 

    // 3. Resep
    public $recipes = [];

    public function mount($id = null)
    {
        // [PERBAIKAN DISINI] Tambahkan 'inventory' ke dalam daftar role yang diizinkan
        if (!in_array(Auth::user()->role, ['owner', 'production', 'inventory'])) {
            return abort(403, 'Akses Ditolak: Anda tidak memiliki izin mengelola produk.');
        }

        if (request()->has('type')) {
            $this->type = request('type');
        }

        $this->product_line_id = ProductLine::first()->id ?? null;

        if ($id) {
            // --- MODE EDIT ---
            $product = Product::with('recipes')->findOrFail($id);
            
            $this->product_id = $product->id;
            $this->name = $product->name;
            $this->code = $product->code;
            $this->type = $product->type;
            $this->oldImage = $product->image_path; 
            $this->product_line_id = $product->product_line_id;
            $this->base_price = $product->base_price;
            $this->sell_price = $product->sell_price;
            $this->unit = $product->unit;
            $this->current_stock = $product->current_stock;
            $this->min_stock = $product->min_stock; 

            foreach ($product->recipes as $r) {
                $this->recipes[] = [
                    'material_id' => $r->material_id,
                    'quantity_needed' => $r->quantity_needed + 0
                ];
            }
        } else {
            // --- MODE BARU ---
            $this->generateAutoCode();
            $this->addRecipeRow();
        }
    }

    public function generateAutoCode()
    {
        $prefix = ($this->type == 'goods') ? 'PRD' : 'MAT';
        $year = date('y');
        $random = strtoupper(Str::random(4));
        $this->code = "{$prefix}-{$year}-{$random}";
    }

    public function updatedType()
    {
        $this->generateAutoCode();
        if($this->type == 'material') {
            $this->recipes = [];
            $this->base_price = 0;
        }
    }

    public function updatedRecipes()
    {
        if ($this->type == 'goods') {
            $totalCost = 0;
            foreach ($this->recipes as $row) {
                if (!empty($row['material_id']) && !empty($row['quantity_needed'])) {
                    $material = Product::find($row['material_id']);
                    if ($material) {
                        $totalCost += ($material->base_price * $row['quantity_needed']);
                    }
                }
            }
            $this->base_price = $totalCost;
        }
    }

    public function addRecipeRow()
    {
        $this->recipes[] = ['material_id' => '', 'quantity_needed' => ''];
    }

    public function removeRecipeRow($index)
    {
        unset($this->recipes[$index]);
        $this->recipes = array_values($this->recipes);
        $this->updatedRecipes();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:products,code,' . $this->product_id,
            'product_line_id' => 'required',
            'base_price' => 'required|numeric|min:0',
            'sell_price' => 'nullable|numeric|min:0',
            'unit' => 'required',
            'min_stock' => 'required|numeric|min:0', 
            'image' => 'nullable|image|max:2048', 
        ]);

        DB::transaction(function () {
            $imagePath = $this->oldImage; 
            
            if ($this->image) {
                if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                    Storage::disk('public')->delete($this->oldImage);
                }
                $imagePath = $this->image->store('products', 'public');
            }

            $product = Product::updateOrCreate(
                ['id' => $this->product_id],
                [
                    'name' => $this->name,
                    'code' => $this->code,
                    'type' => $this->type,
                    'image_path' => $imagePath,
                    'product_line_id' => $this->product_line_id,
                    'base_price' => $this->base_price,
                    'sell_price' => $this->sell_price ?? 0,
                    'unit' => $this->unit,
                    'current_stock' => $this->product_id ? $this->current_stock : ($this->current_stock ?? 0),
                    'min_stock' => $this->min_stock,
                ]
            );

            if ($this->type == 'goods') {
                ProductRecipe::where('product_id', $product->id)->delete();
                foreach ($this->recipes as $row) {
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

        session()->flash('message', 'Data berhasil disimpan!');
        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.product.form', [
            'productLines' => ProductLine::all(),
            'materials' => Product::where('type', 'material')->orderBy('name')->get()
        ])->layout('layouts.app');
    }
}