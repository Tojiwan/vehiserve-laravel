<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Document Tracking</h1>
        <a href="{{ route('user.trip-request.create') }}" class="btn btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            New Trip Request
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="card">
        <div class="card-body px-6 py-3 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, destination, or ID..." class="input w-full sm:w-64">
                    <select wire:model="statusFilter" class="input w-40">
                        <option value="all">All Statuses</option>
                        <option value="Pending Dean">Pending Dean</option>
                        <option value="Approved by Dean">Approved by Dean</option>
                        <option value="Rejected by Dean">Rejected by Dean</option>
                        <option value="Pending Vice President">Pending Vice President</option>
                        <option value="Approved by Vice President">Approved by Vice President</option>
                        <option value="Rejected by Vice President">Rejected by Vice President</option>
                        <option value="Pending SUC President">Pending SUC President</option>
                        <option value="Approved by SUC President">Approved by SUC President</option>
                        <option value="Rejected by SUC President">Rejected by SUC President</option>
                        <option value="Pending Motor Pool">Pending Motor Pool</option>
                        <option value="Vehicle Assigned">Vehicle Assigned</option>
                        <option value="No Vehicle Available">No Vehicle Available</option>
                        <option value="Pending Final MP Approval">Pending Final MP Approval</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled by User">Cancelled by User</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                    <select wire:model="sortField" class="input w-40">
                        <option value="created_at">Date Created</option>
                        <option value="inclusive_date">Inclusive Date</option>
                        <option value="departure_date">Departure Date</option>
                        <option value="destination">Destination</option>
                        <option value="status">Status</option>
                    </select>
                    <button wire:click="$set('sortDirection', sortDirection === 'asc' ? 'desc' : 'asc')" class="btn btn-ghost btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M3 4h13M3 8h13m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4' : 'M3 4h13M3 8h13m-9 4h9m5-4v12m0 0l4-4m-4 4l-4-4' }}"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Trip Requests List -->
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
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tripRequests as $request)
                        <tr>
                            <td>#{{ $request->id }}</td>
                            <td>
                                <span class="badge {{ $request->inclusive_date ? 'badge-primary' : 'badge-secondary' }}">
                                    {{ $request->inclusive_date ? 'Travel' : 'Vehicle' }}
                                </span>
                            </td>
                            <td>
                                {{ $request->inclusive_date 
                                    ? $request->inclusive_date->format('M d, Y') 
                                    : $request->departure_date->format('M d, Y') }}
                            </td>
                            <td>{{ $request->personnel_name ?? $request->requesting_person }}</td>
                            <td>{{ $request->destination }}</td>
                            <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <span class="badge 
                                    @if(str_contains($request->status, 'Pending')) badge-warning
                                    @elseif(str_contains($request->status, 'Approved') || str_contains($request->status, 'Completed') || str_contains($request->status, 'Vehicle Assigned')) badge-success
                                    @elseif(str_contains($request->status, 'Rejected') || str_contains($request->status, 'No Vehicle')) badge-danger
                                    @elseif(str_contains($request->status, 'Cancelled')) badge-gray
                                    @else badge-info
                                    @endif
                                ">
                                    {{ $request->status }}
                                </span>
                            </td>
                            <td>
                                <livewire:shared.progress-tracker 
                                    :steps="$request->inclusive_date
                                        ? [['label' => 'Dean', 'status_key' => 'Pending Dean'], ['label' => 'VP', 'status_key' => 'Pending VP'], ['label' => 'SUC', 'status_key' => 'Pending SUC'], ['label' => $this->outcomeLabel($request->status) ?? 'MP', 'status_key' => 'Pending Motor Pool']]
                                        : [['label' => 'MP', 'status_key' => 'Pending Motor Pool'], ['label' => 'Dean', 'status_key' => 'Pending Dean'], ['label' => 'VP', 'status_key' => 'Pending VP'], ['label' => 'SUC', 'status_key' => 'Pending SUC'], ['label' => $this->outcomeLabel($request->status) ?? 'Final MP', 'status_key' => 'Pending Final MP Approval']]"
                                    :currentStep="$this->getStepIndex($request->status)"
                                    :rejectedStep="$this->getRejectedStepIndex($request->status)"
                                    :cancelled="$request->status === 'Cancelled by User'"
                                />
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('user.document-tracking.detail', ['type' => 'trip', 'id' => $request->id]) }}" class="btn btn-ghost btn-sm btn-icon" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @if($this->isCancellable($request->status))
                                    <button wire:click="$dispatch('confirm-modal:show', ['title' => 'Cancel Request', 'message' => 'Are you sure you want to cancel this trip request?', 'confirmAction' => 'cancelTripRequest', 'confirmParams' => [{'id' => $request->id}], 'variant' => 'danger'])" class="btn btn-ghost btn-sm btn-icon text-red-500 hover:text-red-700" title="Cancel Request">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-12">
                                <div class="empty-state">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    <p class="empty-state-title">No trip requests yet</p>
                                    <p class="empty-state-description">Create your first trip request</p>
                                    <a href="{{ route('user.trip-request.create') }}" class="btn btn-primary mt-4">Create Request</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $tripRequests->links() }}
            </div>
        </div>
    </div>

    <!-- Confirm Modal -->
    <livewire:shared.confirm-modal />
</div>