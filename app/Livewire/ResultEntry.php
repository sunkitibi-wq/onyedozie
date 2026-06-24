<?php

namespace App\Livewire;

use App\Models\Result;
use App\Models\ResultEntry as ResultEntryModel;
use App\Models\Incident;
use App\Models\PollingUnit;
use Livewire\Component;
use Livewire\WithPagination;

class ResultEntry extends Component
{
    use WithPagination;

    public $activeTab = 'scoreboard'; // scoreboard, verification, incidents
    
    // Result editing/verification fields
    public $selectedResultId;
    public $votesData = []; // party => votes

    // Incident management fields
    public $filterUrgency = '';
    public $filterIncidentStatus = '';

    public function selectTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function selectResultForVerification($id)
    {
        $result = Result::with(['entries', 'pollingUnit'])->find($id);
        if ($result) {
            $this->selectedResultId = $result->id;
            $this->votesData = [];
            foreach ($result->entries as $entry) {
                $this->votesData[$entry->id] = $entry->votes;
            }
        }
    }

    public function closeVerification()
    {
        $this->reset(['selectedResultId', 'votesData']);
    }

    public function verifyResult()
    {
        $result = Result::find($this->selectedResultId);
        if ($result) {
            // Update individual votes if edited by admin
            foreach ($this->votesData as $entryId => $votes) {
                $entry = ResultEntryModel::find($entryId);
                if ($entry) {
                    $entry->update(['votes' => $votes]);
                }
            }

            $result->update([
                'verified_at' => now(),
                'verified_by' => auth()->id(),
            ]);

            // Award points to agent who uploaded: 10 points for a verified EC8A result submission
            \App\Models\LeaderboardPoint::create([
                'user_id' => $result->user_id,
                'points' => 10,
                'source_type' => 'result',
                'source_id' => $result->id,
            ]);

            \App\Models\ActivityLog::log(
                "Verified EC8A result for PU: {$result->pollingUnit?->name}",
                $result
            );

            session()->flash('message', 'Result sheet verified successfully. Points awarded to field coordinator.');
            $this->closeVerification();
        }
    }

    public function updateIncidentStatus($incidentId, $status)
    {
        $incident = Incident::find($incidentId);
        if ($incident) {
            $incident->update(['status' => $status]);

            \App\Models\ActivityLog::log(
                "Updated status of incident #{$incident->id} to {$status}",
                $incident
            );

            session()->flash('message', "Incident status updated to {$status}.");
        }
    }

    public function render()
    {
        // Calculate party totals
        $scoreboard = ResultEntryModel::select('party')
            ->selectRaw('SUM(votes) as total_votes')
            ->whereHas('result', function ($q) {
                $q->whereNotNull('verified_at');
            })
            ->groupBy('party')
            ->orderByDesc('total_votes')
            ->get();

        // PU Stats
        $totalPUs = PollingUnit::count();
        $reportingPUs = Result::distinct('polling_unit_id')->count();

        // Incident Query
        $incidentQuery = Incident::with(['user', 'media']);
        if ($this->filterUrgency) {
            $incidentQuery->where('urgency', $this->filterUrgency);
        }
        if ($this->filterIncidentStatus) {
            $incidentQuery->where('status', $this->filterIncidentStatus);
        }

        return view('livewire.result-entry', [
            'scoreboard' => $scoreboard,
            'reportingStats' => [
                'total' => $totalPUs,
                'reporting' => $reportingPUs,
                'percent' => $totalPUs > 0 ? round(($reportingPUs / $totalPUs) * 100, 1) : 0,
            ],
            'pendingVerification' => Result::whereNull('verified_at')
                ->with(['user', 'pollingUnit', 'entries'])
                ->paginate(10, ['*'], 'verifyPage'),
            'incidents' => $incidentQuery->latest()->paginate(15, ['*'], 'incidentPage'),
            'allResults' => Result::with(['pollingUnit', 'user', 'entries'])
                ->whereNotNull('verified_at')
                ->latest()
                ->paginate(15, ['*'], 'allResultsPage'),
        ]);
    }
}
