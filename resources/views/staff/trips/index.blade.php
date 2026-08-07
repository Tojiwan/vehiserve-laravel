@extends('layouts.staff')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pending Trip Requests</h1>
            <p class="text-gray-500 mt-1">Review and assign vehicles for pending motor pool approvals</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Personnel</th>
                            <th>Destination</th>
                            <th>Submitted</th>
                            <th>Current Stage</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendingTrips as $trip)
                        <tr>
                            <td>#{{ $trip->id }}</td>
                            <td>
                                <span class="badge {{ $trip->inclusive_date ? 'badge-primary' : 'badge-secondary' }}">
                                    {{ $trip->inclusive_date ? 'Travel' : 'Vehicle' }}
                                </span>
                            </td>
                            <td>
                                {{ $trip->inclusive_date 
                                    ? $trip->inclusive_date->format('M d, Y') 
                                    : $trip->departure_date->format('M d, Y') }}
                            </td>
                            <td>{{ $trip->personnel_name ?? $trip->requesting_person }}</td>
                            <td>{{ $trip->destination }}</td>
                            <td>{{ $trip->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <span class="badge badge-warning">{{ $trip->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('staff.trips.show', $trip) }}" class="btn btn-primary btn-sm">
                                    Assign Vehicle
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-12">
                                <div class="empty-state">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    <p class="empty-state-title">No pending trip requests</p>
                                    <p class="empty-state-description">All requests have been processed</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $pendingTrips->links() }}
            </div>
        </div>
    </div>
</div>
@endsection