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
        // Meta parameters can have dots, which PHP converts to underscores in query keys
        $mode = $request->input('hub_mode') ?? $request->input('hub.mode');
        $token = $request->input('hub_verify_token') ?? $request->input('hub.verify_token');
        $challenge = $request->input('hub_challenge') ?? $request->input('hub.challenge');

        $configuredToken = env('WHATSAPP_WEBHOOK_VERIFY_TOKEN', 'onyendozi-whatsapp-token-2026');

        if ($mode === 'subscribe' && $token === $configuredToken) {
            Log::info('[WhatsApp Webhook] Verification successful.', [
                'mode' => $mode,
                'token' => $token,
                'challenge' => $challenge
            ]);

            return response($challenge, 200)
                ->header('Content-Type', 'text/plain');
        }

        Log::warning('[WhatsApp Webhook] Verification token mismatch or invalid mode.', [
            'mode' => $mode,
            'token' => $token,
            'challenge' => $challenge
        ]);

        return response('Forbidden', 403);
    }

    /**
     * Handle incoming Meta WhatsApp Webhook event notifications (POST).
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('[WhatsApp Webhook] Received webhook event payload.', [
            'payload' => $payload
        ]);

        // Standard Meta WhatsApp Webhook requires a swift HTTP 200 OK response 
        // to avoid event retries or automatic webhook suspension.
        return response()->json([
            'status' => 'success',
            'message' => 'Event received'
        ], 200);
    }
}
