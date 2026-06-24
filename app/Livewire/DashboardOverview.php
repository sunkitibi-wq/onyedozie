<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Event;
use App\Models\Task;
use App\Models\Incident;
use App\Models\Result;
use App\Models\DoorKnock;
use Livewire\Component;

class DashboardOverview extends Component
{
    public $stats = [];
    public $activities = [];
    public $pendingUsers = [];
    public $recentIncidents = [];
    public $recentResults = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Stats
        $this->stats = [
            'total_members' => User::count(),
            'volunteers' => User::whereHas('roles', fn($q) => $q->where('name', 'Volunteer'))->count(),
            'coordinators' => User::whereHas('roles', fn($q) => $q->whereIn('name', ['LGA Coordinator', 'Ward Coordinator', 'Polling Unit Coordinator']))->count(),
            'active_agents' => User::where('last_seen_at', '>=', now()->subMinutes(30))->count(),
            'pending_approvals' => User::where('status', 'pending_approval')->count(),
            'upcoming_events' => Event::where('date', '>=', now())->count(),
            'total_incidents' => Incident::count(),
            'total_results' => Result::count(),
            'total_doors' => DoorKnock::count(),
        ];

        // Pending approvals list
        $this->pendingUsers = User::where('status', 'pending_approval')
            ->with(['lga', 'ward', 'pollingUnit'])
            ->latest()
            ->limit(10)
            ->get();

        // Recent incidents
        $this->recentIncidents = Incident::with(['user'])
            ->latest()
            ->limit(5)
            ->get();

        // Recent results
        $this->recentResults = Result::with(['user', 'pollingUnit', 'entries'])
            ->latest()
            ->limit(5)
            ->get();
    }

    public function approveUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->status = 'active';
            $user->save();
            
            // Log activity
            \App\Models\ActivityLog::log(
                "Approved user account for {$user->name} ({$user->phone})",
                $user
            );

            session()->flash('message', "User {$user->name} approved successfully.");
            $this->loadData();
        }
    }

    public function rejectUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->status = 'suspended';
            $user->save();

            \App\Models\ActivityLog::log(
                "Suspended/Rejected user account for {$user->name} ({$user->phone})",
                $user
            );

            session()->flash('message', "User {$user->name} application rejected.");
            $this->loadData();
        }
    }

    public function render()
    {
        return view('livewire.dashboard-overview');
    }
}
