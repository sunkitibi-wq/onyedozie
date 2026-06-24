<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Lga;
use App\Models\Event;
use App\Models\PollingUnit;
use App\Models\Setting;
use Livewire\Component;

class CandidateDashboard extends Component
{
    public $token = null;
    public $isPublic = false;

    public function mount($token = null)
    {
        if ($token) {
            $this->isPublic = true;
            $this->token = $token;

            // Verify campaign share token (default or from Settings table)
            $validToken = Setting::get('candidate_share_token', 'dozie-campaign-token-2027');
            if ($token !== $validToken) {
                abort(403, 'Unauthorized public share link.');
            }
        } else {
            // For standard auth access, verify permissions
            if (!auth()->check() || (!auth()->user()->hasRole('Candidate Dashboard') && !auth()->user()->hasRole('Super Admin'))) {
                abort(403, 'Unauthorized access.');
            }
        }
    }

    public function render()
    {
        // Supporter/voter metrics
        $totalMembers = User::count();
        $targetMembers = 10000;
        $progressPercentage = min(100, round(($totalMembers / $targetMembers) * 100, 1));

        // Coverage calculations
        $totalPUs = PollingUnit::count() ?: 1;
        $coveredPUs = PollingUnit::whereHas('users')->count();
        $coveragePercentage = round(($coveredPUs / $totalPUs) * 100, 1);

        // LGA list with counts
        $lgaData = Lga::withCount('users')
            ->orderBy('users_count', 'desc')
            ->get();

        // Upcoming events
        $upcomingEvents = Event::where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->take(5)
            ->get();

        // Polling units with active supporter clusters for Leaflet mapping
        $activePUs = PollingUnit::whereHas('users')
            ->withCount('users')
            ->get()
            ->map(function ($pu) {
                return [
                    'name' => $pu->name,
                    'code' => $pu->code,
                    'lat' => (float) $pu->lat,
                    'lng' => (float) $pu->lng,
                    'count' => $pu->users_count,
                ];
            });

        // Shareable URL for the candidate view
        $shareToken = Setting::get('candidate_share_token', 'dozie-campaign-token-2027');
        $shareableUrl = route('candidate.public-share', ['token' => $shareToken]);

        return view('livewire.candidate-dashboard', [
            'totalMembers' => $totalMembers,
            'progressPercentage' => $progressPercentage,
            'coveragePercentage' => $coveragePercentage,
            'lgaData' => $lgaData,
            'upcomingEvents' => $upcomingEvents,
            'activePUs' => $activePUs,
            'shareableUrl' => $shareableUrl,
        ])->layout($this->isPublic ? 'layouts.blank' : 'layouts.app');
    }
}
