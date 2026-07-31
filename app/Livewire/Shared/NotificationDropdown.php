<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class NotificationDropdown extends Component
{
    public $show = false;
    public $notifications = [];
    public $unreadCount = 0;
    
    // This property is used to resolve the {id} placeholder in the event listener
    public $id = 0;

    public function mount(): void
    {
        if (Auth::check()) {
            $this->id = Auth::id();
        }
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        if (Auth::check()) {
            $this->notifications = Auth::user()->notifications()->latest()->take(10)->get();
            $this->unreadCount = Auth::user()->unreadNotifications()->count();
        }
    }

    public function toggle(): void
    {
        $this->show = !$this->show;
        if ($this->show) {
            $this->loadNotifications();
        }
    }

    public function markAsRead($notificationId): void
    {
        if (Auth::check()) {
            $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
            if ($notification) {
                $notification->markAsRead();
                $this->loadNotifications();
            }
        }
    }

    public function markAllAsRead(): void
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
            $this->loadNotifications();
        }
    }

    #[On('echo:notifications.{id},NotificationCreated')]
    public function handleNotificationCreated($id): void
    {
        if (Auth::check() && Auth::id() == $id) {
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.shared.notification-dropdown');
    }
}