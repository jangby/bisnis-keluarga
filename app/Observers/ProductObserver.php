<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     * Dijalankan OTOMATIS saat data produk (stok) berubah.
     */
    public function updated(Product $product): void
    {
        // Hanya jalankan jika ada perubahan pada kolom 'current_stock'
        if ($product->isDirty('current_stock')) {

            // KONDISI 1: Stok Sudah Aman (Diatas Minimum)
            // Reset tanggal notifikasi agar nanti jika turun lagi, dianggap alert baru.
            if ($product->current_stock > $product->min_stock) {
                if ($product->last_alert_date !== null) {
                    $product->last_alert_date = null;
                    $product->saveQuietly(); // Simpan tanpa memicu event loop
                }
            }

            // KONDISI 2: Stok KRITIS (Menyentuh/Dibawah Minimum)
            // DAN belum pernah dinotif sebelumnya (last_alert_date kosong)
            elseif ($product->current_stock <= $product->min_stock && is_null($product->last_alert_date)) {
                
                // 1. Kirim Pesan WA
                $this->sendWhatsAppMessage($product);

                // 2. Catat tanggal notifikasi hari ini
                $product->last_alert_date = now();
                $product->saveQuietly();
            }
        }
    }

    /**
     * Fungsi Khusus Mengirim Pesan via WAHA
     */
    private function sendWhatsAppMessage(Product $product): void
    {
        // 1. Siapkan Pesan Rapi
        $message = "*⚠️ PERINGATAN STOK MENIPIS ⚠️*\n\n";
        $message .= "Halo Tim Gudang & Produksi, stok barang berikut sudah mencapai batas minimum:\n\n";
        $message .= "📦 *Produk:* " . $product->name . "\n";
        $message .= "🔢 *Kode:* " . $product->code . "\n";
        $message .= "📉 *Sisa Stok:* " . $product->current_stock . " " . $product->unit . "\n";
        $message .= "🚨 *Batas Min:* " . $product->min_stock . " " . $product->unit . "\n\n";
        $message .= "Mohon segera lakukan pembelian bahan baku atau produksi ulang.\n";
        $message .= "_Pesan otomatis Sistem Bisnis Keluarga_";

        // 2. Konfigurasi WAHA (Sesuai FinanceRecordObserver)
        $wahaUrl = 'http://localhost:3000/api/sendText';
        $chatId = '120363404018478772@g.us'; // ID Grup Keluarga
        $apiKey = '0f0eb5d196b6459781f7d854aac5050e'; // API Key WAHA Anda

        try {
            // 3. Kirim Request ke WAHA
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Api-Key' => $apiKey,
            ])->post($wahaUrl, [
                'chatId' => $chatId,
                'text' => $message,
                'session' => 'default'
            ]);

            // 4. Cek & Log Status
            if ($response->successful()) {
                Log::info("✅ WAHA Alert Stok Terkirim: " . $product->name);
            } else {
                Log::error("❌ WAHA Gagal Kirim Stok. Status: " . $response->status());
                Log::error("Response: " . $response->body());
            }

        } catch (\Exception $e) {
            Log::error("⛔ Gagal Koneksi ke Server WAHA (Stok): " . $e->getMessage());
        }
    }
}