<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\LeaderboardPoint;
use Livewire\Component;
use Livewire\WithPagination;

class RecruitmentStatistics extends Component
{
    use WithPagination;

    public $search = '';
    public $timeframe = 'all'; // all, month, today

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTimeframe()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Base query for recruiters
        $query = User::whereHas('leaderboardPoints', function ($q) {
            $q->where('source_type', 'recruitment');
            
            if ($this->timeframe === 'month') {
                $q->whereMonth('earned_at', now()->month)
                  ->whereYear('earned_at', now()->year);
            } elseif ($this->timeframe === 'today') {
                $q->whereDate('earned_at', now()->toDateString());
            }
        });

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
        }

        $recruiters = $query->withCount(['leaderboardPoints as recruits_count' => function ($q) {
            $q->where('source_type', 'recruitment');
            
            if ($this->timeframe === 'month') {
                $q->whereMonth('earned_at', now()->month)
                  ->whereYear('earned_at', now()->year);
            } elseif ($this->timeframe === 'today') {
                $q->whereDate('earned_at', now()->toDateString());
            }
        }])
        ->orderByDesc('recruits_count')
        ->paginate(15);

        // Overall stats
        $totalRecruits = LeaderboardPoint::where('source_type', 'recruitment')->count();
        $monthlyRecruits = LeaderboardPoint::where('source_type', 'recruitment')
            ->whereMonth('earned_at', now()->month)
            ->whereYear('earned_at', now()->year)
            ->count();
        $todayRecruits = LeaderboardPoint::where('source_type', 'recruitment')
            ->whereDate('earned_at', now()->toDateString())
            ->count();

        return view('livewire.recruitment-statistics', [
            'recruiters' => $recruiters,
            'stats' => [
                'total' => $totalRecruits,
                'monthly' => $monthlyRecruits,
                'today' => $todayRecruits,
            ]
        ]);
    }
}
