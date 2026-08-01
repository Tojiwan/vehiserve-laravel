<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.user')]
class NotificationsIndex extends Component
{
    use WithPagination;

    public $showRead = true;
    public $filter = 'all'; // all, unread, read

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function markAsRead($notificationId): void
    {
        $notification = auth()->user()->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function deleteNotification($notificationId): void
    {
        $notification = auth()->user()->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->delete();
        }
    }

    public function render()
    {
        $query = auth()->user()->notifications()->latest();

        if ($this->filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($this->filter === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->paginate(15);

        return view('livewire.user.notifications-index', [
            'notifications' => $notifications,
        ]);
    }
}