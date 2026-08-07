@extends('layouts.staff')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $trip->inclusive_date ? 'Travel Request' : 'Vehicle Request' }} Details
            </h1>
            <p class="text-gray-500 mt-1">#{{ $trip->id }} - {{ $trip->destination }}</p>
        </div>
        <a href="{{ route('staff.trips.index') }}" class="btn btn-secondary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Queue
        </a>
    </div>

    <!-- Request Info Card -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Request Information</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Request ID</p>
                    <p class="font-semibold text-gray-900">#{{ $trip->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">
                        {{ $trip->inclusive_date ? 'Inclusive Date' : 'Request Date' }}
                    </p>
                    <p class="font-semibold text-gray-900">
                        {{ $trip->inclusive_date 
                            ? $trip->inclusive_date->format('M d, Y') 
                            : $trip->departure_date->format('M d, Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">
                        {{ $trip->inclusive_date ? 'Personnel Name' : 'Requesting Person' }}
                    </p>
                    <p class="font-semibold text-gray-900">
                        {{ $trip->inclusive_date ? $trip->personnel_name : $trip->requesting_person }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Destination</p>
                    <p class="font-semibold text-gray-900">{{ $trip->destination }}</p>
                </div>
            </div>

            @if ($trip->inclusive_date)
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Official Station</p>
                    <p class="font-semibold text-gray-900">{{ $trip->official_station }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Inclusive Date</p>
                    <p class="font-semibold text-gray-900">{{ $trip->inclusive_date->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Requesting For</p>
                    <p class="font-semibold text-gray-900">{{ $trip->requesting_for }}</p>
                </div>
            </div>
            @else
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Request Date</p>
                    <p class="font-semibold text-gray-900">{{ $trip->request_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Departure Date</p>
                    <p class="font-semibold text-gray-900">{{ $trip->departure_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Return Date</p>
                    <p class="font-semibold text-gray-900">{{ $trip->return_date->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Departure Time</p>
                    <p class="font-semibold text-gray-900">{{ $trip->departure_time }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Return Date</p>
                    <p class="font-semibold text-gray-900">{{ $trip->return_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Number of Passengers</p>
                    <p class="font-semibold text-gray-900">{{ $trip->num_passengers }}</p>
                </div>
            </div>

            <div class="mt-6">
                <p class="text-sm text-gray-500">Office/College</p>
                <p class="font-semibold text-gray-900">{{ $trip->office_college }}</p>
            </div>

            <div class="mt-6">
                <p class="text-sm text-gray-500">Purpose</p>
                <p class="text-gray-900">{{ $trip->purpose }}</p>
            </div>

            @if ($trip->passengers->count() > 0)
            <div class="mt-6">
                <p class="text-sm text-gray-500">Passengers ({{ $trip->passengers->count() }})</p>
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach ($trip->passengers as $passenger)
                    <span class="badge badge-primary">{{ $passenger->passenger_name }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mt-6">
                <p class="text-sm text-gray-500">Purpose</p>
                <p class="text-gray-900">{{ $trip->purpose }}</p>
            </div>

            @if ($trip->vehicle)
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-500">Currently Assigned Vehicle</p>
                <p class="font-semibold text-gray-900">{{ $trip->vehicle->vehicle_name }} ({{ $trip->vehicle->plate_number }})</p>
            </div>
            @endif

            @if ($trip->driver)
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-500">Currently Assigned Driver</p>
                <p class="font-semibold text-gray-900">{{ $trip->driver->full_name }} ({{ $trip->driver->license_number }})</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Progress Tracker -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Approval Progress</h2>
        </div>
        <div class="card-body">
            <livewire:shared.progress-tracker 
                :steps="{{ $steps }}"
                :currentStep="{{ $currentStep }}"
                :rejectedStep="{{ $rejectedStep }}"
                :cancelled="{{ $cancelled }}"
            />
        </div>
    </div>

    <!-- Approval History -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Approval History</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Stage</th>
                            <th>Approver</th>
                            <th>Status</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($approvals as $approval)
                        <tr>
                            <td>{{ $approval->role }}</td>
                            <td>
                                @if ($approval->user)
                                {{ $approval->user->name }}
                                @else
                                <span class="text-gray-400">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge 
                                    @if($approval->status === 'Approved') badge-success
                                    @elseif($approval->status === 'Rejected') badge-danger
                                    @elseif($approval->status === 'Pending') badge-warning
                                    @else badge-gray
                                    @endif
                                ">
                                    {{ $approval->status }}
                                </span>
                            </td>
                            <td>{{ $approval->comment ?? '<span class="text-gray-400">-</span>' }}</td>
                            <td>{{ $approval->approved_at ? $approval->approved_at->format('M d, Y H:i') : '<span class="text-gray-400">-</span>' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-8">
                                <p class="text-gray-500">No approval records found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Vehicle & Driver Assignment -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Assign Vehicle & Driver</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('staff.trips.assign', $trip) }}" class="space-y-6">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label class="label" for="vehicle_ID">Vehicle <span class="text-red-500">*</span></label>
                        <select id="vehicle_ID" name="vehicle_ID" class="input" required>
                            <option value="">Select Vehicle</option>
                            @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->vehicle_ID }}">
                                {{ $vehicle->vehicle_name }} ({{ $vehicle->plate_number }}) - Capacity: {{ $vehicle->capacity }}
                            </option>
                            @endforeach
                        </select>
                        @error('vehicle_ID') <p class="error-message">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="label" for="driver_ID">Driver <span class="text-red-500">*</span></label>
                        <select id="driver_ID" name="driver_ID" class="input" required>
                            <option value="">Select Driver</option>
                            @foreach ($drivers as $driver)
                            <option value="{{ $driver->driver_ID }}">
                                {{ $driver->full_name }} ({{ $driver->license_number }})
                            </option>
                            @endforeach
                        </select>
                        @error('driver_ID') <p class="error-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="label" for="return_date">Return Date <span class="text-red-500">*</span></label>
                    <input type="date" id="return_date" name="return_date" class="input" required value="{{ old('return_date', $trip->return_date) }}">
                    @error('return_date') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                    <a href="{{ route('staff.trips.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12a6 6 0 01-6 6H6a6 6 0 01-6-6H0"></path>
                        </svg>
                        Assign Vehicle & Driver
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection