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
     * Handle Meta WhatsApp Webhook verification checks (GET).
     */
    public function verify(Request $request): Response
    {
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
     * Handle incoming Meta WhatsApp Webhook event notifications (POST).
     */
    public function handle(Request $request): JsonResponse
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

        $eventPayload = $request->all();

        Log::info('[WhatsApp Webhook] Received webhook event payload.', [
            'payload' => $eventPayload,
        ]);

        // Meta expects a swift HTTP 200 OK response to avoid retries or suspension.
        return response()->json([
            'status' => 'success',
            'message' => 'Event received',
        ], 200);
    }
}
