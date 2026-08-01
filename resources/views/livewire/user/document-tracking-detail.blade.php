<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $type === 'vehicle' ? 'Vehicle Request' : 'Travel Request' }} Details
            </h1>
            <p class="text-gray-500 mt-1">#{{ $request->id }} - {{ $request->destination }}</p>
        </div>
        <a href="{{ $type === 'vehicle' ? route('user.document-tracking') : route('user.document-tracking') }}" class="btn btn-secondary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Tracking
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
                    <p class="font-semibold text-gray-900">#{{ $request->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">
                        {{ $type === 'vehicle' ? 'Request Date' : 'Inclusive Date' }}
                    </p>
                    <p class="font-semibold text-gray-900">
                        {{ $type === 'vehicle' 
                            ? $request->request_date->format('M d, Y') 
                            : $request->inclusive_date->format('M d, Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">
                        {{ $type === 'vehicle' ? 'Requesting Person' : 'Personnel Name' }}
                    </p>
                    <p class="font-semibold text-gray-900">
                        {{ $type === 'vehicle' ? $request->requesting_person : $request->personnel_name }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Destination</p>
                    <p class="font-semibold text-gray-900">{{ $request->destination }}</p>
                </div>
            </div>

            @if ($type === 'vehicle')
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Departure Date</p>
                    <p class="font-semibold text-gray-900">{{ $request->departure_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Return Date</p>
                    <p class="font-semibold text-gray-900">{{ $request->return_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Departure Time</p>
                    <p class="font-semibold text-gray-900">{{ $request->departure_time }}</p>
                </div>
            </div>

            <div class="mt-6">
                <p class="text-sm text-gray-500">Purpose</p>
                <p class="text-gray-900">{{ $request->purpose }}</p>
            </div>

            @if ($request->passengers->count() > 0)
            <div class="mt-6">
                <p class="text-sm text-gray-500">Passengers ({{ $request->passengers->count() }})</p>
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach ($request->passengers as $passenger)
                    <span class="badge badge-primary">{{ $passenger->passenger_name }}</span>
                    @endforeach
                </div>
            </div>
            @endif
            @else
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Official Station</p>
                    <p class="font-semibold text-gray-900">{{ $request->official_station }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Inclusive Date</p>
                    <p class="font-semibold text-gray-900">{{ $request->inclusive_date->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Requesting For</p>
                    <p class="font-semibold text-gray-900">{{ $request->requesting_for }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Vehicle Request</p>
                    <p class="font-semibold text-gray-900">{{ $request->vehicle_request }}</p>
                </div>
            </div>

            <div class="mt-6">
                <p class="text-sm text-gray-500">Purpose</p>
                <p class="text-gray-900">{{ $request->purpose }}</p>
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

    <!-- Approval Timeline -->
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

    <!-- Documents -->
    @if ($documents->count() > 0)
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Documents</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($documents as $document)
                <div class="card border border-gray-200">
                    <div class="card-body p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $document->file_name }}</p>
                                <p class="text-xs text-gray-500">{{ $document->type }}</p>
                                <p class="text-xs text-gray-400">{{ $document->file_size ? number_format($document->file_size / 1024, 1) . ' KB' : 'Unknown size' }}</p>
                            </div>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-secondary btn-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View
                            </a>
                            <a href="{{ route('document.download', ['path' => $document->file_path]) }}" class="btn btn-primary btn-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if ($request->vehicle_status === 'Cancelled by User' || $request->vehicle_status === 'Completed')
    <div class="mt-6 text-center">
        <span class="badge {{ $request->vehicle_status === 'Completed' ? 'badge-success' : 'badge-gray' }} text-lg px-4 py-2">
            {{ $request->vehicle_status }}
        </span>
    </div>
    @endif
</div>