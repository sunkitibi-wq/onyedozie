<?php

use App\Models\User;
use App\Models\Lga;
use App\Models\PhoneOtp;
use App\Models\Ward;
use App\Models\PollingUnit;
use App\Models\Event;
use App\Models\Task;
use Database\Seeders\GeographicSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    config([
        'otp.testing_code' => '654321',
        'otp.resend_cooldown_seconds' => 0,
        'otp.max_attempts' => 3,
    ]);

    $this->seed(GeographicSeeder::class);
    $this->seed(RoleSeeder::class);
});

test('a user can register and login via API', function () {
    $lga = Lga::first();
    $ward = Ward::first();
    $pu = PollingUnit::first();

    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'John Volunteer',
        'email' => 'john.volunteer@example.com',
        'phone' => '08099998888',
        'password' => 'password123',
        'role' => 'Volunteer',
        'lga_id' => $lga->id,
        'ward_id' => $ward->id,
        'polling_unit_id' => $pu->id,
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user',
                'token'
            ]
        ]);

    $loginResponse = $this->postJson('/api/v1/auth/login', [
        'phone' => '08099998888',
        'password' => 'password123',
    ]);

    $loginResponse->assertStatus(200)
        ->assertJsonPath('success', true);
});

test('unverified user login triggers phone verification OTP', function () {
    $user = User::factory()->create([
        'phone' => '08070000009',
        'password' => Hash::make('password123'),
        'phone_verified_at' => null,
    ]);

    // Ensure no OTP exists initially
    expect(PhoneOtp::where('phone', $user->phone)->exists())->toBeFalse();

    $response = $this->postJson('/api/v1/auth/login', [
        'phone' => '08070000009',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    // Verify OTP was created
    $otp = PhoneOtp::where('phone', $user->phone)
        ->where('purpose', 'phone_verification')
        ->first();

    expect($otp)->not->toBeNull()
        ->and(Hash::check('654321', $otp->code_hash))->toBeTrue();
});

test('unverified user can resend phone verification OTP', function () {
    $user = User::factory()->create([
        'phone' => '08070000010',
        'phone_verified_at' => null,
    ]);

    $response = $this->postJson('/api/v1/auth/resend-otp', [
        'phone' => $user->phone,
        'purpose' => 'phone_verification',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'OTP sent to your registered email address.');

    $otp = PhoneOtp::where('phone', $user->phone)
        ->where('purpose', 'phone_verification')
        ->first();

    expect($otp)->not->toBeNull();
});

test('password reset otp is stored hashed with an expiry', function () {
    $user = User::factory()->create([
        'phone' => '08070000001',
    ]);

    $response = $this->postJson('/api/v1/auth/forgot-password', [
        'phone' => $user->phone,
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $otp = PhoneOtp::where('phone', $user->phone)
        ->where('purpose', 'password_reset')
        ->first();

    expect($otp)->not->toBeNull()
        ->and($otp->code_hash)->not->toBe('654321')
        ->and(Hash::check('654321', $otp->code_hash))->toBeTrue()
        ->and($otp->expires_at->isFuture())->toBeTrue();
});

test('password reset otp can be verified and consumed when password is reset', function () {
    $user = User::factory()->create([
        'phone' => '08070000002',
        'password' => Hash::make('old-password'),
    ]);

    $this->postJson('/api/v1/auth/forgot-password', [
        'phone' => $user->phone,
    ])->assertStatus(200);

    $this->postJson('/api/v1/auth/verify-otp', [
        'phone' => $user->phone,
        'otp' => '654321',
    ])->assertStatus(200);

    $this->postJson('/api/v1/auth/reset-password', [
        'phone' => $user->phone,
        'otp' => '654321',
        'password' => 'new-password',
    ])->assertStatus(200)
        ->assertJsonPath('success', true);

    $user->refresh();
    $otp = PhoneOtp::where('phone', $user->phone)->where('purpose', 'password_reset')->first();

    expect(Hash::check('new-password', $user->password))->toBeTrue()
        ->and($otp->consumed_at)->not->toBeNull();
});

test('password reset verification can target its otp purpose when phone verification is also pending', function () {
    $lga = Lga::first();
    $ward = Ward::first();
    $pu = PollingUnit::first();

    config(['otp.testing_code' => '111111']);

    $this->postJson('/api/v1/auth/register', [
        'name' => 'Pending Phone User',
        'email' => 'pending.phone@example.com',
        'phone' => '08070000006',
        'password' => 'password123',
        'role' => 'Volunteer',
        'lga_id' => $lga->id,
        'ward_id' => $ward->id,
        'polling_unit_id' => $pu->id,
    ])->assertStatus(201);

    config(['otp.testing_code' => '222222']);

    $this->postJson('/api/v1/auth/forgot-password', [
        'phone' => '08070000006',
    ])->assertStatus(200);

    $this->postJson('/api/v1/auth/verify-otp', [
        'phone' => '08070000006',
        'otp' => '222222',
        'purpose' => 'password_reset',
    ])->assertStatus(200)
        ->assertJsonPath('success', true);
});

test('expired password reset otp is rejected', function () {
    $user = User::factory()->create([
        'phone' => '08070000003',
    ]);

    $this->postJson('/api/v1/auth/forgot-password', [
        'phone' => $user->phone,
    ])->assertStatus(200);

    PhoneOtp::where('phone', $user->phone)
        ->where('purpose', 'password_reset')
        ->update(['expires_at' => now()->subMinute()]);

    $this->postJson('/api/v1/auth/reset-password', [
        'phone' => $user->phone,
        'otp' => '654321',
        'password' => 'new-password',
    ])->assertStatus(400)
        ->assertJsonPath('success', false);
});

test('password reset otp locks after too many incorrect attempts', function () {
    $user = User::factory()->create([
        'phone' => '08070000004',
    ]);

    $this->postJson('/api/v1/auth/forgot-password', [
        'phone' => $user->phone,
    ])->assertStatus(200);

    $payload = [
        'phone' => $user->phone,
        'otp' => '000000',
    ];

    $this->postJson('/api/v1/auth/verify-otp', $payload)->assertStatus(400);
    $this->postJson('/api/v1/auth/verify-otp', $payload)->assertStatus(400);
    $this->postJson('/api/v1/auth/verify-otp', $payload)->assertStatus(429);

    $otp = PhoneOtp::where('phone', $user->phone)->where('purpose', 'password_reset')->first();

    expect($otp->attempts)->toBe(3);
});

test('password reset otp requests are rate limited', function () {
    config([
        'otp.request_rate_limit.max_attempts' => 1,
        'otp.request_rate_limit.decay_seconds' => 60,
    ]);

    $user = User::factory()->create([
        'phone' => '08070000005',
    ]);

    $payload = ['phone' => $user->phone];

    $this->postJson('/api/v1/auth/forgot-password', $payload)->assertStatus(200);
    $this->postJson('/api/v1/auth/forgot-password', $payload)->assertStatus(429)
        ->assertJsonPath('success', false);
});

test('authenticated user can retrieve members', function () {
    $user = User::factory()->create([
        'status' => 'active',
    ]);
    $user->assignRole('Super Admin');

    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/members');

    $response->assertStatus(200)
        ->assertJsonPath('success', true);
});

test('authenticated user can check in to events', function () {
    $user = User::factory()->create([
        'status' => 'active',
    ]);
    $user->assignRole('Volunteer');

    $event = Event::create([
        'title' => 'Mobilization Rally',
        'date' => now()->addDay(),
        'venue' => 'Square',
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->postJson("/api/v1/events/{$event->id}/check-in");

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('leaderboard_points', [
        'user_id' => $user->id,
        'points' => 5,
        'source_type' => 'event',
    ]);
});

test('authenticated user can log door knocks', function () {
    $user = User::factory()->create([
        'status' => 'active',
    ]);
    $user->assignRole('Volunteer');

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/door-knocks', [
            'lat' => 6.22,
            'lng' => 7.01,
            'outcome' => 'supportive',
            'voter_name' => 'Adama Obi',
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('leaderboard_points', [
        'user_id' => $user->id,
        'points' => 2,
        'source_type' => 'door_knock',
    ]);
});

test('authenticated user can submit results and incidents', function () {
    $user = User::factory()->create([
        'status' => 'active',
    ]);
    $user->assignRole('Polling Unit Coordinator');

    $pu = PollingUnit::first();

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/results', [
            'polling_unit_id' => $pu->id,
            'ec8a_image_path' => '/uploads/ec8a.png',
            'results' => [
                ['party' => 'APGA', 'votes' => 320],
                ['party' => 'APC', 'votes' => 45],
                ['party' => 'PDP', 'votes' => 12],
            ]
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    $incidentResponse = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/incidents', [
            'type' => 'violence',
            'description' => 'Disruption by thugs near PU entrance.',
            'urgency' => 'critical',
            'lat' => 6.22,
            'lng' => 7.01,
        ]);

    $incidentResponse->assertStatus(201)
        ->assertJsonPath('success', true);
});

test('authenticated user can recruit a new volunteer', function () {
    Storage::fake('public');

    $recruiter = User::factory()->create([
        'status' => 'active',
    ]);
    $recruiter->assignRole('Volunteer');

    $lga = Lga::first();
    $ward = Ward::first();
    $pu = PollingUnit::first();
    $file = UploadedFile::fake()->image('passport.png');

    $response = $this->actingAs($recruiter, 'sanctum')
        ->postJson('/api/v1/members/recruit', [
            'name' => 'Recruited Supporter',
            'phone' => '08011112222',
            'occupation' => 'Trader',
            'lga_id' => $lga->id,
            'ward_id' => $ward->id,
            'polling_unit_id' => $pu->id,
            'notes' => 'Met at market mobilization rally',
            'passport' => $file,
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'volunteer',
                'recruiter_points_awarded',
            ]
        ]);

    // Retrieve created volunteer to check passport_path
    $volunteer = User::where('phone', '08011112222')->first();
    $this->assertNotNull($volunteer);
    $this->assertNotNull($volunteer->passport_path);
    $this->assertStringStartsWith('/storage/passports/', $volunteer->passport_path);

    // Extract filename from passport path
    $storedFilename = basename($volunteer->passport_path);
    Storage::disk('public')->assertExists('passports/' . $storedFilename);

    $this->assertDatabaseHas('users', [
        'name' => 'Recruited Supporter',
        'phone' => '08011112222',
        'occupation' => 'Trader',
        'lga_id' => $lga->id,
        'ward_id' => $ward->id,
        'polling_unit_id' => $pu->id,
        'status' => 'active',
        'passport_path' => $volunteer->passport_path,
    ]);

    $this->assertTrue($volunteer->hasRole('Volunteer'));

    $this->assertDatabaseHas('leaderboard_points', [
        'user_id' => $recruiter->id,
        'points' => 10,
        'source_type' => 'recruitment',
        'source_id' => $volunteer->id,
    ]);
});
