<?php

namespace App\Jobs;

use App\Models\WhatsappBroadcast;
use App\Models\User;
use App\Notifications\GeneralCampaignNotification;
use App\Services\WhatsappService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsappBroadcastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected WhatsappBroadcast $broadcast;

    /**
     * Create a new job instance.
     */
    public function __construct(WhatsappBroadcast $broadcast)
    {
        $this->broadcast = $broadcast;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsappService $whatsappService): void
    {
        $this->broadcast->update(['status' => 'sending']);

        $phoneNumbers = [];
        $usersToNotify = collect();

        if ($this->broadcast->audience_type === 'custom') {
            $phoneNumbers = $this->broadcast->audience_filter['phones'] ?? [];
            $usersToNotify = User::whereIn('phone', $phoneNumbers)->get();
        } else {
            $query = User::where('status', 'active');

            if ($this->broadcast->audience_type === 'lga') {
                $lgaId = $this->broadcast->audience_filter['lga_id'] ?? null;
                if ($lgaId) {
                    $query->where('lga_id', $lgaId);
                }
            } elseif ($this->broadcast->audience_type === 'role') {
                $roleName = $this->broadcast->audience_filter['role'] ?? null;
                if ($roleName) {
                    $query->role($roleName);
                }
            }

            $usersToNotify = $query->get();
            $phoneNumbers = $usersToNotify->pluck('phone')->toArray();
        }

        $sent = 0;
        $delivered = 0;
        $failed = 0;

        foreach ($phoneNumbers as $phone) {
            $success = $whatsappService->send($phone, $this->broadcast->message, $this->broadcast->media_path);

            $sent++;
            if ($success) {
                $delivered++;
            } else {
                $failed++;
            }

            $this->broadcast->update([
                'sent_count' => $sent,
                'delivered_count' => $delivered,
                'failed_count' => $failed,
            ]);
        }

        $notification = new GeneralCampaignNotification(
            "Mass Mobilization Alert",
            $this->broadcast->message,
            'broadcast',
            ['broadcast_id' => $this->broadcast->id]
        );

        foreach ($usersToNotify as $user) {
            $user->notify($notification);

            if ($user->device_token) {
                $this->sendFcmPush($user->device_token, "Mass Mobilization Alert", $this->broadcast->message);
            }
        }

        $this->broadcast->update(['status' => 'completed']);
    }

    private function sendFcmPush(string $token, string $title, string $body): void
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = env('FIREBASE_SERVER_KEY');

        if (empty($serverKey)) {
            Log::info("[FCM Push - SIMULATION] Sent push to $token. Title: $title, Body: $body");
            return;
        }

        try {
            Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ])->post($fcmUrl, [
                'to' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'sound' => 'default',
                ],
                'data' => [
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'type' => 'broadcast',
                ],
            ]);
        } catch (\Exception $e) {
            Log::error("[FCM Push] Failed to send push to token $token: " . $e->getMessage());
        }
    }
}
