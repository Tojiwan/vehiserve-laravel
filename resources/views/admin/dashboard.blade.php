@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-500 mt-1">System administration and overview</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalUsers ?? 0 }}</p>
                    </div>
                    <div class="stat-card-icon bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1h0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Vehicles</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalVehicles ?? 0 }}</p>
                    </div>
                    <div class="stat-card-icon bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Drivers</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalDrivers ?? 0 }}</p>
                    </div>
                    <div class="stat-card-icon bg-amber-100 text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Pending Requests</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingRequests ?? 0 }}</p>
                    </div>
                    <div class="stat-card-icon bg-red-100 text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
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
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1h0z"></path>
                    </svg>
                    <span>Manage Users</span>
                </a>
                <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Manage Vehicles</span>
                </a>
                <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Manage Drivers</span>
                </a>
                <a href="{{ route('admin.reports') }}" class="btn btn-secondary w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Reports</span>
                </a>
            </div>
        </div>
    </div>

    <!-- System Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Recent Users</h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-red-600 hover:text-red-700">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->roles->first() ? 'badge-primary' : 'badge-gray' }}">
                                        {{ $user->roles->first()->name ?? 'None' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $user->email_verified_at ? 'badge-success' : 'badge-warning' }}">
                                        {{ $user->email_verified_at ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="empty-state">
                                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1h0z"></path>
                                        </svg>
                                        <p class="empty-state-title">No users found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">System Health</h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-500">Database</span>
                            <span class="badge badge-success">Healthy</span>
                        </div>
                        <div class="text-sm text-gray-500">SQLite - Connected</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-500">Storage</span>
                            <span class="badge badge-success">OK</span>
                        </div>
                        <div class="text-sm text-gray-500">Local - Writable</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-500">Queue</span>
                            <span class="badge {{ $queueStatus === 'running' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst($queueStatus ?? 'unknown') }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">Jobs: {{ $queueJobsCount ?? 0 }} pending</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-500">Cache</span>
                            <span class="badge badge-success">Active</span>
                        </div>
                        <div class="text-sm text-gray-500">Database driver</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Recent Activity</h2>
            <a href="{{ route('admin.activity') }}" class="text-sm text-red-600 hover:text-red-700">View All</a>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Subject</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentActivity as $activity)
                        <tr>
                            <td>{{ $activity->causer->name ?? 'System' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $activity->description)) }}</td>
                            <td>{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</td>
                            <td>{{ $activity->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-12">
                                <div class="empty-state">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3-2-1.343-2-3-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2v-6a2 2 0 00-2-2zM12 9v4m0 4h.01M5 11a1 1 0 011-1h12a1 1 0 110 2H6a1 1 0 01-1-1z"></path>
                                    </svg>
                                    <p class="empty-state-title">No recent activity</p>
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
