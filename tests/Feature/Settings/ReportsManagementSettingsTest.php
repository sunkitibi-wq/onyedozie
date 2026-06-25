<?php

use App\Models\User;
use App\Models\Setting;
use Livewire\Livewire;
use App\Livewire\ReportsManagement;

test('settings API endpoint returns default visibility settings', function () {
    $response = $this->getJson('/api/v1/settings');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.quick_actions_enabled', false) // Default is 0
        ->assertJsonPath('data.quick_action_recruit_visible', true)
        ->assertJsonPath('data.quick_action_results_visible', true)
        ->assertJsonPath('data.quick_action_incident_visible', true)
        ->assertJsonPath('data.quick_action_voice_visible', true)
        ->assertJsonPath('data.quick_action_members_visible', true)
        ->assertJsonPath('data.quick_action_mobilize_visible', true);
});

test('settings API endpoint returns database visibility values', function () {
    Setting::set('quick_actions_enabled', '1');
    Setting::set('quick_action_recruit_visible', '0');
    Setting::set('quick_action_results_visible', '1');
    Setting::set('quick_action_incident_visible', '0');

    $response = $this->getJson('/api/v1/settings');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.quick_actions_enabled', true)
        ->assertJsonPath('data.quick_action_recruit_visible', false)
        ->assertJsonPath('data.quick_action_results_visible', true)
        ->assertJsonPath('data.quick_action_incident_visible', false)
        ->assertJsonPath('data.quick_action_voice_visible', true);
});

test('reports management component mounts with correct default settings', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(ReportsManagement::class)
        ->assertSet('quickActionRecruitVisible', true)
        ->assertSet('quickActionResultsVisible', true)
        ->assertSet('quickActionIncidentVisible', true)
        ->assertSet('quickActionVoiceVisible', true)
        ->assertSet('quickActionMembersVisible', true)
        ->assertSet('quickActionMobilizeVisible', true);
});

test('reports management component toggles individual visibility values and updates database', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Initial check (should be default 1 in DB since it was not set)
    expect(Setting::get('quick_action_recruit_visible', '1'))->toBe('1');

    $component = Livewire::test(ReportsManagement::class);

    // Toggle off
    $component->call('toggleQuickActionVisibility', 'recruit')
        ->assertSet('quickActionRecruitVisible', false);

    expect(Setting::get('quick_action_recruit_visible', '1'))->toBe('0');

    // Toggle on again
    $component->call('toggleQuickActionVisibility', 'recruit')
        ->assertSet('quickActionRecruitVisible', true);

    expect(Setting::get('quick_action_recruit_visible', '1'))->toBe('1');
});
