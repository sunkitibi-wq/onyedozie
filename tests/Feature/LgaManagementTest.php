<?php

use App\Models\User;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\PollingUnit;
use App\Models\ActivityLog;
use Livewire\Livewire;
use App\Livewire\LgaManagement;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Seed Spatie roles if needed
    if (Role::count() === 0) {
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }
});

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

test('super admin can create lga, ward, and polling unit with activity logging', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');
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

test('super admin can delete polling unit, ward, and lga, verifying cascades and logging', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');
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

test('super admin can edit lga with activity logging', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');
    $this->actingAs($user);

    $lga = Lga::create(['name' => 'Original LGA Name', 'state' => 'Anambra']);

    Livewire::test(LgaManagement::class)
        ->call('editLga', $lga->id)
        ->assertSet('editingLgaId', $lga->id)
        ->assertSet('editingLgaName', 'Original LGA Name')
        ->set('editingLgaName', 'Updated LGA Name')
        ->call('updateLga')
        ->assertHasNoErrors()
        ->assertSet('editingLgaId', null)
        ->assertSet('editingLgaName', null);

    expect($lga->fresh()->name)->toBe('Updated LGA Name');

    $log = ActivityLog::where('subject_type', Lga::class)
        ->where('subject_id', $lga->id)
        ->where('description', 'Updated LGA: Original LGA Name to Updated LGA Name')
        ->first();
    expect($log)->not->toBeNull();
});

test('super admin can edit ward with activity logging', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');
    $this->actingAs($user);

    $lga1 = Lga::create(['name' => 'LGA 1', 'state' => 'Anambra']);
    $lga2 = Lga::create(['name' => 'LGA 2', 'state' => 'Anambra']);
    $ward = Ward::create(['name' => 'Original Ward Name', 'lga_id' => $lga1->id]);

    Livewire::test(LgaManagement::class)
        ->call('editWard', $ward->id)
        ->assertSet('editingWardId', $ward->id)
        ->assertSet('editingWardName', 'Original Ward Name')
        ->assertSet('editingWardLgaId', $lga1->id)
        ->set('editingWardName', 'Updated Ward Name')
        ->set('editingWardLgaId', $lga2->id)
        ->call('updateWard')
        ->assertHasNoErrors()
        ->assertSet('editingWardId', null)
        ->assertSet('editingWardName', null)
        ->assertSet('editingWardLgaId', null);

    $ward = $ward->fresh();
    expect($ward->name)->toBe('Updated Ward Name');
    expect($ward->lga_id)->toBe($lga2->id);

    $log = ActivityLog::where('subject_type', Ward::class)
        ->where('subject_id', $ward->id)
        ->where('description', 'Updated Ward: Original Ward Name to Updated Ward Name')
        ->first();
    expect($log)->not->toBeNull();
});

test('super admin can edit polling unit with activity logging', function () {
    $user = User::factory()->create();
    $user->assignRole('Super Admin');
    $this->actingAs($user);

    $lga = Lga::create(['name' => 'LGA', 'state' => 'Anambra']);
    $ward1 = Ward::create(['name' => 'Ward 1', 'lga_id' => $lga->id]);
    $ward2 = Ward::create(['name' => 'Ward 2', 'lga_id' => $lga->id]);
    
    $pu = PollingUnit::create([
        'name' => 'Original PU',
        'code' => 'PU-CODE-1',
        'ward_id' => $ward1->id,
        'lat' => 6.1,
        'lng' => 7.0,
        'registered_voters' => 100,
    ]);

    Livewire::test(LgaManagement::class)
        ->call('editPu', $pu->id)
        ->assertSet('editingPuId', $pu->id)
        ->assertSet('editingPuName', 'Original PU')
        ->assertSet('editingPuCode', 'PU-CODE-1')
        ->assertSet('editingPuWardId', $ward1->id)
        ->assertSet('editingPuLat', 6.1)
        ->assertSet('editingPuLng', 7.0)
        ->assertSet('editingPuVoters', 100)
        ->set('editingPuName', 'Updated PU')
        ->set('editingPuCode', 'PU-CODE-2')
        ->set('editingPuWardId', $ward2->id)
        ->set('editingPuLat', 6.2)
        ->set('editingPuLng', 7.1)
        ->set('editingPuVoters', 200)
        ->call('updatePu')
        ->assertHasNoErrors()
        ->assertSet('editingPuId', null);

    $pu = $pu->fresh();
    expect($pu->name)->toBe('Updated PU');
    expect($pu->code)->toBe('PU-CODE-2');
    expect($pu->ward_id)->toBe($ward2->id);
    expect((float)$pu->lat)->toBe(6.2);
    expect((float)$pu->lng)->toBe(7.1);
    expect($pu->registered_voters)->toBe(200);

    $log = ActivityLog::where('subject_type', PollingUnit::class)
        ->where('subject_id', $pu->id)
        ->where('description', 'Updated Polling Unit: Original PU to Updated PU (PU-CODE-2)')
        ->first();
    expect($log)->not->toBeNull();
});

test('non super admin cannot modify geographic infrastructure', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $lga = Lga::create(['name' => 'Test LGA', 'state' => 'Anambra']);
    $ward = Ward::create(['name' => 'Test Ward', 'lga_id' => $lga->id]);
    $pu = PollingUnit::create([
        'name' => 'Test PU',
        'code' => 'PU-1',
        'ward_id' => $ward->id,
    ]);

    // Try to create LGA
    Livewire::test(LgaManagement::class)
        ->set('lgaName', 'Failed LGA')
        ->call('createLga')
        ->assertStatus(403);

    // Try to edit LGA
    Livewire::test(LgaManagement::class)
        ->call('editLga', $lga->id)
        ->assertStatus(403);

    // Try to update LGA
    Livewire::test(LgaManagement::class)
        ->set('editingLgaId', $lga->id)
        ->set('editingLgaName', 'Failed Update')
        ->call('updateLga')
        ->assertStatus(403);

    // Try to delete LGA
    Livewire::test(LgaManagement::class)
        ->call('deleteLga', $lga->id)
        ->assertStatus(403);

    // Try to create Ward
    Livewire::test(LgaManagement::class)
        ->set('wardName', 'Failed Ward')
        ->set('wardLgaId', $lga->id)
        ->call('createWard')
        ->assertStatus(403);

    // Try to edit Ward
    Livewire::test(LgaManagement::class)
        ->call('editWard', $ward->id)
        ->assertStatus(403);

    // Try to delete Ward
    Livewire::test(LgaManagement::class)
        ->call('deleteWard', $ward->id)
        ->assertStatus(403);

    // Try to create PU
    Livewire::test(LgaManagement::class)
        ->set('puName', 'Failed PU')
        ->set('puCode', 'PU-2')
        ->set('puWardId', $ward->id)
        ->call('createPu')
        ->assertStatus(403);

    // Try to edit PU
    Livewire::test(LgaManagement::class)
        ->call('editPu', $pu->id)
        ->assertStatus(403);

    // Try to delete PU
    Livewire::test(LgaManagement::class)
        ->call('deletePu', $pu->id)
        ->assertStatus(403);
});
