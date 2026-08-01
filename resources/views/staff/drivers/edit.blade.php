@extends('layouts.staff')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Edit Driver</h1>
        <a href="{{ route('staff.drivers.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('staff.drivers.update', $driver) }}" class="space-y-6">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="label" for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" class="input" required value="{{ $driver->full_name }}">
                </div>
                <div class="form-group">
                    <label class="label" for="license_number">License Number *</label>
                    <input type="text" id="license_number" name="license_number" class="input" required value="{{ $driver->license_number }}">
                </div>
                <div class="form-group">
                    <label class="label" for="license_expiry">License Expiry *</label>
                    <input type="date" id="license_expiry" name="license_expiry" class="input" required value="{{ $driver->license_expiry }}">
                </div>
                <div class="form-group">
                    <label class="label" for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" class="input" value="{{ $driver->phone }}">
                </div>
                <div class="form-group">
                    <label class="label" for="status">Status</label>
                    <select id="status" name="status" class="input">
                        <option value="Available" {{ $driver->status === 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="On Trip" {{ $driver->status === 'On Trip' ? 'selected' : '' }}>On Trip</option>
                        <option value="On Leave" {{ $driver->status === 'On Leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('staff.drivers.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
