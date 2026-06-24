<?php

namespace App\Livewire;

use App\Models\Lga;
use App\Models\Ward;
use App\Models\PollingUnit;
use Livewire\Component;
use Livewire\WithPagination;

class LgaManagement extends Component
{
    use WithPagination;

    public $activeTab = 'lgas'; // lgas, wards, pus
    
    // Form fields
    public $lgaName;
    public $wardName, $wardLgaId;
    public $puName, $puCode, $puWardId, $puLat, $puLng, $puVoters;

    protected $rules = [
        'lgaName' => 'required|string|max:255',
        'wardName' => 'required|string|max:255',
        'wardLgaId' => 'required|exists:lgas,id',
        'puName' => 'required|string|max:255',
        'puCode' => 'required|string|unique:polling_units,code',
        'puWardId' => 'required|exists:wards,id',
    ];

    public function selectTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function createLga()
    {
        $this->validateOnly('lgaName');
        $lga = Lga::create([
            'name' => $this->lgaName,
            'state' => 'Anambra',
        ]);
        \App\Models\ActivityLog::log("Created LGA: {$lga->name}", $lga);
        $this->reset(['lgaName']);
        session()->flash('message', 'LGA created successfully.');
    }

    public function createWard()
    {
        $this->validateOnly('wardName');
        $this->validateOnly('wardLgaId');
        $ward = Ward::create([
            'name' => $this->wardName,
            'lga_id' => $this->wardLgaId,
        ]);
        \App\Models\ActivityLog::log("Created Ward: {$ward->name} in LGA {$ward->lga?->name}", $ward);
        $this->reset(['wardName', 'wardLgaId']);
        session()->flash('message', 'Ward created successfully.');
    }

    public function createPu()
    {
        $this->validateOnly('puName');
        $this->validateOnly('puCode');
        $this->validateOnly('puWardId');
        
        $pu = PollingUnit::create([
            'name' => $this->puName,
            'code' => $this->puCode,
            'ward_id' => $this->puWardId,
            'lat' => $this->puLat ?: null,
            'lng' => $this->puLng ?: null,
            'registered_voters' => $this->puVoters ?: 0,
        ]);
        \App\Models\ActivityLog::log("Created Polling Unit: {$pu->name} ({$pu->code})", $pu);
        
        $this->reset(['puName', 'puCode', 'puWardId', 'puLat', 'puLng', 'puVoters']);
        session()->flash('message', 'Polling Unit created successfully.');
    }

    public function deleteLga($id)
    {
        $lga = Lga::findOrFail($id);
        \App\Models\ActivityLog::log("Deleted LGA: {$lga->name}", $lga);
        $lga->delete();
        session()->flash('message', 'LGA and its associated wards & polling units deleted successfully.');
    }

    public function deleteWard($id)
    {
        $ward = Ward::findOrFail($id);
        \App\Models\ActivityLog::log("Deleted Ward: {$ward->name}", $ward);
        $ward->delete();
        session()->flash('message', 'Ward and its associated polling units deleted successfully.');
    }

    public function deletePu($id)
    {
        $pu = PollingUnit::findOrFail($id);
        \App\Models\ActivityLog::log("Deleted Polling Unit: {$pu->name} ({$pu->code})", $pu);
        $pu->delete();
        session()->flash('message', 'Polling Unit deleted successfully.');
    }

    public function render()
    {
        return view('livewire.lga-management', [
            'lgas' => Lga::withCount(['wards', 'users'])->paginate(10, ['*'], 'lgasPage'),
            'wards' => Ward::with(['lga'])->withCount(['pollingUnits'])->paginate(15, ['*'], 'wardsPage'),
            'pus' => PollingUnit::with(['ward.lga'])->paginate(20, ['*'], 'pusPage'),
            'allLgas' => Lga::all(),
            'allWards' => Ward::all(),
        ]);
    }
}
