<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Notifications\DatabaseNotification;

class NotificationBell extends Component
{
    public int $unreadCount = 0;
    public bool $isOpen = false;
    public $notifications = [];

    protected $listeners = ['notificationsRead' => 'refreshNotifications'];

    public function mount(): void
    {
        $this->refreshNotifications();
    }

    public function refreshNotifications(): void
    {
        $user = auth()->user();
        if (!$user) return;

        $this->unreadCount = $user->unreadNotifications()->count();
        $this->notifications = $user->notifications()
            ->latest()
            ->take(8)
            ->get()
            ->map(fn ($n) => [
                'id'        => $n->id,
                'title'     => $n->data['title'] ?? 'Notification',
                'body'      => $n->data['body'] ?? '',
                'type'      => $n->data['type'] ?? 'general',
                'read'      => !is_null($n->read_at),
                'time_ago'  => $n->created_at->diffForHumans(),
            ])
            ->toArray();
    }

    public function toggleOpen(): void
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen) {
            $this->refreshNotifications();
        }
    }

    public function markAsRead(string $id): void
    {
        $user = auth()->user();
        $notification = $user->notifications()->find($id);
        $notification?->markAsRead();
        $this->refreshNotifications();
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->refreshNotifications();
        $this->dispatch('notificationsRead');
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
