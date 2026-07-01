<?php

namespace App\Listeners;

use App\Mail\NewSupporterAdminMail;
use App\Mail\WelcomeSupporterMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendRegistrationEmails
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        // 1. Send admin notification email
        try {
            Mail::to('info@onyendoziconnect.org')->send(new NewSupporterAdminMail($user));
        } catch (\Throwable $e) {
            Log::error("Failed to send admin supporter registration notification: " . $e->getMessage(), [
                'user_id' => $user->id,
                'exception' => $e
            ]);
        }

        // 2. Send welcome email to user if they provided an email address
        if ($user->email) {
            try {
                Mail::to($user->email)->send(new WelcomeSupporterMail($user));
            } catch (\Throwable $e) {
                Log::error("Failed to send welcome email to supporter: " . $e->getMessage(), [
                    'user_id' => $user->id,
                    'exception' => $e
                ]);
            }
        }
    }
}
