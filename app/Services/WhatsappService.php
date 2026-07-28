<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
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
        $driver = config('services.whatsapp.driver', 'meta');
        $phone = $this->normalizePhone($phone);

        if ($driver === 'twilio') {
            return $this->sendViaTwilio($phone, $message, $mediaPath);
        }

        return $this->sendViaMeta($phone, $message, $mediaPath);
    }

    protected function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (strlen($phone) === 11 && str_starts_with($phone, '0')) {
            return '234' . substr($phone, 1);
        }

        return $phone;
    }

    protected function sendViaMeta(string $phone, string $message, ?string $mediaPath = null): bool
    {
        $phoneNumberId = config('services.whatsapp.phone_number_id');
        $accessToken = config('services.whatsapp.access_token');

        if (empty($phoneNumberId) || empty($accessToken)) {
            Log::info("[WhatsappService - SIMULATION] Sent WhatsApp message to $phone. Media: " . ($mediaPath ?? 'None') . ". Body: $message");
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

            Log::info("[WhatsappService] Successfully sent WhatsApp message via Meta API to $phone.");
            return true;
        } catch (\Exception $e) {
            Log::error("[WhatsappService] Failed to send WhatsApp to $phone via Meta API: " . $e->getMessage());
            return false;
        }
    }

    protected function sendViaTwilio(string $phone, string $message, ?string $mediaPath = null): bool
    {
        $accountSid = config('services.twilio.account_sid');
        $authToken = config('services.twilio.auth_token');
        $fromNumber = config('services.twilio.whatsapp_from');

        if (empty($accountSid) || empty($authToken) || empty($fromNumber)) {
            Log::info("[WhatsappService - SIMULATION] Twilio WhatsApp send to $phone. Media: " . ($mediaPath ?? 'None') . ". Body: $message");
            return rand(1, 100) <= 95;
        }

        $payload = [
            'To' => 'whatsapp:' . $phone,
            'From' => $fromNumber,
            'Body' => $message,
        ];

        if ($mediaPath) {
            $payload['MediaUrl'] = asset('storage/' . $mediaPath);
        }

        try {
            $response = Http::withBasicAuth($accountSid, $authToken)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", $payload);

            if ($response->successful()) {
                Log::info("[WhatsappService] Successfully sent WhatsApp message via Twilio to $phone.");
                return true;
            }

            Log::error("[WhatsappService] Twilio WhatsApp send failed for $phone: {$response->status()} {$response->body()}");
            return false;
        } catch (\Exception $e) {
            Log::error("[WhatsappService] Failed to send WhatsApp to $phone via Twilio: " . $e->getMessage());
            return false;
        }
    }
}

