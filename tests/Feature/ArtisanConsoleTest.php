<?php

use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    if (Role::count() === 0) {
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }
});

test('guest users cannot access the artisan console page', function () {
    $response = $this->get(route('artisan-console'));
    $response->assertRedirect(route('login'));
});

test('non-permitted user roles cannot access the artisan console page', function () {
    $user = User::factory()->create();
    $user->assignRole('Volunteer');

    $response = $this->actingAs($user)->get(route('artisan-console'));
    $response->assertStatus(403);
});

test('super admin can access the artisan console page', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    $response = $this->actingAs($user)->get(route('artisan-console'));
    $response->assertStatus(200);
    $response->assertSeeLivewire(\App\Livewire\ArtisanConsole::class);
});

test('artisan console executes whitelisted commands and prints output', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    Livewire::actingAs($user)
        ->test(\App\Livewire\ArtisanConsole::class)
        ->set('commandString', 'migrate:status')
        ->call('runCommand')
        ->assertSee('Check database migration status')
        ->assertSee('migrate:status');
});

test('artisan console rejects restricted commands', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');

    Livewire::actingAs($user)
        ->test(\App\Livewire\ArtisanConsole::class)
        ->set('commandString', 'tinker')
        ->call('runCommand')
        ->assertSee("ERROR: Command 'tinker' is restricted for safety reasons.");
});
