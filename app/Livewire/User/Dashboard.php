<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\VehicleRequest;
use App\Models\TravelRequest;
use App\Services\VehicleRequestService;
use App\Services\TravelRequestService;

#[Layout('layouts.user')]
class Dashboard extends Component
{
    public $vehicleStats = [];
    public $travelStats = [];
    public $recentVehicleRequests = [];
    public $recentTravelRequests = [];

    public function mount(): void
    {
        $this->loadStats();
        $this->loadRecentRequests();
    }

    public function loadStats(): void
    {
        $vehicleService = new VehicleRequestService();
        $travelService = new TravelRequestService();

        $this->vehicleStats = $vehicleService->getStats(auth()->id());
        $this->travelStats = $travelService->getStats(auth()->id());
    }

    public function loadRecentRequests(): void
    {
        $this->recentVehicleRequests = VehicleRequest::where('user_ID', auth()->id())
            ->latest()
            ->limit(5)
            ->get();

        $this->recentTravelRequests = TravelRequest::where('user_ID', auth()->id())
            ->latest()
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.user.dashboard');
    }
}