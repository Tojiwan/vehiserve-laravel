<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class ProgressTracker extends Component
{
    public $steps = [];
    public $currentStep = 0;
    public $completedSteps = [];
    public $rejectedStep = null;
    public $cancelled = false;

    public function render()
    {
        return view('livewire.shared.progress-tracker');
    }
}