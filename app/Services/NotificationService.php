<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Send notification to specific users
     */
    public function sendToUsers(array $userIds, \Illuminate\Notifications\Notification $notification): void
    {
        $users = User::whereIn('id', $userIds)->get();
        Notification::send($users, $notification);
    }

    /**
     * Send notification to users with specific role
     */
    public function sendToRole(string $role, \Illuminate\Notifications\Notification $notification): void
    {
        $users = User::role($role)->get();
        Notification::send($users, $notification);
    }

    /**
     * Send notification to users with specific permission
     */
    public function sendToPermission(string $permission, \Illuminate\Notifications\Notification $notification): void
    {
        $users = User::permission($permission)->get();
        Notification::send($users, $notification);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(User $user, int $notificationId): void
    {
        $user->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(User $user): void
    {
        $user->unreadNotifications->markAsRead();
    }

    /**
     * Get unread count
     */
    public function getUnreadCount(User $user): int
    {
        return $user->unreadNotifications()->count();
    }

    /**
     * Get recent notifications
     */
    public function getRecentNotifications(User $user, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $user->notifications()->latest()->limit($limit)->get();
    }

    /**
     * Delete notification
     */
    public function deleteNotification(User $user, int $notificationId): void
    {
        $user->notifications()->where('id', $notificationId)->delete();
    }

    /**
     * Clear all notifications
     */
    public function clearAllNotifications(User $user): void
    {
        $user->notifications()->delete();
    }
}