<?php

namespace App\Livewire;

use App\Models\Incident;
use App\Models\VoiceReport;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;

class ReportsManagement extends Component
{
    use WithPagination;

    public $activeTab = 'incidents'; // incidents, voice_reports, settings
    public $selectedIncidentId = null;
    public $incidentStatusUpdate = null;
    public $quickActionsEnabled = false;
    public $quickActionRecruitVisible = true;
    public $quickActionResultsVisible = true;
    public $quickActionIncidentVisible = true;
    public $quickActionVoiceVisible = true;
    public $quickActionMembersVisible = true;
    public $quickActionMobilizeVisible = true;

    public function mount()
    {
        $this->quickActionsEnabled = Setting::get('quick_actions_enabled', '0') === '1';
        $this->quickActionRecruitVisible = Setting::get('quick_action_recruit_visible', '1') === '1';
        $this->quickActionResultsVisible = Setting::get('quick_action_results_visible', '1') === '1';
        $this->quickActionIncidentVisible = Setting::get('quick_action_incident_visible', '1') === '1';
        $this->quickActionVoiceVisible = Setting::get('quick_action_voice_visible', '1') === '1';
        $this->quickActionMembersVisible = Setting::get('quick_action_members_visible', '1') === '1';
        $this->quickActionMobilizeVisible = Setting::get('quick_action_mobilize_visible', '1') === '1';
    }

    public function selectTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function toggleQuickActions()
    {
        $this->quickActionsEnabled = !$this->quickActionsEnabled;
        Setting::set('quick_actions_enabled', $this->quickActionsEnabled ? '1' : '0');
        session()->flash('message', 'Mobile App Quick Actions status updated successfully.');
    }

    public function toggleQuickActionVisibility($action)
    {
        $property = 'quickAction' . ucfirst($action) . 'Visible';
        $settingKey = 'quick_action_' . $action . '_visible';
        
        if (property_exists($this, $property)) {
            $this->$property = !$this->$property;
            Setting::set($settingKey, $this->$property ? '1' : '0');
            session()->flash('message', 'Mobile App Quick Link visibility updated successfully.');
        }
    }

    public function viewIncident($id)
    {
        $this->selectedIncidentId = $id;
        $incident = Incident::find($id);
        if ($incident) {
            $this->incidentStatusUpdate = $incident->status;
        }
    }

    public function closeIncidentModal()
    {
        $this->selectedIncidentId = null;
        $this->incidentStatusUpdate = null;
    }

    public function updateIncidentStatus($id, $status)
    {
        $incident = Incident::find($id);
        if ($incident) {
            $incident->update(['status' => $status]);
            $this->incidentStatusUpdate = $status;
            session()->flash('message', 'Incident status updated successfully.');
        }
    }

    public function render()
    {
        $incidents = Incident::with(['user.lga', 'user.ward'])
            ->latest()
            ->paginate(15, ['*'], 'incidentsPage');

        $voiceReports = VoiceReport::with(['user.lga', 'user.ward'])
            ->latest()
            ->paginate(15, ['*'], 'voicePage');

        $selectedIncident = $this->selectedIncidentId 
            ? Incident::with(['user.lga', 'user.ward', 'media'])->find($this->selectedIncidentId)
            : null;

        return view('livewire.reports-management', [
            'incidents' => $incidents,
            'voiceReports' => $voiceReports,
            'selectedIncident' => $selectedIncident,
            'totalIncidents' => Incident::count(),
            'pendingIncidents' => Incident::where('status', 'reported')->count(),
            'totalVoiceReports' => VoiceReport::count(),
        ]);
    }
}
