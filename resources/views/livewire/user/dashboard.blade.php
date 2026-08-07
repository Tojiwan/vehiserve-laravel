<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Vehicle Request Stats -->
        <div class="stat-card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Pending Vehicle</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $vehicleStats['pending'] ?? 0 }}</p>
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
                        <p class="text-sm text-gray-500">Approved Vehicle</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $vehicleStats['approved'] ?? 0 }}</p>
                    </div>
                    <div class="stat-card-icon bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Rejected Vehicle</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $vehicleStats['rejected'] ?? 0 }}</p>
                    </div>
                    <div class="stat-card-icon bg-red-100 text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Cancelled Vehicle</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $vehicleStats['cancelled'] ?? 0 }}</p>
                    </div>
                    <div class="stat-card-icon bg-gray-100 text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
                <a href="{{ route('user.trip-request.create') }}" class="btn btn-primary w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Trip Request
                </a>
                <a href="{{ route('user.trip-requests') }}" class="btn btn-secondary w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    Track Requests
                </a>
                <a href="{{ route('user.documents') }}" class="btn btn-secondary w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    View Documents
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Vehicle Requests -->
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Recent Vehicle Requests</h2>
                <a href="{{ route('user.trip-requests') }}" class="text-sm text-red-600 hover:text-red-700">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Destination</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentVehicleRequests as $request)
                            <tr>
                                <td>#{{ $request->id }}</td>
                                <td>{{ $request->request_date->format('M d, Y') }}</td>
                                <td>{{ $request->destination }}</td>
                                <td>
                                    <span class="badge 
                                        @if(str_contains($request->vehicle_status, 'Pending')) badge-warning
                                        @elseif(str_contains($request->vehicle_status, 'Approved') || str_contains($request->vehicle_status, 'Completed')) badge-success
                                        @elseif(str_contains($request->vehicle_status, 'Rejected') || str_contains($request->vehicle_status, 'No Vehicle')) badge-danger
                                        @elseif(str_contains($request->vehicle_status, 'Cancelled')) badge-gray
                                        @else badge-info
                                        @endif
                                    ">
                                        {{ $request->vehicle_status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-8">
                                    <div class="empty-state">
                                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                        <p class="empty-state-title">No vehicle requests yet</p>
                                        <p class="empty-state-description">Create your first trip request</p>
                                        <a href="{{ route('user.trip-request.create') }}" class="btn btn-primary">Create Request</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Travel Requests -->
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Recent Travel Requests</h2>
                <a href="{{ route('user.trip-requests') }}" class="text-sm text-red-600 hover:text-red-700">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Destination</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentTravelRequests as $request)
                            <tr>
                                <td>#{{ $request->id }}</td>
                                <td>{{ $request->inclusive_date->format('M d, Y') }}</td>
                                <td>{{ $request->destination }}</td>
                                <td>
                                    <span class="badge 
                                        @if(str_contains($request->vehicle_status, 'Pending')) badge-warning
                                        @elseif(str_contains($request->vehicle_status, 'Approved') || str_contains($request->vehicle_status, 'Completed')) badge-success
                                        @elseif(str_contains($request->vehicle_status, 'Rejected') || str_contains($request->vehicle_status, 'No Vehicle')) badge-danger
                                        @elseif(str_contains($request->vehicle_status, 'Cancelled')) badge-gray
                                        @else badge-info
                                        @endif
                                    ">
                                        {{ $request->vehicle_status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-8">
                                    <div class="empty-state">
                                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                        <p class="empty-state-title">No travel requests yet</p>
                                        <p class="empty-state-description">Create your first travel request</p>
                                        <a href="{{ route('user.trip-request.create') }}" class="btn btn-primary">Create Request</a>
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
</div>