@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Add Driver</h1>
        <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.drivers.store') }}" class="space-y-6">
                @csrf
                <div class="form-group">
                    <label class="label" for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" class="input" required>
                </div>
                <div class="form-group">
                    <label class="label" for="license_number">License Number *</label>
                    <input type="text" id="license_number" name="license_number" class="input" required>
                </div>
                <div class="form-group">
                    <label class="label" for="license_expiry">License Expiry *</label>
                    <input type="date" id="license_expiry" name="license_expiry" class="input" required>
                </div>
                <div class="form-group">
                    <label class="label" for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" class="input">
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
