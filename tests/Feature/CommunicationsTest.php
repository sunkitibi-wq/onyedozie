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
