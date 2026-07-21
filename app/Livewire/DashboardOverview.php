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
    public $topRecruiters = [];
    public $monthlyTopRecruiters = [];
    public $recruitmentChartData = [];
    
    // Current user's stats
    public $myPoints = 0;
    public $myRecruits = 0;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');

        // Current user stats
        $this->myPoints = $user->points;
        $this->myRecruits = \App\Models\LeaderboardPoint::where('user_id', $user->id)
            ->where('source_type', 'recruitment')
            ->count();

        if ($isSuperAdmin) {
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
                'total_recruits' => \App\Models\LeaderboardPoint::where('source_type', 'recruitment')->count(),
                'monthly_recruits' => \App\Models\LeaderboardPoint::where('source_type', 'recruitment')
                    ->whereMonth('earned_at', now()->month)
                    ->whereYear('earned_at', now()->year)
                    ->count(),
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

            // Top Recruiters Overall
            $this->topRecruiters = User::whereHas('leaderboardPoints', function ($q) {
                    $q->where('source_type', 'recruitment');
                })
                ->withCount(['leaderboardPoints as recruits_count' => function ($query) {
                    $query->where('source_type', 'recruitment');
                }])
                ->orderByDesc('recruits_count')
                ->limit(5)
                ->get();

            // Top Recruiters This Month
            $this->monthlyTopRecruiters = User::whereHas('leaderboardPoints', function ($q) {
                    $q->where('source_type', 'recruitment')
                      ->whereMonth('earned_at', now()->month)
                      ->whereYear('earned_at', now()->year);
                })
                ->withCount(['leaderboardPoints as recruits_count' => function ($query) {
                    $query->where('source_type', 'recruitment')
                          ->whereMonth('earned_at', now()->month)
                          ->whereYear('earned_at', now()->year);
                }])
                ->orderByDesc('recruits_count')
                ->limit(5)
                ->get();

            // Recruitment Chart Data (Last 7 Days)
            $this->recruitmentChartData = collect(range(6, 0))->map(function ($daysAgo) {
                $date = now()->subDays($daysAgo);
                $count = \App\Models\LeaderboardPoint::query()
                    ->where('source_type', 'recruitment')
                    ->whereDate('earned_at', $date->toDateString())
                    ->count();
                return [
                    'label' => $date->format('D'), // Mon, Tue etc.
                    'full_date' => $date->format('M d, Y'),
                    'count' => $count
                ];
            })->toArray();
        } else {
            // General stats should be empty
            $this->stats = [];
            $this->pendingUsers = collect();
            $this->topRecruiters = collect();
            $this->monthlyTopRecruiters = collect();

            // Only their own incidents
            $this->recentIncidents = Incident::where('user_id', $user->id)
                ->with(['user'])
                ->latest()
                ->limit(5)
                ->get();

            // Only their own results
            $this->recentResults = Result::where('user_id', $user->id)
                ->with(['user', 'pollingUnit', 'entries'])
                ->latest()
                ->limit(5)
                ->get();
        }
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
