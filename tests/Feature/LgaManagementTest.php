<?php

use App\Models\User;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\PollingUnit;
use App\Models\ActivityLog;
use Livewire\Livewire;
use App\Livewire\LgaManagement;

test('guest is redirected to login from lgas page', function () {
    $this->get(route('lgas'))->assertRedirect(route('login'));
});

test('authenticated user can view lgas page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get(route('lgas'))
        ->assertOk()
        ->assertSeeLivewire(LgaManagement::class);
});

test('admin can create lga, ward, and polling unit with activity logging', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // 1. Create LGA
    Livewire::test(LgaManagement::class)
        ->set('lgaName', 'Test LGA')
        ->call('createLga')
        ->assertHasNoErrors()
        ->assertSet('lgaName', null);

    $lga = Lga::where('name', 'Test LGA')->first();
    expect($lga)->not->toBeNull();
    expect($lga->state)->toBe('Anambra');

    $lgaLog = ActivityLog::where('subject_type', Lga::class)
        ->where('subject_id', $lga->id)
        ->first();
    expect($lgaLog)->not->toBeNull();
    expect($lgaLog->description)->toBe('Created LGA: Test LGA');

    // 2. Create Ward
    Livewire::test(LgaManagement::class)
        ->set('wardName', 'Test Ward')
        ->set('wardLgaId', $lga->id)
        ->call('createWard')
        ->assertHasNoErrors();

    $ward = Ward::where('name', 'Test Ward')->first();
    expect($ward)->not->toBeNull();
    expect($ward->lga_id)->toBe($lga->id);

    $wardLog = ActivityLog::where('subject_type', Ward::class)
        ->where('subject_id', $ward->id)
        ->first();
    expect($wardLog)->not->toBeNull();
    expect($wardLog->description)->toContain('Created Ward: Test Ward');

    // 3. Create Polling Unit
    Livewire::test(LgaManagement::class)
        ->set('puName', 'Test PU')
        ->set('puCode', 'TEST-PU-CODE')
        ->set('puWardId', $ward->id)
        ->set('puLat', 6.12)
        ->set('puLng', 7.05)
        ->set('puVoters', 150)
        ->call('createPu')
        ->assertHasNoErrors();

    $pu = PollingUnit::where('code', 'TEST-PU-CODE')->first();
    expect($pu)->not->toBeNull();
    expect($pu->name)->toBe('Test PU');
    expect($pu->registered_voters)->toBe(150);

    $puLog = ActivityLog::where('subject_type', PollingUnit::class)
        ->where('subject_id', $pu->id)
        ->first();
    expect($puLog)->not->toBeNull();
    expect($puLog->description)->toBe('Created Polling Unit: Test PU (TEST-PU-CODE)');
});

test('admin can delete polling unit, ward, and lga, verifying cascades and logging', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create hierarchy
    $lga = Lga::create(['name' => 'Delete LGA', 'state' => 'Anambra']);
    $ward = Ward::create(['name' => 'Delete Ward', 'lga_id' => $lga->id]);
    $pu = PollingUnit::create([
        'name' => 'Delete PU',
        'code' => 'DEL-PU-CODE',
        'ward_id' => $ward->id,
        'registered_voters' => 100
    ]);

    // Assign user to geographic records to test set null cascade
    $member = User::factory()->create([
        'lga_id' => $lga->id,
        'ward_id' => $ward->id,
        'polling_unit_id' => $pu->id,
    ]);

    // 1. Delete Polling Unit
    Livewire::test(LgaManagement::class)
        ->call('deletePu', $pu->id)
        ->assertHasNoErrors();

    expect(PollingUnit::find($pu->id))->toBeNull();
    expect($member->fresh()->polling_unit_id)->toBeNull();
    expect($member->fresh()->ward_id)->toBe($ward->id);

    $puDeleteLog = ActivityLog::where('description', 'Deleted Polling Unit: Delete PU (DEL-PU-CODE)')->first();
    expect($puDeleteLog)->not->toBeNull();

    // 2. Delete Ward (should cascade delete any other PU but set user's ward_id to null)
    $pu2 = PollingUnit::create([
        'name' => 'Delete PU 2',
        'code' => 'DEL-PU-CODE-2',
        'ward_id' => $ward->id,
    ]);

    Livewire::test(LgaManagement::class)
        ->call('deleteWard', $ward->id)
        ->assertHasNoErrors();

    expect(Ward::find($ward->id))->toBeNull();
    expect(PollingUnit::find($pu2->id))->toBeNull(); // Cascaded deletion
    expect($member->fresh()->ward_id)->toBeNull();

    $wardDeleteLog = ActivityLog::where('description', 'Deleted Ward: Delete Ward')->first();
    expect($wardDeleteLog)->not->toBeNull();

    // 3. Delete LGA (should cascade delete any other Ward/PU but set user's lga_id to null)
    $ward2 = Ward::create(['name' => 'Delete Ward 2', 'lga_id' => $lga->id]);
    $pu3 = PollingUnit::create([
        'name' => 'Delete PU 3',
        'code' => 'DEL-PU-CODE-3',
        'ward_id' => $ward2->id,
    ]);

    Livewire::test(LgaManagement::class)
        ->call('deleteLga', $lga->id)
        ->assertHasNoErrors();

    expect(Lga::find($lga->id))->toBeNull();
    expect(Ward::find($ward2->id))->toBeNull(); // Cascaded
    expect(PollingUnit::find($pu3->id))->toBeNull(); // Cascaded
    expect($member->fresh()->lga_id)->toBeNull();

    $lgaDeleteLog = ActivityLog::where('description', 'Deleted LGA: Delete LGA')->first();
    expect($lgaDeleteLog)->not->toBeNull();
});
