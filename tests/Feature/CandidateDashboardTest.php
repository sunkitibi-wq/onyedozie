<?php

use App\Models\User;
use App\Models\Setting;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    if (Role::count() === 0) {
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }
});

test('guest users cannot access the candidate dashboard page', function () {
    $response = $this->get(route('candidate-dashboard'));
    $response->assertRedirect(route('login'));
});

test('volunteers cannot access the candidate dashboard page', function () {
    $user = User::factory()->create();
    $user->assignRole('Volunteer');

    $response = $this->actingAs($user)->get(route('candidate-dashboard'));
    $response->assertStatus(403);
});

test('super admin can access the candidate dashboard page', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $response = $this->actingAs($user)->get(route('candidate-dashboard'));
    $response->assertStatus(200);
    $response->assertSeeLivewire(\App\Livewire\CandidateDashboard::class);
});

test('candidate dashboard role can access the candidate dashboard page', function () {
    $user = User::factory()->create();
    $user->assignRole('Candidate Dashboard');

    $response = $this->actingAs($user)->get(route('candidate-dashboard'));
    $response->assertStatus(200);
    $response->assertSeeLivewire(\App\Livewire\CandidateDashboard::class);
});

test('anyone can access candidate dashboard via public shareable link with valid token', function () {
    // Generate valid token
    $token = 'test-token-12345';
    Setting::set('candidate_share_token', $token);

    $response = $this->get(route('candidate.public-share', ['token' => $token]));
    $response->assertStatus(200);
    $response->assertSeeLivewire(\App\Livewire\CandidateDashboard::class);
});

test('candidate dashboard public share link returns 403 on invalid token', function () {
    Setting::set('candidate_share_token', 'valid-token');

    $response = $this->get(route('candidate.public-share', ['token' => 'invalid-token']));
    $response->assertStatus(403);
});
