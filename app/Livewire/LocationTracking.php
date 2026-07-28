<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Lga;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class LocationTracking extends Component
{
    public $search = '';
    public $roleFilter = '';
    public $lgaFilter = '';
    public $statusFilter = 'active'; // Default to active members

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'lgaFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function render()
    {
        // Fetch matching users who have locations
        $query = User::query()
            ->with(['roles', 'lga', 'latestAgentLocation'])
            ->whereHas('latestAgentLocation'); // Only display users who have at least one location recorded

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

        $members = $query->get()->map(function ($user) {
            $latestLoc = $user->latestAgentLocation;
            $lastPing = $latestLoc ? $latestLoc->recorded_at : null;
            
            // Online if pinged within the last 15 minutes
            $isOnline = $lastPing ? $lastPing->greaterThanOrEqualTo(now()->subMinutes(15)) : false;

            return [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'role' => $user->roles->first()?->name ?? 'Member',
                'lga' => $user->lga?->name ?? 'N/A',
                'lat' => (float) $latestLoc->lat,
                'lng' => (float) $latestLoc->lng,
                'accuracy' => $latestLoc->accuracy ? (float) $latestLoc->accuracy : null,
                'battery' => $latestLoc->battery_level,
                'last_ping' => $lastPing ? $lastPing->toIso8601String() : null,
                'last_ping_human' => $lastPing ? $lastPing->diffForHumans() : 'Never',
                'is_online' => $isOnline,
                'initials' => $user->initials(),
                'passport' => $user->passport_path ? asset($user->passport_path) : null,
            ];
        });

        return view('livewire.location-tracking', [
            'members' => $members,
            'lgas' => Lga::all(),
            'roles' => Role::all(),
        ]);
    }
}
