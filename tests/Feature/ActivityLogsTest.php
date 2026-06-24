<?php

use App\Models\User;
use App\Models\ActivityLog;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Seed Spatie roles if needed
    if (Role::count() === 0) {
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }
});

test('guest users cannot access the activity logs page', function () {
    $response = $this->get(route('activity-logs'));
    $response->assertRedirect(route('login'));
});

test('super admin can access the activity logs page', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $response = $this->actingAs($user)->get(route('activity-logs'));
    $response->assertStatus(200);
    $response->assertSeeLivewire(\App\Livewire\ActivityLogs::class);
});

test('non-permitted roles cannot see activity logs sidebar item', function () {
    $user = User::factory()->create();
    $user->assignRole('Volunteer');

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertStatus(200);
    $response->assertDontSee('Activity Logs');
});

test('super admin can see activity logs sidebar item', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertStatus(200);
    $response->assertSee('Activity Logs');
});

test('livewire activity logs component can search and filter logs', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Super Admin');

    ActivityLog::log('Created test document', null, $admin, null, 'Document Log');
    ActivityLog::log('Deleted old files', null, $admin, null, 'File Log');

    Livewire::actingAs($admin)
        ->test(\App\Livewire\ActivityLogs::class)
        ->assertSee('Created test document')
        ->assertSee('Deleted old files')
        ->set('search', 'Document')
        ->assertSee('Created test document')
        ->assertDontSee('Deleted old files')
        ->set('search', '')
        ->set('logNameFilter', 'File Log')
        ->assertSee('Deleted old files')
        ->assertDontSee('Created test document');
});
