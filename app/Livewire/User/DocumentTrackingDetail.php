<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\TripRequest;
use App\Enums\TripRequestStatus;
use App\Services\TripRequestWorkflowService;

#[Layout('layouts.user')]
class DocumentTrackingDetail extends Component
{
    public string $type = 'trip';
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
        $this->request = TripRequest::where('user_ID', auth()->id())
            ->where('id', $this->id)
            ->with(['passengers', 'approvals.user', 'documents'])
            ->firstOrFail();

        $this->approvals = $this->request->approvals()->with('user')->orderBy('id')->get();
        $this->documents = $this->request->documents;
    }

    public function getSteps(): array
    {
        return [
            ['label' => 'Dean', 'status_key' => TripRequestStatus::PENDING_DEAN->value],
            ['label' => 'VP', 'status_key' => TripRequestStatus::PENDING_VP->value],
            ['label' => 'SUC', 'status_key' => TripRequestStatus::PENDING_SUC->value],
            ['label' => 'MP', 'status_key' => TripRequestStatus::PENDING_MOTOR_POOL->value],
            ['label' => $this->outcomeLabel() ?? 'Final MP', 'status_key' => TripRequestStatus::PENDING_FINAL_MP->value],
        ];
    }

    public function outcomeLabel(): ?string
    {
        return $this->request
            ? TripRequestStatus::tryFrom($this->request->status)?->outcomeLabel()
            : null;
    }

    public function getCurrentStepIndex(): int
    {
        if (!$this->request) return 0;

        $steps = $this->getSteps();
        $status = $this->request->status;

        foreach ($steps as $index => $step) {
            if (str_contains($status, $step['status_key'])) {
                return $index;
            }
        }

        // Check for terminal statuses
        $terminalStatuses = [
            TripRequestStatus::COMPLETED->value,
            TripRequestStatus::VEHICLE_ASSIGNED->value,
            TripRequestStatus::CANCELLED->value,
            TripRequestStatus::NO_VEHICLE_AVAILABLE->value,
            TripRequestStatus::REJECTED_DEAN->value,
            TripRequestStatus::REJECTED_VP->value,
            TripRequestStatus::REJECTED_SUC->value,
            TripRequestStatus::REJECTED->value,
        ];

        foreach ($terminalStatuses as $terminal) {
            if (str_contains($status, $terminal)) {
                return in_array($status, [
                    TripRequestStatus::COMPLETED->value,
                    TripRequestStatus::VEHICLE_ASSIGNED->value,
                ]) ? count($steps) : count($steps) - 1;
            }
        }

        return 0;
    }

    public function getRejectedStepIndex(): ?int
    {
        if (!$this->request) return null;

        $steps = $this->getSteps();
        $status = $this->request->status;

        $rejectedStatuses = [
            TripRequestStatus::NO_VEHICLE_AVAILABLE->value,
            TripRequestStatus::REJECTED_DEAN->value,
            TripRequestStatus::REJECTED_VP->value,
            TripRequestStatus::REJECTED_SUC->value,
            TripRequestStatus::REJECTED->value,
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
        return $this->request && $this->request->status === TripRequestStatus::CANCELLED->value;
    }

    public function isCancellable(): bool
    {
        return $this->request && in_array(
            $this->request->status,
            app(TripRequestWorkflowService::class)->cancellableStatuses()
        );
    }

    public function cancelTripRequest($id): void
    {
        $request = TripRequest::where('user_ID', auth()->id())->findOrFail($id);

        $workflow = app(TripRequestWorkflowService::class);

        if ($workflow->cancelTripRequest($request, auth()->id())) {
            session()->flash('success', 'Trip request cancelled successfully!');
        }

        $this->redirectRoute('user.document-tracking');
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