<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class NotificationsPage extends Component
{
    use WithPagination;

    public string $typeFilter = '';

    protected $queryString = [
        'typeFilter' => ['except' => ''],
    ];

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function markAsRead(string $id): void
    {
        $user = auth()->user();
        $notification = $user->notifications()->find($id);
        $notification?->markAsRead();
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function delete(string $id): void
    {
        auth()->user()->notifications()->where('id', $id)->delete();
    }

    public function render()
    {
        $query = auth()->user()->notifications()->latest();

        if ($this->typeFilter) {
            $query->whereJsonContains('data->type', $this->typeFilter);
        }

        return view('livewire.notifications-page', [
            'notifications' => $query->paginate(20),
            'unreadCount'   => auth()->user()->unreadNotifications()->count(),
        ]);
    }
}
