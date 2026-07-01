<?php

namespace App\Listeners;

use App\Mail\NewSupporterAdminMail;
use App\Mail\WelcomeSupporterMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class SendRegistrationEmails
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        // 1. Send admin notification email
        Mail::to('info@onyendoziconnect.org')->send(new NewSupporterAdminMail($user));

        // 2. Send welcome email to user if they provided an email address
        if ($user->email) {
            Mail::to($user->email)->send(new WelcomeSupporterMail($user));
        }
    }
}
