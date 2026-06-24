<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\TaskCompletion;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class TaskManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    // Create Form Fields
    public $isCreating = false;
    public $title = '';
    public $description = '';
    public $deadline = '';
    public $assignedToRole = '';
    public $lgaId = '';
    public $wardId = '';
    public $assignedUserId = '';

    // Detail Modal / Drawer Fields
    public $selectedTaskId;
    public $selectedTaskCompletions = [];

    // Dependents
    public $wards = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'deadline' => 'required|date',
        'assignedToRole' => 'nullable|string',
        'lgaId' => 'nullable|exists:lgas,id',
        'wardId' => 'nullable|exists:wards,id',
        'assignedUserId' => 'nullable|exists:users,id',
    ];

    public function updatedLgaId($value)
    {
        $this->wards = $value ? Ward::where('lga_id', $value)->get() : [];
        $this->wardId = '';
    }

    public function startCreate()
    {
        $this->closeDetails();
        $this->isCreating = true;
    }

    public function closeDetails()
    {
        $this->reset([
            'isCreating',
            'title',
            'description',
            'deadline',
            'assignedToRole',
            'lgaId',
            'wardId',
            'assignedUserId',
            'selectedTaskId',
            'selectedTaskCompletions',
            'wards'
        ]);
        $this->resetErrorBag();
    }

    public function createTask()
    {
        $this->validate();

        $task = Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'status' => 'pending',
            'assigned_to_role' => $this->assignedToRole ?: null,
            'lga_id' => $this->lgaId ?: null,
            'ward_id' => $this->wardId ?: null,
            'assigned_user_id' => $this->assignedUserId ?: null,
            'created_by' => auth()->id(),
        ]);

        \App\Models\ActivityLog::log(
            "Created a new campaign task: {$task->title}",
            $task
        );

        // Dispatch task-assigned notifications
        $notification = new \App\Notifications\GeneralCampaignNotification(
            'New Task Assigned: ' . $task->title,
            $task->description,
            'task_assigned',
            ['task_id' => $task->id, 'url' => route('tasks')]
        );

        if ($task->assigned_user_id) {
            // Notify the specific user
            $assignee = User::find($task->assigned_user_id);
            $assignee?->notify($notification);
        } elseif ($task->assigned_to_role) {
            // Notify all users with that role
            User::role($task->assigned_to_role)->each(fn ($u) => $u->notify($notification));
        } else {
            // Broadcast to all active members
            User::where('status', 'active')->each(fn ($u) => $u->notify($notification));
        }

        session()->flash('message', 'Campaign task created and notifications dispatched successfully.');
        $this->closeDetails();
    }

    public function viewCompletions($id)
    {
        $this->closeDetails();
        $this->selectedTaskId = $id;
        $this->selectedTaskCompletions = TaskCompletion::where('task_id', $id)
            ->with('user')
            ->latest()
            ->get();
    }

    public function verifyTask($id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->update([
                'status' => 'verified',
                'verified_by' => auth()->id(),
            ]);

            \App\Models\ActivityLog::log(
                "Verified and closed campaign task: {$task->title}",
                $task
            );

            // Notify all agents who completed this task
            $completors = \App\Models\TaskCompletion::where('task_id', $task->id)
                ->with('user')->get()->pluck('user')->unique('id');

            $notification = new \App\Notifications\GeneralCampaignNotification(
                'Task Verified: ' . $task->title,
                'Your submission for this task has been reviewed and verified by the campaign team. Great work!',
                'task_verified',
                ['task_id' => $task->id]
            );
            $completors->each(fn ($u) => $u?->notify($notification));

            session()->flash('message', 'Task verified, closed, and agents notified.');
            if ($this->selectedTaskId == $id) {
                $this->viewCompletions($id);
            }
        }
    }

    public function render()
    {
        $query = Task::query()->with(['assignee', 'lga', 'ward', 'creator', 'verifier']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.task-manager', [
            'tasks' => $query->latest()->paginate(15),
            'lgas' => Lga::all(),
            'roles' => Role::all(),
            'users' => User::where('status', 'active')->orderBy('name', 'asc')->get(),
        ]);
    }
}
