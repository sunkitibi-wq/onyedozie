<?php

use App\Models\User;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\PollingUnit;
use App\Mail\NewSupporterAdminMail;
use App\Mail\WelcomeSupporterMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('emails are sent to admin and registrant when email is provided', function () {
    Mail::fake();

    $lga = Lga::firstOrCreate(['name' => 'Anaocha', 'state' => 'Anambra']);
    $ward = Ward::firstOrCreate(['name' => 'Adazi-Nnukwu II', 'lga_id' => $lga->id]);
    $pu = PollingUnit::firstOrCreate(['name' => 'PU 1', 'code' => '001', 'ward_id' => $ward->id]);

    $user = User::create([
        'name' => 'John Supporter',
        'phone' => '08099887766',
        'email' => 'john@example.com',
        'password' => bcrypt('password123'),
        'lga_id' => $lga->id,
        'ward_id' => $ward->id,
        'polling_unit_id' => $pu->id,
    ]);

    event(new Registered($user));

    // Assert a mail was sent to the admin
    Mail::assertQueued(NewSupporterAdminMail::class, function ($mail) use ($user) {
        return $mail->hasTo('info@onyendoziconnect.org') &&
               $mail->user->id === $user->id;
    });

    // Assert a mail was sent to the registrant
    Mail::assertQueued(WelcomeSupporterMail::class, function ($mail) use ($user) {
        return $mail->hasTo('john@example.com') &&
               $mail->user->id === $user->id;
    });
});

test('only admin email is sent when registrant has no email', function () {
    Mail::fake();

    $lga = Lga::firstOrCreate(['name' => 'Anaocha', 'state' => 'Anambra']);
    $ward = Ward::firstOrCreate(['name' => 'Adazi-Nnukwu II', 'lga_id' => $lga->id]);
    $pu = PollingUnit::firstOrCreate(['name' => 'PU 1', 'code' => '001', 'ward_id' => $ward->id]);

    $user = User::create([
        'name' => 'Jane Supporter',
        'phone' => '08099887755',
        'email' => null, // No email provided
        'password' => bcrypt('password123'),
        'lga_id' => $lga->id,
        'ward_id' => $ward->id,
        'polling_unit_id' => $pu->id,
    ]);

    event(new Registered($user));

    // Assert a mail was sent to the admin
    Mail::assertQueued(NewSupporterAdminMail::class, function ($mail) use ($user) {
        return $mail->hasTo('info@onyendoziconnect.org') &&
               $mail->user->id === $user->id;
    });

    // Assert no welcome email was sent
    Mail::assertNotQueued(WelcomeSupporterMail::class);
});
