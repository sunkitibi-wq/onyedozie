<?php

use App\Livewire\JoinMovement;
use App\Models\User;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\PollingUnit;
use Spatie\Permission\Models\Role;
use Livewire\Livewire;

test('join movement page can be rendered', function () {
    $response = $this->get(route('join'));

    $response->assertOk()
        ->assertSeeLivewire(JoinMovement::class);
});

test('supporters can register through join movement form', function () {
    // Seed role & geography
    $role = Role::firstOrCreate(['name' => 'Volunteer']);
    $lga = Lga::firstOrCreate(['name' => 'Anaocha', 'state' => 'Anambra']);
    $ward = Ward::firstOrCreate(['name' => 'Adazi-Nnukwu II', 'lga_id' => $lga->id]);
    $pu = PollingUnit::firstOrCreate(['name' => 'PU 1', 'code' => '001', 'ward_id' => $ward->id]);

    Livewire::test(JoinMovement::class)
        ->set('name', 'Supporter Supporter')
        ->set('phone', '08012345678')
        ->set('email', 'supporter@example.com')
        ->set('password', 'password123')
        ->set('selectedRole', 'Volunteer')
        ->set('lgaId', $lga->id)
        ->set('wardId', $ward->id)
        ->set('pollingUnitId', $pu->id)
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('users', [
        'name' => 'Supporter Supporter',
        'phone' => '08012345678',
        'email' => 'supporter@example.com',
        'lga_id' => $lga->id,
        'ward_id' => $ward->id,
        'polling_unit_id' => $pu->id,
    ]);

    $user = User::where('phone', '08012345678')->first();
    expect($user->hasRole('Volunteer'))->toBeTrue();
});
