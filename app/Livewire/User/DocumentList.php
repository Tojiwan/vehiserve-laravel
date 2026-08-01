<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\VehicleRequest;
use App\Models\TravelRequest;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.user')]
class DocumentList extends Component
{
    use WithPagination;

    public $sidebarOpen = false;
    public $sidebarCollapsed = false;

    public $vehicleSearch = '';
    public $travelSearch = '';

    public function getVehicleRequestsProperty()
    {
        return VehicleRequest::where('user_ID', auth()->id())
            ->where('vehicle_status', 'Completed')
            ->when($this->vehicleSearch, function ($query) {
                $query->where(function ($q) {
                    $q->where('requesting_person', 'like', "%{$this->vehicleSearch}%")
                      ->orWhere('destination', 'like', "%{$this->vehicleSearch}%")
                      ->orWhere('id', 'like', "%{$this->vehicleSearch}%");
                });
            })
            ->latest()
            ->paginate(5);
    }

    public function getTravelRequestsProperty()
    {
        return TravelRequest::where('user_ID', auth()->id())
            ->where('vehicle_status', 'Completed')
            ->when($this->travelSearch, function ($query) {
                $query->where(function ($q) {
                    $q->where('personnel_name', 'like', "%{$this->travelSearch}%")
                      ->orWhere('destination', 'like', "%{$this->travelSearch}%")
                      ->orWhere('id', 'like', "%{$this->travelSearch}%");
                });
            })
            ->latest()
            ->paginate(5);
    }

    #[On('download-pdf')]
    public function downloadPdf($type, $id): void
    {
        $documentService = new DocumentService();

        if ($type === 'vehicle') {
            $request = VehicleRequest::findOrFail($id);
            if ($request->user_ID !== auth()->id()) {
                abort(403);
            }
            $path = $documentService->generateVehicleRequestPdf($request);
        } else {
            $request = TravelRequest::findOrFail($id);
            if ($request->user_ID !== auth()->id()) {
                abort(403);
            }
            $path = $documentService->generateTravelRequestPdf($request);
        }

        $this->dispatch('trigger-download', url: route('document.download', ['path' => $path]));
    }

    public function render()
    {
        return view('livewire.user.document-list', [
            'vehicleRequests' => $this->vehicleRequests,
            'travelRequests' => $this->travelRequests,
        ]);
    }
}