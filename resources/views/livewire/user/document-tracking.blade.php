<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Document Tracking</h1>
    </div>

    <!-- Vehicle Requests Section -->
    <div class="card">
        <div class="card-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-900">Vehicle Requests</h2>
            <div class="flex items-center gap-3">
                <input type="text" wire:model.live.debounce.300ms="vehicleSearch" placeholder="Search by name, destination, or ID..." class="input w-64">
                <select wire:model="vehicleSortField" class="input w-40">
                    <option value="created_at">Date Created</option>
                    <option value="request_date">Request Date</option>
                    <option value="destination">Destination</option>
                    <option value="vehicle_status">Status</option>
                </select>
                <button wire:click="$set('vehicleSortDirection', vehicleSortDirection === 'asc' ? 'desc' : 'asc')" class="btn btn-ghost btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $vehicleSortDirection === 'asc' ? 'M3 4h13M3 8h13m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4' : 'M3 4h13M3 8h13m-9 4h9m5-4v12m0 0l4-4m-4 4l-4-4' }}"></path>
                    </svg>
                </button>
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
                            <th>Submitted</th>
                            <th>Progress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vehicleRequests as $request)
                        <tr>
                            <td>#{{ $request->id }}</td>
                            <td>{{ $request->request_date->format('M d, Y') }}</td>
                            <td>{{ $request->requesting_person }}</td>
                            <td>{{ $request->destination }}</td>
                            <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <livewire:shared.progress-tracker 
                                    :steps="[
                                        ['label' => 'Motor Pool'],
                                        ['label' => 'Dean'],
                                        ['label' => 'VP'],
                                        ['label' => 'SUC Pres'],
                                        ['label' => 'Final MP']
                                    ]"
                                    :currentStep="$this->getVehicleStepIndex($request->vehicle_status)"
                                    :rejectedStep="$this->getVehicleRejectedStepIndex($request->vehicle_status)"
                                    :cancelled="$request->vehicle_status === 'Cancelled by User'"
                                />
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('user.document-tracking.detail', ['type' => 'vehicle', 'id' => $request->id]) }}" class="btn btn-ghost btn-sm btn-icon" title="View Progress">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @if(in_array($request->vehicle_status, ['Pending Motor Pool', 'Pending Dean', 'Pending VP', 'Pending SUC', 'Pending Final MP Approval']))
                                    <button wire:click="$dispatch('confirm-modal:show', ['title' => 'Cancel Request', 'message' => 'Are you sure you want to cancel this vehicle request?', 'confirmAction' => 'cancelVehicleRequest', 'confirmParams' => [{'id' => $request->id}], 'variant' => 'danger'])" class="btn btn-ghost btn-sm btn-icon text-red-500 hover:text-red-700" title="Cancel Request">
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
                            <td colspan="7" class="text-center py-12">
                                <div class="empty-state">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    <p class="empty-state-title">{{ $vehicleSearch ? 'No matching vehicle requests found' : 'No vehicle requests yet' }}</p>
                                    <p class="empty-state-description">{{ $vehicleSearch ? 'Try adjusting your search' : 'Create your first vehicle request' }}</p>
                                    @if(!$vehicleSearch)
                                    <a href="{{ route('user.vehicle-request.create') }}" class="btn btn-primary mt-4">Create Request</a>
                                    @endif
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

    <!-- Travel Requests Section -->
    <div class="card">
        <div class="card-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-900">Travel Requests</h2>
            <div class="flex items-center gap-3">
                <input type="text" wire:model.live.debounce.300ms="travelSearch" placeholder="Search by name, destination, or ID..." class="input w-64">
                <select wire:model="travelSortField" class="input w-40">
                    <option value="created_at">Date Created</option>
                    <option value="inclusive_date">Inclusive Date</option>
                    <option value="destination">Destination</option>
                    <option value="vehicle_status">Status</option>
                </select>
                <button wire:click="$set('travelSortDirection', travelSortDirection === 'asc' ? 'desc' : 'asc')" class="btn btn-ghost btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $travelSortDirection === 'asc' ? 'M3 4h13M3 8h13m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4' : 'M3 4h13M3 8h13m-9 4h9m5-4v12m0 0l4-4m-4 4l-4-4' }}"></path>
                    </svg>
                </button>
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
                            <th>Submitted</th>
                            <th>Progress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($travelRequests as $request)
                        <tr>
                            <td>#{{ $request->id }}</td>
                            <td>{{ $request->inclusive_date->format('M d, Y') }}</td>
                            <td>{{ $request->personnel_name }}</td>
                            <td>{{ $request->destination }}</td>
                            <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <livewire:shared.progress-tracker 
                                    :steps="[
                                        ['label' => 'Dean'],
                                        ['label' => 'VP'],
                                        ['label' => 'SUC Pres'],
                                        ['label' => 'Motor Pool']
                                    ]"
                                    :currentStep="$this->getTravelStepIndex($request->vehicle_status)"
                                    :rejectedStep="$this->getTravelRejectedStepIndex($request->vehicle_status)"
                                    :cancelled="$request->vehicle_status === 'Cancelled by User'"
                                />
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('user.document-tracking.detail', ['type' => 'travel', 'id' => $request->id]) }}" class="btn btn-ghost btn-sm btn-icon" title="View Progress">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @if(in_array($request->vehicle_status, ['Pending Dean', 'Pending VP', 'Pending SUC', 'Pending Motor Pool']))
                                    <button wire:click="$dispatch('confirm-modal:show', ['title' => 'Cancel Request', 'message' => 'Are you sure you want to cancel this travel request?', 'confirmAction' => 'cancelTravelRequest', 'confirmParams' => [{'id' => $request->id}], 'variant' => 'danger'])" class="btn btn-ghost btn-sm btn-icon text-red-500 hover:text-red-700" title="Cancel Request">
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
                            <td colspan="7" class="text-center py-12">
                                <div class="empty-state">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    <p class="empty-state-title">{{ $travelSearch ? 'No matching travel requests found' : 'No travel requests yet' }}</p>
                                    <p class="empty-state-description">{{ $travelSearch ? 'Try adjusting your search' : 'Create your first travel request' }}</p>
                                    @if(!$travelSearch)
                                    <a href="{{ route('user.travel-request.create') }}" class="btn btn-primary mt-4">Create Request</a>
                                    @endif
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

    <!-- Confirm Modal -->
    <livewire:shared.confirm-modal />
</div>