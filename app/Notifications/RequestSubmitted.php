<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class RequestSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $requestType, // 'Vehicle Request' or 'Travel Request'
        public int $requestId,
        public string $requesterName,
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
            'requester_name' => $this->requesterName,
            'message' => "New {$this->requestType} #{$this->requestId} submitted by {$this->requesterName}",
            'action_url' => route("{$this->requestType}.show", $this->requestId),
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'request_type' => $this->requestType,
            'request_id' => $this->requestId,
            'requester_name' => $this->requesterName,
            'message' => "New {$this->requestType} #{$this->requestId} submitted by {$this->requesterName}",
        ]);
    }
}