<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\TripRequest;
use App\Services\TripRequestWorkflowService;

#[Layout('layouts.user')]
class DocumentTracking extends Component
{
    use WithPagination;

    public $sidebarOpen = false;
    public $sidebarCollapsed = false;

    public $search = '';
    public $statusFilter = 'all';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSortField(): void
    {
        $this->resetPage();
    }

    public function isCancellable(string $status): bool
    {
        return in_array($status, app(TripRequestWorkflowService::class)->cancellableStatuses());
    }

    public function cancelTripRequest($id): void
    {
        $request = TripRequest::where('user_ID', auth()->id())->findOrFail($id);

        $workflow = app(TripRequestWorkflowService::class);

        if ($workflow->cancelTripRequest($request, auth()->id())) {
            session()->flash('success', 'Trip request cancelled successfully!');
        }
    }

    public function getTripRequestsProperty()
    {
        return TripRequest::where('user_ID', auth()->id())
            ->with('approvals')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('personnel_name', 'like', "%{$this->search}%")
                      ->orWhere('destination', 'like', "%{$this->search}%")
                      ->orWhere('id', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
        }

    public function render()
    {
        return view('livewire.user.document-tracking', [
            'tripRequests' => $this->tripRequests,
        ]);
    }
}