<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use Livewire\Attributes\On;

class ConfirmModal extends Component
{
    public $show = false;
    public $title = 'Confirm Action';
    public $message = 'Are you sure you want to proceed?';
    public $confirmText = 'Confirm';
    public $cancelText = 'Cancel';
    public $confirmAction = '';
    public $confirmParams = [];
    public $variant = 'danger'; // danger, primary, warning

    #[On('confirm-modal:show')]
    public function showModal(array $data)
    {
        $this->title = $data['title'] ?? $this->title;
        $this->message = $data['message'] ?? $this->message;
        $this->confirmText = $data['confirmText'] ?? $this->confirmText;
        $this->cancelText = $data['cancelText'] ?? $this->cancelText;
        $this->confirmAction = $data['confirmAction'] ?? '';
        $this->confirmParams = $data['confirmParams'] ?? [];
        $this->variant = $data['variant'] ?? $this->variant;
        $this->show = true;
    }

    public function confirm()
    {
        if ($this->confirmAction) {
            $this->dispatch($this->confirmAction, ...$this->confirmParams);
        }
        $this->close();
    }

    public function close()
    {
        $this->show = false;
        $this->reset(['title', 'message', 'confirmText', 'cancelText', 'confirmAction', 'confirmParams', 'variant']);
    }

    public function render()
    {
        return view('livewire.shared.confirm-modal');
    }
}