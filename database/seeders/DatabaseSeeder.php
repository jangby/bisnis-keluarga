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
}
}