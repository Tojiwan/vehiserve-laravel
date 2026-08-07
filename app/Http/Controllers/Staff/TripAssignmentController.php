<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\TripRequest;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Booking;
use Illuminate\Http\Request;

class TripAssignmentController extends Controller
{
    public function index()
    {
        $pendingTrips = TripRequest::where('status', 'Pending Motor Pool')
            ->with(['user', 'vehicle', 'driver', 'approvals.user'])
            ->latest()
            ->paginate(15);

        return view('staff.trips.index', compact('pendingTrips'));
    }

    public function show(TripRequest $trip)
    {
        $trip->load(['user', 'vehicle', 'driver', 'approvals.user', 'passengers', 'documents']);
        $vehicles = Vehicle::where('status', 'Available')->get();
        $drivers = Driver::where('status', 'Available')->get();
        $approvals = $trip->approvals()->with('user')->orderBy('id')->get();

        return view('staff.trips.show', compact('trip', 'vehicles', 'drivers', 'approvals'));
    }

    public function assign(Request $request, TripRequest $trip)
    {
        $request->validate([
            'vehicle_ID' => 'required|exists:vehicles,vehicle_ID',
            'driver_ID' => 'required|exists:drivers,driver_ID',
            'return_date' => 'required|date|after_or_equal:' . $trip->departure_date,
        ]);

        $workflow = new \App\Services\TripRequestWorkflowService();
        $workflow->assignVehicleAndDriver($trip, $request->vehicle_ID, $request->driver_ID, $request->return_date);

        return redirect()->route('staff.trips.index')
            ->with('success', 'Vehicle and driver assigned successfully!');
    }
}