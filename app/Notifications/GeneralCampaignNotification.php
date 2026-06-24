<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class GeneralCampaignNotification extends Notification
{
    public string $title;
    public string $body;
    public string $type;
    public ?array $actionPayload;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        string $title,
        string $body,
        string $type = 'general',
        ?array $actionPayload = null
    ) {
        $this->title = $title;
        $this->body = $body;
        $this->type = $type;
        $this->actionPayload = $actionPayload;
    }

    /**
     * Deliver only through the database channel — no queue, no mail.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Persist the notification payload to the notifications table.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'          => $this->title,
            'body'           => $this->body,
            'type'           => $this->type,
            'action_payload' => $this->actionPayload,
        ];
    }
}
