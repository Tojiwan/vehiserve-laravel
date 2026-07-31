<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class RequestApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $requestType,
        public int $requestId,
        public string $approverName,
        public string $approverRole,
        public ?string $nextStep = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        $message = "Your {$this->requestType} #{$this->requestId} was approved by {$this->approverName} ({$this->approverRole})";
        
        if ($this->nextStep) {
            $message .= ". Next step: {$this->nextStep}";
        }

        return [
            'request_type' => $this->requestType,
            'request_id' => $this->requestId,
            'approver_name' => $this->approverName,
            'approver_role' => $this->approverRole,
            'next_step' => $this->nextStep,
            'message' => $message,
            'action_url' => route("{$this->requestType}.show", $this->requestId),
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}