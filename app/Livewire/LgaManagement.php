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
    public $search = '';
    
    // Form fields
    public $lgaName;
    public $wardName, $wardLgaId;
    public $puName, $puCode, $puWardId, $puLat, $puLng, $puVoters;

    // Editing fields
    public $editingLgaId, $editingLgaName;
    public $editingWardId, $editingWardName, $editingWardLgaId;
    public $editingPuId, $editingPuName, $editingPuCode, $editingPuWardId, $editingPuLat, $editingPuLng, $editingPuVoters;

    protected $rules = [
        'lgaName' => 'required|string|max:255',
        'wardName' => 'required|string|max:255',
        'wardLgaId' => 'required|exists:lgas,id',
        'puName' => 'required|string|max:255',
        'puCode' => 'required|string|unique:polling_units,code',
        'puWardId' => 'required|exists:wards,id',
    ];

    private function authorizeSuperAdmin()
    {
        if (!auth()->user() || !auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Unauthorized.');
        }
    }

    public function selectTab($tab)
    {
        $this->activeTab = $tab;
        $this->cancelEditLga();
        $this->cancelEditWard();
        $this->cancelEditPu();
        $this->resetPage('lgasPage');
        $this->resetPage('wardsPage');
        $this->resetPage('pusPage');
    }

    public function updatingSearch()
    {
        $this->resetPage('lgasPage');
        $this->resetPage('wardsPage');
        $this->resetPage('pusPage');
    }

    // LGA Edit Methods
    public function editLga($id)
    {
        $this->authorizeSuperAdmin();
        $lga = Lga::findOrFail($id);
        $this->editingLgaId = $lga->id;
        $this->editingLgaName = $lga->name;
    }

    public function cancelEditLga()
    {
        $this->reset(['editingLgaId', 'editingLgaName']);
        $this->resetErrorBag();
    }

    public function updateLga()
    {
        $this->authorizeSuperAdmin();
        $this->validate([
            'editingLgaName' => 'required|string|max:255',
        ]);

        $lga = Lga::findOrFail($this->editingLgaId);
        $oldName = $lga->name;
        $lga->update([
            'name' => $this->editingLgaName,
        ]);

        \App\Models\ActivityLog::log("Updated LGA: {$oldName} to {$lga->name}", $lga);
        $this->cancelEditLga();
        session()->flash('message', 'LGA updated successfully.');
    }

    // Ward Edit Methods
    public function editWard($id)
    {
        $this->authorizeSuperAdmin();
        $ward = Ward::findOrFail($id);
        $this->editingWardId = $ward->id;
        $this->editingWardName = $ward->name;
        $this->editingWardLgaId = $ward->lga_id;
    }

    public function cancelEditWard()
    {
        $this->reset(['editingWardId', 'editingWardName', 'editingWardLgaId']);
        $this->resetErrorBag();
    }

    public function updateWard()
    {
        $this->authorizeSuperAdmin();
        $this->validate([
            'editingWardName' => 'required|string|max:255',
            'editingWardLgaId' => 'required|exists:lgas,id',
        ]);

        $ward = Ward::findOrFail($this->editingWardId);
        $oldName = $ward->name;
        $ward->update([
            'name' => $this->editingWardName,
            'lga_id' => $this->editingWardLgaId,
        ]);

        \App\Models\ActivityLog::log("Updated Ward: {$oldName} to {$ward->name}", $ward);
        $this->cancelEditWard();
        session()->flash('message', 'Ward updated successfully.');
    }

    // Polling Unit Edit Methods
    public function editPu($id)
    {
        $this->authorizeSuperAdmin();
        $pu = PollingUnit::findOrFail($id);
        $this->editingPuId = $pu->id;
        $this->editingPuName = $pu->name;
        $this->editingPuCode = $pu->code;
        $this->editingPuWardId = $pu->ward_id;
        $this->editingPuLat = $pu->lat;
        $this->editingPuLng = $pu->lng;
        $this->editingPuVoters = $pu->registered_voters;
    }

    public function cancelEditPu()
    {
        $this->reset([
            'editingPuId',
            'editingPuName',
            'editingPuCode',
            'editingPuWardId',
            'editingPuLat',
            'editingPuLng',
            'editingPuVoters',
        ]);
        $this->resetErrorBag();
    }

    public function updatePu()
    {
        $this->authorizeSuperAdmin();
        $this->validate([
            'editingPuName' => 'required|string|max:255',
            'editingPuCode' => 'required|string|unique:polling_units,code,' . $this->editingPuId,
            'editingPuWardId' => 'required|exists:wards,id',
        ]);

        $pu = PollingUnit::findOrFail($this->editingPuId);
        $oldName = $pu->name;
        $pu->update([
            'name' => $this->editingPuName,
            'code' => $this->editingPuCode,
            'ward_id' => $this->editingPuWardId,
            'lat' => $this->editingPuLat ?: null,
            'lng' => $this->editingPuLng ?: null,
            'registered_voters' => $this->editingPuVoters ?: 0,
        ]);

        \App\Models\ActivityLog::log("Updated Polling Unit: {$oldName} to {$pu->name} ({$pu->code})", $pu);
        $this->cancelEditPu();
        session()->flash('message', 'Polling Unit updated successfully.');
    }

    public function createLga()
    {
        $this->authorizeSuperAdmin();
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
        $this->authorizeSuperAdmin();
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
        $this->authorizeSuperAdmin();
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
        $this->authorizeSuperAdmin();
        $lga = Lga::findOrFail($id);
        \App\Models\ActivityLog::log("Deleted LGA: {$lga->name}", $lga);
        $lga->delete();
        session()->flash('message', 'LGA and its associated wards & polling units deleted successfully.');
    }

    public function deleteWard($id)
    {
        $this->authorizeSuperAdmin();
        $ward = Ward::findOrFail($id);
        \App\Models\ActivityLog::log("Deleted Ward: {$ward->name}", $ward);
        $ward->delete();
        session()->flash('message', 'Ward and its associated polling units deleted successfully.');
    }

    public function deletePu($id)
    {
        $this->authorizeSuperAdmin();
        $pu = PollingUnit::findOrFail($id);
        \App\Models\ActivityLog::log("Deleted Polling Unit: {$pu->name} ({$pu->code})", $pu);
        $pu->delete();
        session()->flash('message', 'Polling Unit deleted successfully.');
    }

    public function render()
    {
        $lgasQuery = Lga::withCount(['wards', 'users']);
        if ($this->search) {
            $lgasQuery->where('name', 'like', '%' . $this->search . '%');
        }

        $wardsQuery = Ward::with(['lga'])->withCount(['pollingUnits']);
        if ($this->search) {
            $wardsQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('lga', function ($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $pusQuery = PollingUnit::with(['ward.lga']);
        if ($this->search) {
            $pusQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhereHas('ward', function ($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('lga', function ($q3) {
                            $q3->where('name', 'like', '%' . $this->search . '%');
                        });
                  });
            });
        }

        return view('livewire.lga-management', [
            'lgas' => $lgasQuery->paginate(10, ['*'], 'lgasPage'),
            'wards' => $wardsQuery->paginate(15, ['*'], 'wardsPage'),
            'pus' => $pusQuery->paginate(20, ['*'], 'pusPage'),
            'allLgas' => Lga::all(),
            'allWards' => Ward::all(),
        ]);
    }
}
