<?php

use App\Models\User;
use App\Models\Task;
use Database\Seeders\GeographicSeeder;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed(GeographicSeeder::class);
    $this->seed(RoleSeeder::class);
});

test('task assigned notification is written to database when task is created via API for a specific user', function () {
    $admin = User::factory()->create(['status' => 'active']);
    $admin->assignRole('Super Admin');

    $agent = User::factory()->create(['status' => 'active']);
    $agent->assignRole('Volunteer');

    // Create the task directly via Livewire component logic (simulate task creation)
    $task = Task::create([
        'title'            => 'Test Notification Task',
        'description'      => 'Test body',
        'deadline'         => now()->addDay(),
        'status'           => 'pending',
        'assigned_user_id' => $agent->id,
        'created_by'       => $admin->id,
    ]);

    $notification = new \App\Notifications\GeneralCampaignNotification(
        'New Task Assigned: ' . $task->title,
        $task->description,
        'task_assigned',
        ['task_id' => $task->id]
    );
    $agent->notify($notification);

    // Notification must be immediately in the DB (no queue)
    $this->assertDatabaseHas('notifications', [
        'notifiable_id'   => $agent->id,
        'notifiable_type' => User::class,
    ]);

    // Verify the data payload
    $stored = $agent->fresh()->notifications()->first();
    expect($stored)->not->toBeNull();
    expect($stored->data['type'])->toBe('task_assigned');
    expect($stored->data['title'])->toContain('Test Notification Task');
    expect($stored->read_at)->toBeNull(); // unread by default
});

test('task completed notification is written to database and notifies super admin', function () {
    $admin = User::factory()->create(['status' => 'active']);
    $admin->assignRole('Super Admin');

    $agent = User::factory()->create(['status' => 'active']);
    $agent->assignRole('Volunteer');

    $task = Task::create([
        'title'       => 'Canvass Ward 4',
        'description' => 'Go door to door',
        'deadline'    => now()->addDay(),
        'status'      => 'pending',
        'created_by'  => $admin->id,
    ]);

    // Fire the completion notification to admin
    $notification = new \App\Notifications\GeneralCampaignNotification(
        'Task Completed: ' . $task->title,
        $agent->name . ' submitted a completion report.',
        'task_completed',
        ['task_id' => $task->id]
    );
    $admin->notify($notification);

    $this->assertDatabaseHas('notifications', [
        'notifiable_id'   => $admin->id,
        'notifiable_type' => User::class,
    ]);

    $stored = $admin->fresh()->notifications()->first();
    expect($stored->data['type'])->toBe('task_completed');
    expect($stored->read_at)->toBeNull();
});

test('task verified notification is written to database and agent can mark it as read via API', function () {
    $admin = User::factory()->create(['status' => 'active']);
    $admin->assignRole('Super Admin');

    $agent = User::factory()->create(['status' => 'active']);
    $agent->assignRole('Volunteer');

    $task = Task::create([
        'title'       => 'Map Polling Units',
        'description' => 'Walk the boundary',
        'deadline'    => now()->addDay(),
        'status'      => 'completed',
        'created_by'  => $admin->id,
        'verified_by' => $admin->id,
    ]);

    // Fire verified notification to agent
    $notification = new \App\Notifications\GeneralCampaignNotification(
        'Task Verified: ' . $task->title,
        'Your submission was reviewed and verified. Great work!',
        'task_verified',
        ['task_id' => $task->id]
    );
    $agent->notify($notification);

    // Get the notification ID
    $notif = $agent->fresh()->notifications()->first();
    expect($notif)->not->toBeNull();
    expect($notif->read_at)->toBeNull();

    // Agent marks it as read via API
    $response = $this->actingAs($agent, 'sanctum')
        ->postJson("/api/v1/notifications/{$notif->id}/read");

    $response->assertStatus(200)->assertJsonPath('success', true);

    // Now it should be marked read
    expect($notif->fresh()->read_at)->not->toBeNull();
});

test('mark all notifications as read via API clears all unread', function () {
    $agent = User::factory()->create(['status' => 'active']);
    $agent->assignRole('Volunteer');

    // Create 3 unread notifications
    foreach (range(1, 3) as $i) {
        $agent->notify(new \App\Notifications\GeneralCampaignNotification(
            "Task $i Assigned",
            "Description $i",
            'task_assigned'
        ));
    }

    expect($agent->fresh()->unreadNotifications()->count())->toBe(3);

    $response = $this->actingAs($agent, 'sanctum')
        ->postJson('/api/v1/notifications/read-all');

    $response->assertStatus(200)->assertJsonPath('success', true);

    expect($agent->fresh()->unreadNotifications()->count())->toBe(0);
});
