<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http; // <--- Wajib ada
use Illuminate\Support\Facades\Log;  // <--- Wajib ada

class CheckLowStockReminder extends Command
{
    protected $signature = 'stock:check-reminder';
    protected $description = 'Cek stok rendah yang sudah 3 hari tidak berubah dan kirim WA';

    public function handle()
    {
        // 1. Ambil SEMUA produk yang stoknya di bawah batas minimum
        $products = Product::whereColumn('current_stock', '<=', 'min_stock')->get();

        $count = 0;

        foreach ($products as $product) {
            $shouldAlert = false;

            // SKENARIO A: Belum pernah dinotif sama sekali (misal: data baru)
            if ($product->last_alert_date === null) {
                $shouldAlert = true;
                $this->info("Ditemukan stok kritis baru: {$product->name}");
            } 
            // SKENARIO B: Sudah pernah dinotif, cek apakah sudah 3 hari berlalu?
            else {
                $lastAlert = Carbon::parse($product->last_alert_date);
                // Cek selisih hari
                if ($lastAlert->diffInDays(now()) >= 3) { 
                    $shouldAlert = true;
                    $this->info("Waktunya reminder ulang untuk: {$product->name}");
                }
            }

            // EKSEKUSI KIRIM
            if ($shouldAlert) {
                // Panggil fungsi kirim WA
                $this->sendWhatsAppReminder($product);

                // Update tanggal notif jadi hari ini
                $product->last_alert_date = now();
                $product->save();
                $count++;
            }
        }

        if ($count == 0) {
            $this->info("Tidak ada produk yang perlu diingatkan saat ini.");
        } else {
            $this->info("Selesai. $count pesan pengingat telah diproses.");
        }
    }

    /**
     * Fungsi Kirim ke WAHA
     */
    private function sendWhatsAppReminder($product)
    {
        // 1. Siapkan Pesan
        $message = "🔔 *REMINDER STOK KRITIS (SUDAH 3 HARI)*\n\n";
        $message .= "Halo Tim, ini adalah pengingat berkala.\n";
        $message .= "Produk: *{$product->name}*\n";
        $message .= "Posisi Stok: *{$product->current_stock} {$product->unit}*\n";
        $message .= "Status: Stok belum diisi ulang sejak 3 hari lalu.\n\n";
        $message .= "Mohon segera tindak lanjuti.";

        // 2. Konfigurasi WAHA (Sesuai data Anda)
        $wahaUrl = 'http://localhost:3000/api/sendText';
        $chatId = '120363404018478772@g.us'; // ID Grup Keluarga
        $apiKey = '0f0eb5d196b6459781f7d854aac5050e'; // API Key WAHA

        try {
            // 3. Tembak API WAHA
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Api-Key' => $apiKey,
            ])->post($wahaUrl, [
                'chatId' => $chatId,
                'text' => $message,
                'session' => 'default'
            ]);

            // 4. Log Hasilnya
            if ($response->successful()) {
                Log::info("✅ REMINDER TERKIRIM: {$product->name}");
                $this->info("   -> Sukses kirim ke WA.");
            } else {
                Log::error("❌ GAGAL KIRIM REMINDER: {$response->status()} - {$response->body()}");
                $this->error("   -> Gagal kirim ke WA (Cek Log).");
            }

        } catch (\Exception $e) {
            Log::error("⛔ Gagal Koneksi WAHA (Reminder): " . $e->getMessage());
            $this->error("   -> Gagal koneksi ke WAHA.");
        }
    }
}