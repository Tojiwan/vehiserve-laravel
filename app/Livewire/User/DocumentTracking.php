<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\VehicleRequest;
use App\Models\TravelRequest;
use App\Enums\VehicleRequestStatus;
use App\Enums\TravelRequestStatus;

#[Layout('layouts.user')]
class DocumentTracking extends Component
{
    use WithPagination;

    public $sidebarOpen = false;
    public $sidebarCollapsed = false;

    public $vehicleSearch = '';
    public $travelSearch = '';
    public $vehicleSortField = 'created_at';
    public $vehicleSortDirection = 'desc';
    public $travelSortField = 'created_at';
    public $travelSortDirection = 'desc';

    public function sortBy($field, $type = 'vehicle'): void
    {
        if ($type === 'vehicle') {
            if ($this->vehicleSortField === $field) {
                $this->vehicleSortDirection = $this->vehicleSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                $this->vehicleSortField = $field;
                $this->vehicleSortDirection = 'asc';
            }
        } else {
            if ($this->travelSortField === $field) {
                $this->travelSortDirection = $this->travelSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                $this->travelSortField = $field;
                $this->travelSortDirection = 'asc';
            }
        }
    }

    public function getVehicleStatusEnum($status): VehicleRequestStatus|null
    {
        return VehicleRequestStatus::tryFrom($status);
    }

    public function getTravelStatusEnum($status): TravelRequestStatus|null
    {
        return TravelRequestStatus::tryFrom($status);
    }

    public function getVehicleStepIndex(string $status): int
    {
        return VehicleRequestStatus::tryFrom($status)?->getStepIndex() ?? 0;
    }

    public function getTravelStepIndex(string $status): int
    {
        return TravelRequestStatus::tryFrom($status)?->getStepIndex() ?? 0;
    }

    public function getVehicleRejectedStepIndex(string $status): ?int
    {
        $enum = VehicleRequestStatus::tryFrom($status);
        return $enum && $enum->isRejected() ? $enum->getStepIndex() : null;
    }

    public function getTravelRejectedStepIndex(string $status): ?int
    {
        $enum = TravelRequestStatus::tryFrom($status);
        return $enum && $enum->isRejected() ? $enum->getStepIndex() : null;
    }

    public function cancelVehicleRequest($id): void
    {
        $request = VehicleRequest::where('user_ID', auth()->id())->findOrFail($id);
        
        if (in_array($request->vehicle_status, ['Pending Motor Pool', 'Pending Dean', 'Pending VP', 'Pending SUC', 'Pending Final MP Approval'])) {
            $request->update(['vehicle_status' => 'Cancelled by User']);
            $request->approvals()->where('status', 'Waiting')->update(['status' => 'Cancelled']);
            session()->flash('success', 'Vehicle request cancelled successfully!');
        }
    }

    public function cancelTravelRequest($id): void
    {
        $request = TravelRequest::where('user_ID', auth()->id())->findOrFail($id);
        
        if (in_array($request->vehicle_status, ['Pending Dean', 'Pending VP', 'Pending SUC', 'Pending Motor Pool'])) {
            $request->update(['vehicle_status' => 'Cancelled by User']);
            $request->approvals()->where('status', 'Waiting')->update(['status' => 'Cancelled']);
            session()->flash('success', 'Travel request cancelled successfully!');
        }
    }

    public function getVehicleRequestsProperty()
    {
        return VehicleRequest::where('user_ID', auth()->id())
            ->where('vehicle_status', '!=', 'Cancelled by User')
            ->when($this->vehicleSearch, function ($query) {
                $query->where(function ($q) {
                    $q->where('requesting_person', 'like', "%{$this->vehicleSearch}%")
                      ->orWhere('destination', 'like', "%{$this->vehicleSearch}%")
                      ->orWhere('id', 'like', "%{$this->vehicleSearch}%");
                });
            })
            ->orderBy($this->vehicleSortField, $this->vehicleSortDirection)
            ->paginate(5);
    }

    public function getTravelRequestsProperty()
    {
        return TravelRequest::where('user_ID', auth()->id())
            ->where('vehicle_status', '!=', 'Cancelled by User')
            ->when($this->travelSearch, function ($query) {
                $query->where(function ($q) {
                    $q->where('personnel_name', 'like', "%{$this->travelSearch}%")
                      ->orWhere('destination', 'like', "%{$this->travelSearch}%")
                      ->orWhere('id', 'like', "%{$this->travelSearch}%");
                });
            })
            ->orderBy($this->travelSortField, $this->travelSortDirection)
            ->paginate(5);
    }

    public function render()
    {
        return view('livewire.user.document-tracking', [
            'vehicleRequests' => $this->vehicleRequests,
            'travelRequests' => $this->travelRequests,
        ]);
    }
}