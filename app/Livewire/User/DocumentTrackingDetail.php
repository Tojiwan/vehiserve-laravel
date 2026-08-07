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
            'steps' => $this->request->progressSteps(),
            'cancelled' => $this->isCancelled(),
        ]);
    }
}