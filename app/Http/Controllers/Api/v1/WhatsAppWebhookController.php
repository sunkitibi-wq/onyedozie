<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    /**
     * Handle Meta or Twilio WhatsApp Webhook verification checks (GET).
     */
    public function verify(Request $request): Response
    {
        if (config('services.whatsapp.driver') === 'twilio') {
            return response('Twilio WhatsApp webhook endpoint is active.', 200);
        }

        // Meta parameters can have dots, which PHP converts to underscores in query keys.
        $mode = $request->input('hub_mode') ?? $request->input('hub.mode');
        $token = $request->input('hub_verify_token') ?? $request->input('hub.verify_token');
        $challenge = $request->input('hub_challenge') ?? $request->input('hub.challenge');

        $configuredToken = config('services.whatsapp.verify_token');

        if ($mode === 'subscribe' && $token === $configuredToken) {
            Log::info('[WhatsApp Webhook] Verification successful.', [
                'mode' => $mode,
                'token' => $token,
                'challenge' => $challenge,
            ]);

            return response($challenge, 200)
                ->header('Content-Type', 'text/plain');
        }

        Log::warning('[WhatsApp Webhook] Verification token mismatch or invalid mode.', [
            'mode' => $mode,
            'token' => $token,
            'challenge' => $challenge,
        ]);

        return response('Forbidden', 403);
    }

    /**
     * Handle incoming WhatsApp Webhook event notifications (POST).
     */
    public function handle(Request $request): JsonResponse
    {
        $driver = config('services.whatsapp.driver', 'meta');

        if ($driver === 'twilio') {
            return $this->handleTwilioWebhook($request);
        }

        return $this->handleMetaWebhook($request);
    }

    protected function handleMetaWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = trim((string) $request->headers->get('X-Hub-Signature-256', ''));
        $appSecret = config('services.whatsapp.app_secret');

        if (empty($appSecret) || empty($signature)) {
            Log::warning('[WhatsApp Webhook] Missing webhook signature or app secret.', [
                'has_signature' => ! empty($signature),
                'has_app_secret' => ! empty($appSecret),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden',
            ], 403);
        }

        $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $appSecret);

        if (! hash_equals($expectedSignature, $signature)) {
            Log::warning('[WhatsApp Webhook] Invalid signature.', [
                'received_signature' => $signature,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden',
            ], 403);
        }

        Log::info('[WhatsApp Webhook] Received Meta webhook event payload.', [
            'payload' => $request->all(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Event received',
        ], 200);
    }

    protected function handleTwilioWebhook(Request $request): JsonResponse
    {
        $signature = trim((string) $request->headers->get('X-Twilio-Signature', ''));
        $authToken = config('services.twilio.auth_token');

        if (empty($authToken) || empty($signature) || ! $this->verifyTwilioSignature($request, $signature, $authToken)) {
            Log::warning('[WhatsApp Webhook] Twilio signature verification failed.', [
                'has_signature' => ! empty($signature),
                'has_auth_token' => ! empty($authToken),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden',
            ], 403);
        }

        $payload = $request->all();
        $incoming = [
            'from' => $payload['From'] ?? null,
            'to' => $payload['To'] ?? null,
            'body' => $payload['Body'] ?? null,
            'message_sid' => $payload['MessageSid'] ?? null,
            'num_media' => $payload['NumMedia'] ?? 0,
            'media_urls' => [],
        ];

        for ($index = 0; $index < intval($incoming['num_media']); $index++) {
            $incoming['media_urls'][] = $payload["MediaUrl{$index}"] ?? null;
        }

        Log::info('[WhatsApp Webhook] Received Twilio webhook event payload.', [
            'payload' => $incoming,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Twilio event received',
        ], 200);
    }

    protected function verifyTwilioSignature(Request $request, string $signature, string $authToken): bool
    {
        $url = $request->fullUrl();
        $params = $request->post();

        ksort($params);

        $data = $url;
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $data .= $key . $item;
                }

                continue;
            }

            $data .= $key . $value;
        }

        $expectedSignature = base64_encode(hash_hmac('sha1', $data, $authToken, true));

        return hash_equals($expectedSignature, $signature);
    }
}
