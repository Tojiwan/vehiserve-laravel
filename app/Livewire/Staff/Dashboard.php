<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\VehicleRequest;
use App\Models\TravelRequest;
use App\Enums\VehicleRequestStatus;
use App\Enums\TravelRequestStatus;
use Carbon\Carbon;

#[Layout('layouts.staff')]
class Dashboard extends Component
{
    public $sidebarOpen = false;
    public $sidebarCollapsed = false;

    public $pendingRequestsCount = 0;
    public $availableVehiclesCount = 0;
    public $availableDriversCount = 0;
    public $tripsTodayCount = 0;
    public $pendingRequests = [];
    public $todayTrips = [];
    public $vehicles = [];

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        // Count pending vehicle requests
        $this->pendingRequestsCount = VehicleRequest::whereIn('vehicle_status', [
            VehicleRequestStatus::PENDING_MOTOR_POOL->value,
            VehicleRequestStatus::PENDING_DEAN->value,
            VehicleRequestStatus::PENDING_VP->value,
            VehicleRequestStatus::PENDING_SUC->value,
            VehicleRequestStatus::PENDING_FINAL_MP->value,
        ])->count();

        // Count pending travel requests
        $this->pendingRequestsCount += TravelRequest::whereIn('vehicle_status', [
            TravelRequestStatus::PENDING_DEAN->value,
            TravelRequestStatus::PENDING_VP->value,
            TravelRequestStatus::PENDING_SUC->value,
            TravelRequestStatus::PENDING_MOTOR_POOL->value,
        ])->count();

        // Available vehicles
        $this->availableVehiclesCount = Vehicle::where('status', 'Available')->count();

        // Available drivers
        $this->availableDriversCount = Driver::where('status', 'Available')->count();

        // Today's trips
        $today = Carbon::today();
        $this->tripsTodayCount = Booking::whereDate('date', $today)->count();

        // Pending requests for queue
        $this->pendingRequests = VehicleRequest::whereIn('vehicle_status', [
            VehicleRequestStatus::PENDING_MOTOR_POOL->value,
            VehicleRequestStatus::PENDING_DEAN->value,
            VehicleRequestStatus::PENDING_VP->value,
            VehicleRequestStatus::PENDING_SUC->value,
            VehicleRequestStatus::PENDING_FINAL_MP->value,
        ])
            ->with('user')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($request) {
                return [
                    'type' => 'vehicle',
                    'id' => $request->id,
                    'requester_name' => $request->requesting_person,
                    'destination' => $request->destination,
                    'current_stage' => $request->vehicle_status,
                    'current_stage_class' => $this->getStageClass($request->vehicle_status),
                    'created_at' => $request->created_at,
                ];
            })->concat(
                TravelRequest::whereIn('vehicle_status', [
                    TravelRequestStatus::PENDING_DEAN->value,
                    TravelRequestStatus::PENDING_VP->value,
                    TravelRequestStatus::PENDING_SUC->value,
                    TravelRequestStatus::PENDING_MOTOR_POOL->value,
                ])
                    ->with('user')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->map(function ($request) {
                        return [
                            'type' => 'travel',
                            'id' => $request->id,
                            'requester_name' => $request->personnel_name,
                            'destination' => $request->destination,
                            'current_stage' => $request->vehicle_status,
                            'current_stage_class' => $this->getStageClass($request->vehicle_status),
                            'created_at' => $request->created_at,
                        ];
                    })
            )->sortByDesc('created_at')
            ->take(10)
            ->values()
            ->toArray();

        // Today's trips
        $this->todayTrips = Booking::whereDate('date', Carbon::today())
            ->with(['vehicle', 'driver'])
            ->latest()
            ->get()
            ->map(function ($booking) {
                return [
                    'booking_ID' => $booking->booking_ID,
                    'vehicle_name' => $booking->vehicle?->vehicle_name ?? 'N/A',
                    'driver_name' => $booking->driver?->full_name ?? 'N/A',
                    'destination' => $booking->destination,
                    'date' => $booking->date,
                    'return_date' => $booking->return_date,
                    'status' => $booking->status,
                ];
            })->toArray();

        // Vehicles for status overview
        $this->vehicles = Vehicle::with('driver')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($vehicle) {
                return [
                    'vehicle_ID' => $vehicle->vehicle_ID,
                    'vehicle_name' => $vehicle->vehicle_name,
                    'plate_number' => $vehicle->plate_number,
                    'status' => $vehicle->status,
                    'capacity' => $vehicle->capacity,
                ];
            })->toArray();
    }

    protected function getStageClass(string $status): string
    {
        return match (true) {
            str_contains($status, 'Pending') => 'badge-warning',
            str_contains($status, 'Approved') || str_contains($status, 'Completed') => 'badge-success',
            str_contains($status, 'Rejected') || str_contains($status, 'No Vehicle') => 'badge-danger',
            str_contains($status, 'Cancelled') => 'badge-gray',
            default => 'badge-gray',
        };
    }

    public function render()
    {
        return view('livewire.staff.dashboard', [
            'pendingRequestsCount' => $this->pendingRequestsCount,
            'availableVehiclesCount' => $this->availableVehiclesCount,
            'availableDriversCount' => $this->availableDriversCount,
            'tripsTodayCount' => $this->tripsTodayCount,
            'pendingRequests' => $this->pendingRequests,
            'todayTrips' => $this->todayTrips,
            'vehicles' => $this->vehicles,
        ]);
    }
}