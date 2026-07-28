<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\PollingUnit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;

class UsersImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Skip if essential fields are missing
            if (empty($row['name']) || empty($row['phone'])) {
                continue;
            }

            // Check for existing phone number to avoid duplicates
            if (User::where('phone', $row['phone'])->exists()) {
                continue;
            }

            // Resolve relationships based on name (case-insensitive)
            $lgaId = null;
            if (!empty($row['lga'])) {
                $lga = Lga::where('name', 'like', '%' . $row['lga'] . '%')->first();
                $lgaId = $lga ? $lga->id : null;
            }

            $wardId = null;
            if (!empty($row['ward']) && $lgaId) {
                $ward = Ward::where('lga_id', $lgaId)->where('name', 'like', '%' . $row['ward'] . '%')->first();
                $wardId = $ward ? $ward->id : null;
            }

            $pollingUnitId = null;
            if (!empty($row['polling_unit']) && $wardId) {
                $pu = PollingUnit::where('ward_id', $wardId)->where('name', 'like', '%' . $row['polling_unit'] . '%')->first();
                $pollingUnitId = $pu ? $pu->id : null;
            }

            // Create user
            $user = User::create([
                'name' => $row['name'],
                'phone' => $row['phone'],
                'email' => !empty($row['email']) ? $row['email'] : null,
                'password' => Hash::make('password123'),
                'occupation' => !empty($row['occupation']) ? $row['occupation'] : null,
                'status' => 'active',
                'lga_id' => $lgaId,
                'ward_id' => $wardId,
                'polling_unit_id' => $pollingUnitId,
            ]);

            // Assign role
            if (!empty($row['role'])) {
                $role = Role::where('name', 'like', '%' . $row['role'] . '%')->first();
                if ($role) {
                    $user->syncRoles([$role->name]);
                }
            }
            
            // Log creation
            \App\Models\ActivityLog::log(
                "Created new campaign user account for {$user->name} via Bulk Upload",
                $user
            );
        }
    }
}
