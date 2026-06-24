<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage users',
            'approve coordinators',
            'create events',
            'track location',
            'upload results',
            'verify results',
            'report incidents',
            'resolve incidents',
            'send broadcasts',
            'view analytics',
            'view candidate dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign existing permissions
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo([
            'manage users',
            'approve coordinators',
            'create events',
            'track location',
            'verify results',
            'resolve incidents',
            'send broadcasts',
            'view analytics',
        ]);

        $candidate = Role::create(['name' => 'Candidate Dashboard']);
        $candidate->givePermissionTo([
            'view analytics',
            'view candidate dashboard',
        ]);

        $lgaCoordinator = Role::create(['name' => 'LGA Coordinator']);
        $lgaCoordinator->givePermissionTo([
            'create events',
            'track location',
            'upload results',
            'report incidents',
        ]);

        $wardCoordinator = Role::create(['name' => 'Ward Coordinator']);
        $wardCoordinator->givePermissionTo([
            'create events',
            'upload results',
            'report incidents',
        ]);

        $puCoordinator = Role::create(['name' => 'Polling Unit Coordinator']);
        $puCoordinator->givePermissionTo([
            'upload results',
            'report incidents',
        ]);

        $volunteer = Role::create(['name' => 'Volunteer']);
        $volunteer->givePermissionTo([
            'report incidents',
        ]);
    }
}
