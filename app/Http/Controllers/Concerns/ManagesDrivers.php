<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

trait ManagesDrivers
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = \App\Models\Driver::latest()->paginate(15);
        return view($this->getViewPath('index'), compact('drivers'));
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
            'full_name' => 'required|string|max:255',
            'license_number' => 'required|string|max:255|unique:drivers',
            'license_expiry' => 'required|date|after:today',
            'phone' => 'nullable|string|max:20',
        ]);

        \App\Models\Driver::create([
            'full_name' => $request->full_name,
            'license_number' => $request->license_number,
            'license_expiry' => $request->license_expiry,
            'phone' => $request->phone,
            'status' => 'Available',
        ]);

        return redirect()->route($this->getRouteName('index'))
            ->with('success', 'Driver created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Driver $driver)
    {
        return view($this->getViewPath('edit'), compact('driver'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Driver $driver)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'license_number' => 'required|string|max:255|unique:drivers,license_number,' . $driver->driver_ID,
            'license_expiry' => 'required|date|after:today',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:Available,On Trip,On Leave',
        ]);

        $driver->update([
            'full_name' => $request->full_name,
            'license_number' => $request->license_number,
            'license_expiry' => $request->license_expiry,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        return redirect()->route($this->getRouteName('index'))
            ->with('success', 'Driver updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Driver $driver)
    {
        $driver->delete();

        return redirect()->route($this->getRouteName('index'))
            ->with('success', 'Driver deleted successfully.');
    }

    /**
     * Get the view path prefix (e.g., 'admin.drivers' or 'staff.drivers')
     */
    abstract protected function getViewPath(string $view): string;

    /**
     * Get the route name prefix (e.g., 'admin.drivers' or 'staff.drivers')
     */
    abstract protected function getRouteName(string $route): string;
}