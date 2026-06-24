<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogs extends Component
{
    use WithPagination;

    public $search = '';
    public $logNameFilter = '';
    public $selectedLogId = null;

    public function selectLog($id)
    {
        $this->selectedLogId = $id;
    }

    public function closeDetails()
    {
        $this->selectedLogId = null;
    }

    public function render()
    {
        $query = ActivityLog::query()
            ->with(['causer', 'subject']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhere('log_name', 'like', '%' . $this->search . '%')
                  ->orWhereHasMorph('causer', ['App\Models\User'], function ($subQ) {
                      $subQ->where('name', 'like', '%' . $this->search . '%')
                           ->orWhere('phone', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->logNameFilter) {
            $query->where('log_name', $this->logNameFilter);
        }

        $logNames = ActivityLog::query()
            ->whereNotNull('log_name')
            ->distinct()
            ->pluck('log_name');

        $selectedLog = $this->selectedLogId ? ActivityLog::with(['causer', 'subject'])->find($this->selectedLogId) : null;

        return view('livewire.activity-logs', [
            'logs' => $query->latest()->paginate(15),
            'logNames' => $logNames,
            'selectedLog' => $selectedLog,
        ]);
    }
}
