<?php

namespace App\Livewire;

use App\Models\DoorKnock;
use App\Models\Lga;
use Livewire\Component;
use Livewire\WithPagination;

class CanvassingTracker extends Component
{
    use WithPagination;

    public $search = '';
    public $outcomeFilter = '';
    public $lgaFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'outcomeFilter' => ['except' => ''],
        'lgaFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingOutcomeFilter()
    {
        $this->resetPage();
    }

    public function updatingLgaFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = DoorKnock::query()->with(['user.lga']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('voter_name', 'like', '%' . $this->search . '%')
                  ->orWhere('address_description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->outcomeFilter) {
            $query->where('outcome', $this->outcomeFilter);
        }

        if ($this->lgaFilter) {
            $query->whereHas('user', function ($uq) {
                $uq->where('lga_id', $this->lgaFilter);
            });
        }

        // Calculate statistics based on the current filters (except pagination)
        $statsQuery = clone $query;
        $totalVisits = $statsQuery->count();
        $supportive = (clone $statsQuery)->where('outcome', 'supportive')->count();
        $neutral = (clone $statsQuery)->where('outcome', 'neutral')->count();
        $opposed = (clone $statsQuery)->where('outcome', 'opposed')->count();
        $notHome = (clone $statsQuery)->where('outcome', 'not home')->count();

        // Coordinates for the Leaflet JS map (limit to 100 markers for performance)
        $markers = (clone $statsQuery)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->latest('visited_at')
            ->take(100)
            ->get()
            ->map(function ($knock) {
                return [
                    'lat' => (float) $knock->lat,
                    'lng' => (float) $knock->lng,
                    'voter_name' => $knock->voter_name ?: 'Unknown Occupant',
                    'agent_name' => $knock->user->name,
                    'outcome' => $knock->outcome,
                    'address' => $knock->address_description ?: 'No address description',
                    'notes' => $knock->notes ?: '',
                ];
            });

        $this->dispatch('visitsUpdated', markers: $markers);

        return view('livewire.canvassing-tracker', [
            'visits' => $query->latest('visited_at')->paginate(15),
            'lgas' => Lga::all(),
            'totalVisits' => $totalVisits,
            'supportiveCount' => $supportive,
            'neutralCount' => $neutral,
            'opposedCount' => $opposed,
            'notHomeCount' => $notHome,
            'markers' => $markers,
        ]);
    }
}
