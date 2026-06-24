<?php

use App\Models\User;
use App\Notifications\GeneralCampaignNotification;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

test('a user can receive database notifications', function () {
    $user = User::factory()->create();

    $notification = new GeneralCampaignNotification('New Task', 'You have been assigned a task.', 'task', ['url' => '/tasks/1']);
    $user->notify($notification);

    expect($user->unreadNotifications)->toHaveCount(1);
    expect($user->unreadNotifications->first()->data['title'])->toBe('New Task');
    expect($user->unreadNotifications->first()->data['type'])->toBe('task');
});

test('authenticated user can list their notifications via api', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $notification = new GeneralCampaignNotification('Alert', 'New system updates.', 'system');
    $user->notify($notification);

    $response = $this->getJson('/api/v1/notifications');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.data.title', 'Alert');
});

test('authenticated user can mark a notification as read', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $notification = new GeneralCampaignNotification('Alert', 'New system updates.', 'system');
    $user->notify($notification);

    $dbNotification = $user->unreadNotifications->first();

    $response = $this->postJson("/api/v1/notifications/{$dbNotification->id}/read");

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    expect($user->fresh()->unreadNotifications)->toHaveCount(0);
});

test('authenticated user can mark all notifications as read', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $user->notify(new GeneralCampaignNotification('Alert 1', 'Update 1', 'system'));
    $user->notify(new GeneralCampaignNotification('Alert 2', 'Update 2', 'system'));

    expect($user->unreadNotifications)->toHaveCount(2);

    $response = $this->postJson('/api/v1/notifications/read-all');

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    expect($user->fresh()->unreadNotifications)->toHaveCount(0);
});
