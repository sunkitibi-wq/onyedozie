<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    /**
     * Send a WhatsApp message to a phone number.
     * If credentials are not set, it simulates a send and logs it.
     */
    public function send(string $phone, string $message, ?string $mediaPath = null): bool
    {
        $apiUrl = env('WHATSAPP_API_URL');
        $apiToken = env('WHATSAPP_API_TOKEN');

        // Normalize phone number (strip spaces, ensure country code)
        $phone = preg_replace('/\D/', '', $phone);
        // Default to Nigerian country code if not present
        if (strlen($phone) === 11 && str_starts_with($phone, '0')) {
            $phone = '234' . substr($phone, 1);
        }

        if (empty($apiUrl) || empty($apiToken)) {
            // Simulated sending
            Log::info("[WhatsappService - SIMULATION] Sent WhatsApp message to $phone. Media: " . ($mediaPath ?? 'None') . ". Body: $message");
            // Simulate 95% delivery success rate
            return rand(1, 100) <= 95;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer $apiToken",
                'Accept' => 'application/json',
            ])->post($apiUrl, [
                'phone' => $phone,
                'message' => $message,
                'media_url' => $mediaPath ? asset('storage/' . $mediaPath) : null,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("[WhatsappService] Failed to send WhatsApp to $phone: " . $e->getMessage());
            return false;
        }
    }
}
