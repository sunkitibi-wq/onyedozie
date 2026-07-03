<?php

use App\Models\User;
use App\Models\WhatsappBroadcast;
use App\Models\ScheduledPost;
use App\Jobs\SendWhatsappBroadcastJob;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

test('unauthenticated users cannot fetch broadcasts', function () {
    $response = $this->getJson('/api/v1/broadcasts');
    $response->assertStatus(401);
});

test('authenticated volunteers can fetch broadcasts but not dispatch them', function () {
    $user = User::factory()->create();
    $user->assignRole('Volunteer');
    $this->actingAs($user, 'sanctum');

    $response = $this->getJson('/api/v1/broadcasts');
    $response->assertOk()
        ->assertJson([
            'success' => true,
        ]);

    $postResponse = $this->postJson('/api/v1/broadcasts', [
        'message' => 'Test alert',
        'audience_type' => 'all',
    ]);
    $postResponse->assertStatus(403);
});

test('authenticated admins can dispatch broadcasts', function () {
    Queue::fake();

    $user = User::factory()->create();
    $user->assignRole('Super Admin');
    $this->actingAs($user, 'sanctum');

    $response = $this->postJson('/api/v1/broadcasts', [
        'message' => 'Mobilization alert message',
        'audience_type' => 'all',
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('whatsapp_broadcasts', [
        'message' => 'Mobilization alert message',
        'audience_type' => 'all',
        'status' => 'pending',
    ]);

    Queue::assertPushed(SendWhatsappBroadcastJob::class);
});

test('authenticated volunteers can fetch social posts but not schedule them', function () {
    $user = User::factory()->create();
    $user->assignRole('Volunteer');
    $this->actingAs($user, 'sanctum');

    $response = $this->getJson('/api/v1/scheduled-posts');
    $response->assertOk()
        ->assertJson([
            'success' => true,
        ]);

    $postResponse = $this->postJson('/api/v1/scheduled-posts', [
        'content' => 'Test social post content',
        'platforms' => ['facebook'],
        'scheduled_at' => now()->addHour()->toIso8601String(),
    ]);
    $postResponse->assertStatus(403);
});

test('authenticated admins can schedule social posts', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');
    $this->actingAs($user, 'sanctum');

    $scheduledTime = now()->addHours(2);

    $response = $this->postJson('/api/v1/scheduled-posts', [
        'content' => 'Scheduled social campaign post',
        'platforms' => ['facebook', 'x'],
        'scheduled_at' => $scheduledTime->toIso8601String(),
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('scheduled_posts', [
        'content' => 'Scheduled social campaign post',
        'status' => 'scheduled',
    ]);
});

test('whatsapp webhook rejects requests with an invalid signature', function () {
    config()->set('services.whatsapp.app_secret', 'test-webhook-secret');

    $response = $this->withHeaders([
        'X-Hub-Signature-256' => 'sha256=invalid-signature',
    ])->postJson('/api/v1/webhooks/whatsapp', [
        'object' => 'whatsapp_business_account',
        'entry' => [],
    ]);

    $response->assertStatus(403);
});

test('whatsapp webhook accepts requests with a valid signature', function () {
    config()->set('services.whatsapp.app_secret', 'test-webhook-secret');

    $body = [
        'object' => 'whatsapp_business_account',
        'entry' => [[
            'id' => '123456789',
            'changes' => [[
                'value' => ['messaging_product' => 'whatsapp'],
                'field' => 'messages',
            ]],
        ]],
    ];

    $payload = json_encode($body);
    $signature = 'sha256=' . hash_hmac('sha256', $payload, 'test-webhook-secret');

    $response = $this->withHeaders([
        'X-Hub-Signature-256' => $signature,
        'Content-Type' => 'application/json',
    ])->postJson('/api/v1/webhooks/whatsapp', $body);

    $response->assertStatus(200)
        ->assertJsonPath('status', 'success');
});

test('whatsapp service uses simulation when config is empty', function () {
    config()->set('services.whatsapp.phone_number_id', null);
    config()->set('services.whatsapp.access_token', null);

    $service = new \App\Services\WhatsappService();
    $result = $service->send('08012345678', 'Test message');
    
    expect($result)->toBeBool();
});

test('admins can resend a broadcast via livewire', function () {
    Queue::fake();

    $user = User::factory()->create();
    $user->assignRole('Super Admin');
    $this->actingAs($user);

    $broadcast = WhatsappBroadcast::create([
        'message' => 'Old message',
        'audience_type' => 'all',
        'status' => 'failed',
        'sent_count' => 10,
        'delivered_count' => 5,
        'failed_count' => 5,
    ]);

    \Livewire\Livewire::test(\App\Livewire\CommunicationsManager::class)
        ->call('resendBroadcast', $broadcast->id);

    $broadcast->refresh();
    expect($broadcast->status)->toBe('pending');
    expect($broadcast->sent_count)->toBe(0);
    expect($broadcast->delivered_count)->toBe(0);
    expect($broadcast->failed_count)->toBe(0);

    Queue::assertPushed(SendWhatsappBroadcastJob::class);
});


