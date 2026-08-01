@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Add Vehicle</h1>
        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.vehicles.store') }}" class="space-y-6">
                @csrf
                <div class="form-group">
                    <label class="label" for="vehicle_name">Vehicle Name *</label>
                    <input type="text" id="vehicle_name" name="vehicle_name" class="input" required>
                </div>
                <div class="form-group">
                    <label class="label" for="plate_number">Plate Number *</label>
                    <input type="text" id="plate_number" name="plate_number" class="input" required>
                </div>
                <div class="form-group">
                    <label class="label" for="vehicle_type">Type *</label>
                    <select id="vehicle_type" name="vehicle_type" class="input" required>
                        <option value="Car">Car</option>
                        <option value="Van">Van</option>
                        <option value="Bus">Bus</option>
                        <option value="Truck">Truck</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="label" for="capacity">Capacity *</label>
                    <input type="number" id="capacity" name="capacity" class="input" required min="1" value="1">
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
