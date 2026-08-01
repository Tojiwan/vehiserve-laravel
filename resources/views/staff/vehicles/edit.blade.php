@extends('layouts.staff')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Edit Vehicle</h1>
        <a href="{{ route('staff.vehicles.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('staff.vehicles.update', $vehicle) }}" class="space-y-6">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="label" for="vehicle_name">Vehicle Name *</label>
                    <input type="text" id="vehicle_name" name="vehicle_name" class="input" required value="{{ $vehicle->vehicle_name }}">
                </div>
                <div class="form-group">
                    <label class="label" for="plate_number">Plate Number *</label>
                    <input type="text" id="plate_number" name="plate_number" class="input" required value="{{ $vehicle->plate_number }}">
                </div>
                <div class="form-group">
                    <label class="label" for="vehicle_type">Type *</label>
                    <select id="vehicle_type" name="vehicle_type" class="input" required>
                        <option value="Car" {{ $vehicle->vehicle_type === 'Car' ? 'selected' : '' }}>Car</option>
                        <option value="Van" {{ $vehicle->vehicle_type === 'Van' ? 'selected' : '' }}>Van</option>
                        <option value="Bus" {{ $vehicle->vehicle_type === 'Bus' ? 'selected' : '' }}>Bus</option>
                        <option value="Truck" {{ $vehicle->vehicle_type === 'Truck' ? 'selected' : '' }}>Truck</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="label" for="capacity">Capacity *</label>
                    <input type="number" id="capacity" name="capacity" class="input" required min="1" value="{{ $vehicle->capacity }}">
                </div>
                <div class="form-group">
                    <label class="label" for="status">Status</label>
                    <select id="status" name="status" class="input">
                        <option value="Available" {{ $vehicle->status === 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="On Trip" {{ $vehicle->status === 'On Trip' ? 'selected' : '' }}>On Trip</option>
                        <option value="Maintenance" {{ $vehicle->status === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('staff.vehicles.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
