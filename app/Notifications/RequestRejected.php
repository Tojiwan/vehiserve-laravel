<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class RequestRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $requestType,
        public int $requestId,
        public string $rejectorName,
        public string $rejectorRole,
        public string $reason,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'request_type' => $this->requestType,
            'request_id' => $this->requestId,
            'rejector_name' => $this->rejectorName,
            'rejector_role' => $this->rejectorRole,
            'reason' => $this->reason,
            'message' => "Your {$this->requestType} #{$this->requestId} was rejected by {$this->rejectorName} ({$this->rejectorRole}): {$this->reason}",
            'action_url' => route("{$this->requestType}.show", $this->requestId),
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}