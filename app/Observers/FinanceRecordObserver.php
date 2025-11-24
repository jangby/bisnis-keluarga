<?php

namespace App\Observers;

use App\Models\FinanceRecord;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FinanceRecordObserver
{
    /**
     * Handle the FinanceRecord "created" event.
     * Dijalankan OTOMATIS sesaat setelah transaksi tersimpan di database.
     */
    public function created(FinanceRecord $record): void
    {
        // 1. Cek Syarat: Nominal harus di atas 5 Juta
        if ($record->amount >= 5000000) {
            
            // 2. Siapkan Pesan WhatsApp
            // Format pesan rapi dengan emoji
            $message = "*⚠️ PEMBERITAHUAN TRANSAKSI BESAR ⚠️*\n\n";
            $message .= "Halo Keluarga, sistem mendeteksi aktivitas keuangan dalam jumlah besar:\n\n";
            $message .= "👤 *Pelaku:* " . $record->user->name . "\n";
            $message .= "💰 *Nominal:* Rp " . number_format($record->amount, 0, ',', '.') . "\n";
            $message .= "📂 *Tipe:* " . ($record->type == 'income' ? 'Pemasukan 🟢' : 'Pengeluaran 🔴') . "\n";
            $message .= "🏷️ *Kategori:* " . $record->category . "\n";
            $message .= "🏭 *Divisi:* " . $record->product_line->name . "\n";
            $message .= "📝 *Catatan:* " . ($record->notes ?? '-') . "\n\n";
            $message .= "_Pesan ini dikirim otomatis oleh Sistem Bisnis Keluarga._";

            // 3. Kirim ke Grup WA Keluarga via WAHA
            // Ganti URL dan Session ID sesuai settingan WAHA Anda
            $wahaUrl = 'http://72.61.208.130:3000/api/sendText'; 
            
            // ID Grup WA Keluarga (Biasanya berakhiran @g.us)
            // Cara cari ID Grup: Lihat dokumentasi WAHA bagian 'GET /api/groups'
            $chatId = '120363404018478772@g.us'; 

            try {
                // API Key WAHA Anda (Sesuaikan dengan settingan di docker-compose/dashboard WAHA)
                // Jika Anda tidak mengubahnya, default biasanya kosong atau '321' tergantung settingan awal.
                // Tapi karena error 401, berarti Anda pasti punya key yang aktif.
                $apiKey = '0f0eb5d196b6459781f7d854aac5050e'; // <--- GANTI INI DENGAN API KEY WAHA ANDA

                // Kirim dengan Header Authentication
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Api-Key' => $apiKey, 
                ])->post($wahaUrl, [
                    'chatId' => $chatId,
                    'text' => $message,
                    'session' => 'default'
                ]);
                
                // Cek Status
                if ($response->successful()) {
                    Log::info("✅ WAHA Berhasil Kirim. ID Transaksi: " . $record->id);
                } else {
                    Log::error("❌ WAHA Menolak. Status: " . $response->status());
                    Log::error("Alasan: " . $response->body());
                }

            } catch (\Exception $e) {
                Log::error("Gagal Koneksi ke Server WAHA: " . $e->getMessage());
            }
        }
    }
}