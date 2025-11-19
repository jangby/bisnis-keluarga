<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Contact;
use App\Models\Product;
use App\Models\ProductLine;
use App\Models\ProductRecipe;
use App\Models\InventoryLog;
use App\Models\FinanceRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. SETUP DIVISI & DOMPET
        // ==========================================
        
        $kecapLine = ProductLine::create([
            'name' => 'Divisi Kecap', 
            'description' => 'Produksi Kecap Manis & Asin'
        ]);
        
        $sistikLine = ProductLine::create([
            'name' => 'Divisi Sistik', 
            'description' => 'Produksi Sistik Aneka Rasa'
        ]);

        $umumLine = ProductLine::create([
            'name' => 'Operasional Umum', 
            'description' => 'Biaya Listrik, Air, Maintenance'
        ]);

        $walletKas = Wallet::create(['name' => 'Kas Tunai (Laci)', 'balance' => 5000000]); // Modal 5jt
        $walletBank = Wallet::create(['name' => 'Bank BRI Keluarga', 'balance' => 20000000]); // Tabungan 20jt
        Wallet::create(['name' => 'Gopay Operasional', 'balance' => 500000]);

        // ==========================================
        // 2. SETUP USER (KELUARGA)
        // ==========================================

        $ayah = User::create([
            'name' => 'Ayah (Owner)',
            'email' => 'ayah@keluarga.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        $ibu = User::create([
            'name' => 'Ibu (Keuangan)',
            'email' => 'ibu@keluarga.com',
            'password' => Hash::make('password'),
            'role' => 'finance',
        ]);

        $kakak = User::create([
            'name' => 'Kakak (Produksi)',
            'email' => 'kakak@keluarga.com',
            'password' => Hash::make('password'),
            'role' => 'production',
        ]);

        $adik = User::create([
            'name' => 'Adik (Marketing)',
            'email' => 'adik@keluarga.com',
            'password' => Hash::make('password'),
            'role' => 'marketing',
        ]);

        // ==========================================
        // 3. SETUP KONTAK
        // ==========================================

        Contact::create([
            'name' => 'Toko Tani Makmur (Supplier)',
            'type' => 'supplier',
            'phone' => '081234567890',
            'address' => 'Pasar Induk Blok A1'
        ]);

        Contact::create([
            'name' => 'Toko Plastik Jaya (Supplier)',
            'type' => 'supplier',
            'phone' => '08111222333',
            'address' => 'Jl. Cibaduyut'
        ]);

        $agen = Contact::create([
            'name' => 'Toko Barokah (Agen)',
            'type' => 'customer',
            'phone' => '089876543210',
            'address' => 'Jl. Raya Cibaduyut No. 12'
        ]);

        // ==========================================
        // 4. DATA BAHAN BAKU (MATERIAL)
        // ==========================================

        // Bahan untuk Kecap
        $kedelai = Product::create([
            'product_line_id' => $kecapLine->id,
            'code' => 'MAT-KCP-01',
            'name' => 'Kacang Kedelai Hitam',
            'type' => 'material', // <--- TIPE MATERIAL
            'base_price' => 15000, // Harga beli per kg
            'sell_price' => 0, // Tidak dijual
            'current_stock' => 100, // Ada 100 Kg
            'unit' => 'Kg'
        ]);

        $gulaMerah = Product::create([
            'product_line_id' => $kecapLine->id,
            'code' => 'MAT-KCP-02',
            'name' => 'Gula Merah Asli',
            'type' => 'material',
            'base_price' => 18000,
            'sell_price' => 0,
            'current_stock' => 50,
            'unit' => 'Kg'
        ]);

        // Bahan untuk Sistik
        $tepung = Product::create([
            'product_line_id' => $sistikLine->id,
            'code' => 'MAT-STK-01',
            'name' => 'Tepung Terigu Premium',
            'type' => 'material',
            'base_price' => 12000,
            'sell_price' => 0,
            'current_stock' => 50, // Ada 50 Kg
            'unit' => 'Kg'
        ]);

        $minyak = Product::create([
            'product_line_id' => $sistikLine->id,
            'code' => 'MAT-STK-02',
            'name' => 'Minyak Goreng',
            'type' => 'material',
            'base_price' => 14000,
            'sell_price' => 0,
            'current_stock' => 20, // Ada 20 Liter
            'unit' => 'Liter'
        ]);

        // ==========================================
        // 5. DATA BARANG JADI (GOODS)
        // ==========================================

        // Produk KECAP
        $kecapManis = Product::create([
            'product_line_id' => $kecapLine->id,
            'code' => 'PRD-KCP-01',
            'name' => 'Kecap Manis Cap Keluarga (600ml)',
            'type' => 'goods', // <--- TIPE BARANG JADI
            'base_price' => 12000, // HPP (Modal)
            'sell_price' => 18000, // Harga Jual
            'current_stock' => 20, // Stok awal barang jadi
            'unit' => 'Botol'
        ]);

        // Produk SISTIK
        $sistikKeju = Product::create([
            'product_line_id' => $sistikLine->id,
            'code' => 'PRD-STK-01',
            'name' => 'Sistik Rasa Keju (250gr)',
            'type' => 'goods',
            'base_price' => 8000,
            'sell_price' => 15000,
            'current_stock' => 0, // Belum diproduksi
            'unit' => 'Bungkus'
        ]);

        // ==========================================
        // 6. RESEP PRODUKSI (RECIPES)
        // ==========================================

        // Resep: 1 Botol Kecap butuh 0.5 Kg Kedelai + 0.2 Kg Gula
        ProductRecipe::create(['product_id' => $kecapManis->id, 'material_id' => $kedelai->id, 'quantity_needed' => 0.5]);
        ProductRecipe::create(['product_id' => $kecapManis->id, 'material_id' => $gulaMerah->id, 'quantity_needed' => 0.2]);

        // Resep: 1 Bungkus Sistik butuh 0.2 Kg Tepung + 0.1 Liter Minyak
        ProductRecipe::create(['product_id' => $sistikKeju->id, 'material_id' => $tepung->id, 'quantity_needed' => 0.2]);
        ProductRecipe::create(['product_id' => $sistikKeju->id, 'material_id' => $minyak->id, 'quantity_needed' => 0.1]);

        // ==========================================
        // 7. SIMULASI TRANSAKSI
        // ==========================================

        // Pemasukan Modal Awal
        FinanceRecord::create([
            'user_id' => $ayah->id,
            'type' => 'income',
            'amount' => 25000000,
            'wallet_id' => $walletBank->id,
            'product_line_id' => $umumLine->id,
            'category' => 'Suntikan Modal',
            'transaction_date' => Carbon::now()->subMonth(),
            'notes' => 'Modal Awal Tahun'
        ]);

        // Pengeluaran Beli Bahan Baku (Stok Awal)
        FinanceRecord::create([
            'user_id' => $ibu->id,
            'type' => 'expense',
            'amount' => 1500000,
            'wallet_id' => $walletKas->id,
            'product_line_id' => $kecapLine->id,
            'category' => 'Pembelian Bahan Baku',
            'transaction_date' => Carbon::now()->subDays(5),
            'notes' => 'Belanja Kedelai 100kg'
        ]);
    }
}