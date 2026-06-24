<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RbacManagement extends Component
{
    public $selectedRoleId;
    public $roleName;
    public $permissionIds = []; // selected permissions for editing

    public function selectRole($id)
    {
        $role = Role::find($id);
        if ($role) {
            $this->selectedRoleId = $role->id;
            $this->roleName = $role->name;
            $this->permissionIds = $role->permissions->pluck('id')->toArray();
        }
    }

    public function closeRole()
    {
        $this->reset(['selectedRoleId', 'roleName', 'permissionIds']);
    }

    public function saveRolePermissions()
    {
        $role = Role::find($this->selectedRoleId);
        if ($role) {
            $permissions = Permission::whereIn('id', $this->permissionIds)->get();
            $role->syncPermissions($permissions);

            // Clear cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            session()->flash('message', "Role '{$role->name}' permissions synchronized successfully.");
            $this->closeRole();
        }
    }

    public function createRole()
    {
        $this->validate([
            'roleName' => 'required|string|unique:roles,name',
        ]);

        $role = Role::create([
            'name' => $this->roleName,
            'guard_name' => 'web'
        ]);

        \App\Models\ActivityLog::log(
            "Created campaign RBAC role: {$role->name}"
        );

        $this->reset(['roleName']);
        session()->flash('message', 'New role created successfully.');
    }

    public function render()
    {
        return view('livewire.rbac-management', [
            'roles' => Role::with(['permissions'])->get(),
            'permissions' => Permission::all(),
        ]);
    }
}
