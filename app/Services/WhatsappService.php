<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;

class WhatsappService
{
    /**
     * Send a WhatsApp message to a phone number.
     * If credentials are not set, it simulates a send and logs it.
     */
    public function send(string $phone, string $message, ?string $mediaPath = null): bool
    {
        $phoneNumberId = config('services.whatsapp.phone_number_id');
        $accessToken = config('services.whatsapp.access_token');

        // Normalize phone number (strip spaces, ensure country code)
        $phone = preg_replace('/\D/', '', $phone);
        // Default to Nigerian country code if not present
        if (strlen($phone) === 11 && str_starts_with($phone, '0')) {
            $phone = '234' . substr($phone, 1);
        }

        if (empty($phoneNumberId) || empty($accessToken)) {
            // Simulated sending
            Log::info("[WhatsappService - SIMULATION] Sent WhatsApp message to $phone. Media: " . ($mediaPath ?? 'None') . ". Body: $message");
            // Simulate 95% delivery success rate
            return rand(1, 100) <= 95;
        }

        try {
            $whatsapp = new WhatsAppCloudApi([
                'from_phone_number_id' => $phoneNumberId,
                'access_token' => $accessToken,
            ]);

            if ($mediaPath) {
                $mediaUrl = asset('storage/' . $mediaPath);
                $linkId = new LinkID($mediaUrl);
                $whatsapp->sendImage($phone, $linkId, $message);
            } else {
                $whatsapp->sendTextMessage($phone, $message);
            }

            Log::info("[WhatsappService] Successfully sent WhatsApp message via SDK to $phone.");
            return true;
        } catch (\Exception $e) {
            Log::error("[WhatsappService] Failed to send WhatsApp to $phone: " . $e->getMessage());
            return false;
        }
    }
}

