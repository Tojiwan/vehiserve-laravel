@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Motor Pool Dashboard</h1>
            <p class="text-gray-500 mt-1">Manage vehicles, drivers, and approvals</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Pending Requests</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingRequestsCount ?? 0 }}</p>
                    </div>
                    <div class="stat-card-icon bg-amber-100 text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Available Vehicles</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $availableVehiclesCount ?? 0 }}</p>
                    </div>
                    <div class="stat-card-icon bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h8a1 1 0 001-1z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10h10a1 1 0 011 1v10a1 1 0 01-1 1h-3m-6 0a1 1 0 011-1h3m-3 0a1 1 0 100 2H7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Available Drivers</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $availableDriversCount ?? 0 }}</p>
                    </div>
                    <div class="stat-card-icon bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Trips Today</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $tripsTodayCount ?? 0 }}</p>
                    </div>
                    <div class="stat-card-icon bg-purple-100 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 4-5 4-5s4 3 4 5 3 1 3 1 3-1 3-1 3-5 4-5z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('staff.approval-queue') }}" class="btn btn-primary w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    Approval Queue
                </a>
                <a href="{{ route('staff.vehicles.index') }}" class="btn btn-secondary w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h8a1 1 0 001-1z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10h10a1 1 0 011 1v10a1 1 0 01-1 1h-3m-6 0a1 1 0 011-1h3m-3 0a1 1 0 100 2H7"></path>
                    </svg>
                    Manage Vehicles
                </a>
                <a href="{{ route('staff.drivers.index') }}" class="btn btn-secondary w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Manage Drivers
                </a>
                <a href="{{ route('staff.calendar') }}" class="btn btn-secondary w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Calendar & Schedules
                </a>
            </div>
        </div>
    </div>

    <!-- Today's Trips -->
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Today's Vehicle Trips</h2>
            <a href="{{ route('staff.trips.index') }}" class="text-sm text-red-600 hover:text-red-700">View All</a>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Trip ID</th>
                            <th>Vehicle</th>
                            <th>Driver</th>
                            <th>Destination</th>
                            <th>Departure</th>
                            <th>Return</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($todayTrips as $trip)
                        <tr>
                            <td>#{{ $trip->booking_ID }}</td>
                            <td>{{ $trip->vehicle_name }}</td>
                            <td>{{ $trip->driver_name }}</td>
                            <td>{{ $trip->destination }}</td>
                            <td>{{ $trip->date->format('H:i') }}</td>
                            <td>{{ $trip->return_date->format('H:i') }}</td>
                            <td>
                                <span class="badge 
                                    @if($trip->status === 'Booked') badge-primary
                                    @elseif($trip->status === 'Completed') badge-success
                                    @elseif($trip->status === 'Cancelled') badge-gray
                                    @endif
                                ">
                                    {{ $trip->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <div class="empty-state">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h8a1 1 0 001-1z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10h10a1 1 0 011 1v10a1 1 0 01-1 1h-3m-6 0a1 1 0 011-1h3m-3 0a1 1 0 100 2H7"></path>
                                    </svg>
                                    <p class="empty-state-title">No trips scheduled today</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Vehicle Status Overview -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Vehicle Status Overview</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Plate</th>
                            <th>Status</th>
                            <th>Capacity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vehicles as $vehicle)
                        <tr>
                            <td>{{ $vehicle->vehicle_name }}</td>
                            <td>{{ $vehicle->plate_number }}</td>
                            <td>
                                <span class="badge 
                                    @if($vehicle->status === 'Available') badge-success
                                    @elseif($vehicle->status === 'On Trip') badge-primary
                                    @elseif($vehicle->status === 'Maintenance') badge-warning
                                    @else badge-gray
                                    @endif
                                ">
                                    {{ $vehicle->status }}
                                </span>
                            </td>
                            <td>{{ $vehicle->capacity }}</td>
                            <td>
                                <a href="{{ route('staff.vehicles.edit', $vehicle->vehicle_ID) }}" class="btn btn-ghost btn-sm btn-icon">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <div class="empty-state">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h8a1 1 0 001-1z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10h10a1 1 0 011 1v10a1 1 0 01-1 1h-3m-6 0a1 1 0 011-1h3m-3 0a1 1 0 100 2H7"></path>
                                    </svg>
                                    <p class="empty-state-title">No vehicles registered</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection