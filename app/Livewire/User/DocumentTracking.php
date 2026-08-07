<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\TripRequest;
use App\Enums\TripRequestStatus;

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

    public function getStatusEnum($status): ?TripRequestStatus
    {
        return TripRequestStatus::tryFrom($status);
    }

    public function getStepIndex(string $status): int
    {
        return TripRequestStatus::tryFrom($status)?->getStepIndex() ?? 0;
    }

    public function getRejectedStepIndex(string $status): ?int
    {
        return TripRequestStatus::tryFrom($status)?->getRejectedStepIndex() ?? null;
    }

    public function cancelTripRequest($id): void
    {
        $request = TripRequest::where('user_ID', auth()->id())->findOrFail($id);
        
        if (in_array($request->status, [
            'Pending Dean', 'Pending Vice President', 'Pending SUC President', 
            'Pending Motor Pool', 'Pending Final MP Approval',
            'Pending Dean', 'Pending VP', 'Pending SUC', 'Pending Motor Pool'
        ])) {
            $request->update(['status' => 'Cancelled by User']);
            $request->approvals()->where('status', 'Waiting')->update(['status' => 'Cancelled']);
            session()->flash('success', 'Trip request cancelled successfully!');
        }
    }

    public function getTripRequestsProperty()
    {
        return TripRequest::where('user_ID', auth()->id())
            ->where('status', '!=', 'Cancelled by User')
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