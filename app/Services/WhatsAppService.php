<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $baseUrl;
    protected $apiKey; // [BARU] Variabel untuk API Key
    protected $session;

    public function __construct()
    {
        // Ambil konfigurasi dari .env
        $this->baseUrl = env('WAHA_API_URL', 'http://72.61.208.130');
        $this->apiKey = env('WAHA_API_KEY'); // [BARU] Ambil key
        $this->session = 'default';
    }

    public function sendText($phone, $message)
    {
        $chatId = $this->formatNumber($phone);

        try {
            // [PERBAIKAN] Tambahkan withHeaders sebelum post()
            $response = Http::withHeaders([
                'X-Api-Key' => $this->apiKey, // Kirim API Key di Header
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/api/sendText", [
                'session' => $this->session,
                'chatId' => $chatId,
                'text' => $message,
            ]);

            if ($response->successful()) {
                return true;
            } else {
                Log::error('WAHA Error: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('WAHA Connection Error: ' . $e->getMessage());
            return false;
        }
    }

    private function formatNumber($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);

        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }

        if (!str_ends_with($number, '@c.us')) {
            $number .= '@c.us';
        }

        return $number;
    }
}