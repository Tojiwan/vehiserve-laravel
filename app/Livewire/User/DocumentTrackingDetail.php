<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\VehicleRequest;
use App\Models\TravelRequest;
use App\Enums\VehicleRequestStatus;
use App\Enums\TravelRequestStatus;

#[Layout('layouts.user')]
class DocumentTrackingDetail extends Component
{
    public string $type = 'vehicle';
    public int $id = 0;

    public $request = null;
    public $approvals = [];
    public $documents = [];

    public function mount(string $type, int $id): void
    {
        $this->type = $type;
        $this->id = $id;
        $this->loadData();
    }

    public function loadData(): void
    {
        if ($this->type === 'vehicle') {
            $this->request = VehicleRequest::where('user_ID', auth()->id())
                ->where('id', $this->id)
                ->with(['passengers', 'approvals.user', 'documents'])
                ->firstOrFail();

            $this->approvals = $this->request->approvals()->with('user')->orderBy('id')->get();
            $this->documents = $this->request->documents;
        } else {
            $this->request = TravelRequest::where('user_ID', auth()->id())
                ->where('id', $this->id)
                ->with(['approvals.user', 'documents'])
                ->firstOrFail();

            $this->approvals = $this->request->approvals()->with('user')->orderBy('id')->get();
            $this->documents = $this->request->documents;
        }
    }

    public function getSteps(): array
    {
        if ($this->type === 'vehicle') {
            return [
                ['label' => 'Motor Pool', 'status_key' => 'Pending Motor Pool'],
                ['label' => 'Dean', 'status_key' => 'Pending Dean'],
                ['label' => 'VP', 'status_key' => 'Pending VP'],
                ['label' => 'SUC Pres', 'status_key' => 'Pending SUC'],
                ['label' => 'Final MP', 'status_key' => 'Pending Final MP Approval'],
            ];
        }

        return [
            ['label' => 'Dean', 'status_key' => 'Pending Dean'],
            ['label' => 'VP', 'status_key' => 'Pending VP'],
            ['label' => 'SUC Pres', 'status_key' => 'Pending SUC'],
            ['label' => 'Motor Pool', 'status_key' => 'Pending Motor Pool'],
        ];
    }

    public function getCurrentStepIndex(): int
    {
        if (!$this->request) return 0;

        $steps = $this->getSteps();
        $status = $this->request->vehicle_status;

        foreach ($steps as $index => $step) {
            if (str_contains($status, $step['status_key'])) {
                return $index;
            }
        }

        // Check for terminal statuses
        $terminalStatuses = [
            'Completed', 'Cancelled by User', 'No Vehicle Available',
            'Rejected by Dean', 'Rejected by VP', 'Rejected by SUC',
            'Rejected'
        ];

        foreach ($terminalStatuses as $terminal) {
            if (str_contains($status, $terminal)) {
                return count($steps) - 1;
            }
        }

        return 0;
    }

    public function getRejectedStepIndex(): ?int
    {
        if (!$this->request) return null;

        $steps = $this->getSteps();
        $status = $this->request->vehicle_status;

        $rejectedStatuses = [
            'No Vehicle Available',
            'Rejected by Dean',
            'Rejected by VP',
            'Rejected by SUC',
            'Rejected'
        ];

        foreach ($rejectedStatuses as $rejected) {
            if (str_contains($status, $rejected)) {
                foreach ($steps as $index => $step) {
                    if (str_contains($status, str_replace('Rejected ', '', $rejected))) {
                        return $index;
                    }
                }
            }
        }

        return null;
    }

    public function isCancelled(): bool
    {
        return $this->request && $this->request->vehicle_status === 'Cancelled by User';
    }

    public function render()
    {
        return view('livewire.user.document-tracking-detail', [
            'request' => $this->request,
            'approvals' => $this->approvals,
            'documents' => $this->documents,
            'steps' => $this->getSteps(),
            'currentStep' => $this->getCurrentStepIndex(),
            'rejectedStep' => $this->getRejectedStepIndex(),
            'cancelled' => $this->isCancelled(),
        ]);
    }
}