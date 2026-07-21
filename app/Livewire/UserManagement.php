<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\PollingUnit;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $roleFilter = '';
    public $lgaFilter = '';
    public $statusFilter = '';
    public $occupationFilter = '';
    
    // Details/Edit/Create Mode
    public $selectedUserId;
    public $isCreating = false;
    
    public $name, $phone, $email, $password, $status, $lgaId, $wardId, $pollingUnitId, $occupation;
    public $selectedRole = '';
    public $passport;
    public $passportPath;

    // Dynamic Lists
    public $wards = [];
    public $pollingUnits = [];

    protected function rules()
    {
        if ($this->isCreating) {
            return [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|unique:users,phone',
                'email' => 'nullable|email|max:255',
                'password' => 'required|string|min:8',
                'selectedRole' => 'required|string',
                'status' => 'required|string',
                'lgaId' => 'nullable|exists:lgas,id',
                'wardId' => 'nullable|exists:wards,id',
                'pollingUnitId' => 'nullable|exists:polling_units,id',
                'passport' => 'nullable|image|max:2048', // 2MB max
                'occupation' => 'nullable|string|max:255',
            ];
        }

        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone,' . $this->selectedUserId,
            'email' => 'nullable|email|max:255',
            'selectedRole' => 'required|string',
            'status' => 'required|string',
            'lgaId' => 'nullable|exists:lgas,id',
            'wardId' => 'nullable|exists:wards,id',
            'pollingUnitId' => 'nullable|exists:polling_units,id',
            'passport' => 'nullable|image|max:2048', // 2MB max
            'occupation' => 'nullable|string|max:255',
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

    public function selectUser($id)
    {
        $this->isCreating = false;
        $user = User::find($id);
        if ($user) {
            $this->selectedUserId = $user->id;
            $this->name = $user->name;
            $this->phone = $user->phone;
            $this->email = $user->email;
            $this->status = $user->status;
            $this->lgaId = $user->lga_id;
            $this->wardId = $user->ward_id;
            $this->pollingUnitId = $user->polling_unit_id;
            $this->occupation = $user->occupation;
            $this->selectedRole = $user->roles->first()?->name ?? '';
            $this->passportPath = $user->passport_path;

            // Hydrate dependent dynamic dropdowns
            $this->wards = $this->lgaId ? Ward::where('lga_id', $this->lgaId)->get() : [];
            $this->pollingUnits = $this->wardId ? PollingUnit::where('ward_id', $this->wardId)->get() : [];
        }
    }

    public function startCreate()
    {
        $this->closeDetails();
        $this->isCreating = true;
        $this->status = 'active';
    }

    public function closeDetails()
    {
        $this->reset([
            'selectedUserId',
            'isCreating',
            'name',
            'phone',
            'email',
            'password',
            'status',
            'lgaId',
            'wardId',
            'pollingUnitId',
            'occupation',
            'selectedRole',
            'passport',
            'passportPath',
            'wards',
            'pollingUnits'
        ]);
        $this->resetErrorBag();
    }

    public function createUser()
    {
        $this->validate();

        $path = null;
        if ($this->passport) {
            $path = $this->passport->store('passports', 'public');
        }

        $user = User::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'status' => $this->status,
            'passport_path' => $path,
            'occupation' => $this->occupation ?: null,
            'lga_id' => $this->lgaId ?: null,
            'ward_id' => $this->wardId ?: null,
            'polling_unit_id' => $this->pollingUnitId ?: null,
        ]);

        if ($this->selectedRole) {
            $user->syncRoles([$this->selectedRole]);
        }

        // Award 10 points to the currently authenticated user (recruiter)
        $recruiter = auth()->user();
        if ($recruiter) {
            \App\Models\LeaderboardPoint::create([
                'user_id' => $recruiter->id,
                'points' => 10,
                'source_type' => 'recruitment',
                'source_id' => $user->id,
                'earned_at' => now(),
            ]);
        }

        \App\Models\ActivityLog::log(
            "Created new campaign user account for {$user->name} as {$this->selectedRole}",
            $user
        );

        session()->flash('message', 'Campaign user created successfully.');
        $this->closeDetails();
    }

    public function saveUser()
    {
        $this->validate();

        $user = User::find($this->selectedUserId);
        if ($user) {
            $path = $user->passport_path;
            if ($this->passport) {
                $path = $this->passport->store('passports', 'public');
            }

            $user->update([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'status' => $this->status,
                'passport_path' => $path,
                'occupation' => $this->occupation ?: null,
                'lga_id' => $this->lgaId ?: null,
                'ward_id' => $this->wardId ?: null,
                'polling_unit_id' => $this->pollingUnitId ?: null,
            ]);

            if ($this->selectedRole) {
                $user->syncRoles([$this->selectedRole]);
            }

            \App\Models\ActivityLog::log(
                "Updated campaign user configuration details for {$user->name}",
                $user
            );

            session()->flash('message', 'User details updated successfully.');
            $this->closeDetails();
        }
    }

    public function toggleUserStatus($id)
    {
        $user = User::find($id);
        if ($user) {
            $newStatus = $user->status === 'active' ? 'suspended' : 'active';
            $user->update(['status' => $newStatus]);
            
            \App\Models\ActivityLog::log(
                "Toggled account status of {$user->name} to {$newStatus}",
                $user
            );

            session()->flash('message', "User status updated to {$newStatus}.");
        }
    }

    public function render()
    {
        $query = User::query()->with(['roles', 'lga', 'ward']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        if ($this->lgaFilter) {
            $query->where('lga_id', $this->lgaFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->occupationFilter) {
            $query->where('occupation', $this->occupationFilter);
        }

        // Get unique occupations for filter dropdown
        $occupations = User::whereNotNull('occupation')
            ->where('occupation', '!=', '')
            ->distinct()
            ->orderBy('occupation', 'asc')
            ->pluck('occupation');

        return view('livewire.user-management', [
            'users' => $query->latest()->paginate(15),
            'lgas' => Lga::all(),
            'roles' => Role::all(),
            'occupations' => $occupations,
        ]);
    }
}
