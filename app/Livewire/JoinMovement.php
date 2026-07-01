<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\PollingUnit;
use App\Models\ActivityLog;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class JoinMovement extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    public $lgaId = '';
    public $wardId = '';
    public $pollingUnitId = '';
    public $selectedRole = '';

    // Dynamic Lists
    public $wards = [];
    public $pollingUnits = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'selectedRole' => 'required|string|exists:roles,name',
            'lgaId' => 'nullable|exists:lgas,id',
            'wardId' => 'nullable|exists:wards,id',
            'pollingUnitId' => 'nullable|exists:polling_units,id',
        ];
    }

    public function updatedLgaId($value)
    {
        $this->wards = $value ? Ward::where('lga_id', $value)->get() : [];
        $this->wardId = '';
        $this->pollingUnits = [];
        $this->pollingUnitId = '';
    }

    public function updatedWardId($value)
    {
        $this->pollingUnits = $value ? PollingUnit::where('ward_id', $value)->get() : [];
        $this->pollingUnitId = '';
    }

    public function submit()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email ?: null,
            'password' => Hash::make($this->password),
            'status' => 'active', // default active status
            'lga_id' => $this->lgaId ?: null,
            'ward_id' => $this->wardId ?: null,
            'polling_unit_id' => $this->pollingUnitId ?: null,
        ]);

        if ($this->selectedRole) {
            $user->syncRoles([$this->selectedRole]);
        }

        event(new Registered($user));

        ActivityLog::log(
            "New campaign supporter registered via web signup: {$user->name} as {$this->selectedRole}",
            $user
        );

        session()->flash('message', 'Thank you! You have successfully registered and joined the movement.');

        $this->reset([
            'name',
            'email',
            'phone',
            'password',
            'lgaId',
            'wardId',
            'pollingUnitId',
            'selectedRole',
            'wards',
            'pollingUnits'
        ]);
    }

    public function render()
    {
        // Fetch only campaign role levels for public signup
        $roles = Role::whereIn('name', [
            'Volunteer',
            'Polling Unit Coordinator',
            'Ward Coordinator',
            'LGA Coordinator'
        ])->get();

        return view('livewire.join-movement', [
            'lgas' => Lga::all(),
            'roles' => $roles,
        ]);
    }
}
