<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

trait ManagesVehicles
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = \App\Models\Vehicle::latest()->paginate(15);
        return view($this->getViewPath('index'), compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view($this->getViewPath('create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_name' => 'required|string|max:255',
            'plate_number' => 'required|string|max:255|unique:vehicles',
            'vehicle_type' => 'required|in:Car,Van,Bus,Truck',
            'capacity' => 'required|integer|min:1',
        ]);

        \App\Models\Vehicle::create([
            'vehicle_name' => $request->vehicle_name,
            'plate_number' => $request->plate_number,
            'vehicle_type' => $request->vehicle_type,
            'capacity' => $request->capacity,
            'status' => 'Available',
        ]);

        return redirect()->route($this->getRouteName('index'))
            ->with('success', 'Vehicle created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Vehicle $vehicle)
    {
        return view($this->getViewPath('edit'), compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Vehicle $vehicle)
    {
        $request->validate([
            'vehicle_name' => 'required|string|max:255',
            'plate_number' => 'required|string|max:255|unique:vehicles,plate_number,' . $vehicle->vehicle_ID,
            'vehicle_type' => 'required|in:Car,Van,Bus,Truck',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:Available,On Trip,Maintenance',
        ]);

        $vehicle->update([
            'vehicle_name' => $request->vehicle_name,
            'plate_number' => $request->plate_number,
            'vehicle_type' => $request->vehicle_type,
            'capacity' => $request->capacity,
            'status' => $request->status,
        ]);

        return redirect()->route($this->getRouteName('index'))
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route($this->getRouteName('index'))
            ->with('success', 'Vehicle deleted successfully.');
    }

    /**
     * Get the view path prefix (e.g., 'admin.vehicles' or 'staff.vehicles')
     */
    abstract protected function getViewPath(string $view): string;

    /**
     * Get the route name prefix (e.g., 'admin.vehicles' or 'staff.vehicles')
     */
    abstract protected function getRouteName(string $route): string;
}