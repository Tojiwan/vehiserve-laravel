<div class="space-y-6">
    <!-- Completed Vehicle Requests -->
    <div class="card">
        <div class="card-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-900">Completed Vehicle Requests</h2>
            <div class="flex items-center gap-3">
                <input type="text" wire:model.live.debounce.300ms="vehicleSearch" placeholder="Search by name, destination, or ID..." class="input w-64">
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Request Date</th>
                            <th>Requesting Person</th>
                            <th>Destination</th>
                            <th>Date Completed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vehicleRequests as $request)
                        <tr>
                            <td>#{{ $request->id }}</td>
                            <td>{{ $request->request_date->format('M d, Y') }}</td>
                            <td>{{ $request->requesting_person }}</td>
                            <td>{{ $request->destination }}</td>
                            <td>{{ $request->updated_at->format('M d, Y') }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <button wire:click="$dispatch('download-pdf', { type: 'vehicle', id: {{ $request->id }})" class="btn btn-ghost btn-sm btn-icon" title="Download PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </button>
                                    <a href="{{ route('user.document-tracking.detail', ['type' => 'vehicle', 'id' => $request->id]) }}" class="btn btn-ghost btn-sm btn-icon" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <div class="empty-state">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="empty-state-title">No completed vehicle requests</p>
                                    <p class="empty-state-description">{{ $vehicleSearch ? 'Try adjusting your search' : 'Approved requests will appear here' }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $vehicleRequests->links() }}
            </div>
        </div>
    </div>

    <!-- Completed Travel Requests -->
    <div class="card">
        <div class="card-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-900">Completed Travel Requests</h2>
            <div class="flex items-center gap-3">
                <input type="text" wire:model.live.debounce.300ms="travelSearch" placeholder="Search by name, destination, or ID..." class="input w-64">
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Inclusive Date</th>
                            <th>Personnel Name</th>
                            <th>Destination</th>
                            <th>Date Completed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($travelRequests as $request)
                        <tr>
                            <td>#{{ $request->id }}</td>
                            <td>{{ $request->inclusive_date->format('M d, Y') }}</td>
                            <td>{{ $request->personnel_name }}</td>
                            <td>{{ $request->destination }}</td>
                            <td>{{ $request->updated_at->format('M d, Y') }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <button wire:click="$dispatch('download-pdf', { type: 'travel', id: {{ $request->id }})" class="btn btn-ghost btn-sm btn-icon" title="Download PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </button>
                                    <a href="{{ route('user.document-tracking.detail', ['type' => 'travel', 'id' => $request->id]) }}" class="btn btn-ghost btn-sm btn-icon" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <div class="empty-state">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="empty-state-title">No completed travel requests</p>
                                    <p class="empty-state-description">{{ $travelSearch ? 'Try adjusting your search' : 'Approved requests will appear here' }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $travelRequests->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', () => {
        Livewire.on('trigger-download', (event) => {
            if (event.url) {
                window.open(event.url, '_blank');
            }
        });
    });
</script>
@endpush