<div class="max-w-4xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h2 class="text-xl font-semibold text-gray-900">Trip Request Form</h2>
            <p class="text-gray-500 mt-1">Fill out both travel and vehicle request details below</p>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="submit" class="space-y-6">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-error">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Travel Request Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Travel Request Details</h3>

                    <!-- Request Date -->
                    <div class="form-group">
                        <label class="label" for="request_date">Request Date <span class="text-red-500">*</span></label>
                        <input type="date" id="request_date" wire:model="request_date" class="input" required>
                        @error('request_date') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Personnel Name -->
                    <div class="form-group">
                        <label class="label" for="personnel_name">Name of Personnel <span class="text-red-500">*</span></label>
                        <input type="text" id="personnel_name" wire:model="personnel_name" class="input" required placeholder="Enter personnel name">
                        @error('personnel_name') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Official Station -->
                    <div class="form-group">
                        <label class="label" for="official_station">Official Station/Office <span class="text-red-500">*</span></label>
                        <input type="text" id="official_station" wire:model="official_station" class="input" required placeholder="Enter official station/office">
                        @error('official_station') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Destination -->
                    <div class="form-group">
                        <label class="label" for="destination">Destination(s) <span class="text-red-500">*</span></label>
                        <input type="text" id="destination" wire:model="destination" class="input" required placeholder="Enter destination">
                        @error('destination') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Purpose -->
                    <div class="form-group">
                        <label class="label" for="purpose">Purpose(s) <span class="text-red-500">*</span></label>
                        <textarea id="purpose" wire:model="purpose" class="input min-h-[100px]" required placeholder="Enter purpose of travel"></textarea>
                        @error('purpose') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Inclusive Date -->
                    <div class="form-group">
                        <label class="label" for="inclusive_date">Inclusive Date <span class="text-red-500">*</span></label>
                        <input type="date" id="inclusive_date" wire:model="inclusive_date" class="input" required>
                        @error('inclusive_date') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Requesting For & Vehicle Request -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="label" for="requesting_for">Requesting For <span class="text-red-500">*</span></label>
                            <select id="requesting_for" wire:model="requesting_for" class="input" required>
                                <option value="Cash Advance">Cash Advance</option>
                                <option value="Reimbursement">Reimbursement</option>
                                <option value="N/A">N/A</option>
                            </select>
                            @error('requesting_for') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Vehicle Request Section -->
                <div class="border-b border-gray-200 pb-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Request Details</h3>

                    <!-- Departure Date & Time -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="label" for="departure_date">Departure Date <span class="text-red-500">*</span></label>
                            <input type="date" id="departure_date" wire:model="departure_date" class="input" required>
                            @error('departure_date') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="label" for="departure_time">Departure Time <span class="text-red-500">*</span></label>
                            <input type="time" id="departure_time" wire:model="departure_time" class="input" required>
                            @error('departure_time') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Return Date -->
                    <div class="form-group">
                        <label class="label" for="return_date">Return Date <span class="text-red-500">*</span></label>
                        <input type="date" id="return_date" wire:model="return_date" class="input" required>
                        @error('return_date') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Number of Passengers -->
                    <div class="form-group">
                        <label class="label" for="num_passengers">Number of Passengers <span class="text-red-500">*</span></label>
                        <input type="number" id="num_passengers" wire:model="num_passengers" class="input" min="1" max="50" required>
                        @error('num_passengers') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Passenger Names -->
                    <div class="form-group">
                        <label class="label">Name of Passenger(s) <span class="text-red-500">*</span></label>
                        <div class="space-y-2">
                            @foreach ($passengers as $index => $passenger)
                            <div class="flex items-center gap-2">
                                <input type="text" wire:model="passengers.{{ $index }}" class="input" placeholder="Passenger {{ $index + 1 }} name">
                                @if ($index > 0)
                                <button type="button" wire:click="$set('passengers.{{ $index }}', '')" class="btn btn-ghost btn-sm btn-icon text-red-500 hover:text-red-700" aria-label="Remove passenger">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @error('passengers.*') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Vehicle Selection -->
                    <div class="form-group">
                        <label class="label" for="vehicle_ID">Vehicle <span class="text-red-500">*</span></label>
                        <select id="vehicle_ID" wire:model="vehicle_ID" class="input" required>
                            <option value="">Select Vehicle</option>
                            @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->vehicle_ID }}">
                                {{ $vehicle->vehicle_name }} ({{ $vehicle->plate_number }}) - Capacity: {{ $vehicle->capacity }}
                            </option>
                            @endforeach
                        </select>
                        @error('vehicle_ID') <p class="error-message">{{ $message }}</p> @enderror
                        @if ($vehicleConflict)
                        <p class="error-message mt-1">Vehicle is already booked for the selected date range. Please choose another vehicle or date range.</p>
                        @endif
                    </div>

                    <!-- Driver Selection -->
                    <div class="form-group">
                        <label class="label" for="driver_ID">Driver (Optional - Staff will assign at final approval)</label>
                        <select id="driver_ID" wire:model="driver_ID" class="input">
                            <option value="">Auto-assign by staff</option>
                            @foreach ($drivers as $driver)
                            <option value="{{ $driver->driver_ID }}">
                                {{ $driver->full_name }} ({{ $driver->license_number }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- File Uploads -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="{ previewUrl: null }">
                        <!-- Signature -->
                        <div class="form-group">
                            <label class="label">Upload Signature (PNG, JPG, JPEG) <span class="text-red-500">*</span></label>
                            <div class="file-upload">
                                <label for="signature-upload" class="cursor-pointer">
                                    <input type="file" wire:model.live="signature" accept=".jpg,.jpeg,.png" class="hidden" id="signature-upload">
                                    @if ($signature)
                                    <div class="file-upload-preview">
                                        <div class="file-preview-item">
                                            <img src="{{ $signature->temporaryUrl() }}" alt="Signature preview" class="w-full h-full object-cover">
                                            <button type="button" @click.prevent="previewUrl = '{{ $signature->temporaryUrl() }}'" class="absolute top-1 left-1 bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center" title="Preview">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="$set('signature', null)" class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                                                ×
                                            </button>
                                        </div>
                                    </div>
                                    @else
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="text-gray-600">Click to upload signature</p>
                                    <p class="text-sm text-gray-400">PNG, JPG, JPEG (Max 2MB) <span class="text-red-300"> Note: Image should have a transparent BG.</span> </p>
                                    @endif
                                </label>
                            </div>
                            @error('signature') <p class="error-message">{{ $message }}</p> @enderror
                        </div>

                        <!-- Valid ID -->
                        <div class="form-group">
                            <label class="label">Upload Valid ID (PNG, JPG, JPEG) <span class="text-red-500">*</span></label>
                            <div class="file-upload">
                                <label for="valid-id-upload" class="cursor-pointer">
                                    <input type="file" wire:model.live="valid_id" accept=".jpg,.jpeg,.png" class="hidden" id="valid-id-upload">
                                    @if ($valid_id)
                                    <div class="file-upload-preview">
                                        <div class="file-preview-item">
                                            <img src="{{ $valid_id->temporaryUrl() }}" alt="Valid ID preview" class="w-full h-full object-cover">
                                            <button type="button" @click.prevent="previewUrl = '{{ $valid_id->temporaryUrl() }}'" class="absolute top-1 left-1 bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center" title="Preview">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="$set('valid_id', null)" class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                                                ×
                                            </button>
                                        </div>
                                    </div>
                                    @else
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <p class="text-gray-600">Click to upload valid ID</p>
                                    <p class="text-sm text-gray-400">PNG, JPG, JPEG (Max 2MB)</p>
                                    @endif
                                </label>
                            </div>
                            @error('valid_id') <p class="error-message">{{ $message }}</p> @enderror
                        </div>

                        <!-- Memo -->
                        <div class="form-group">
                            <label class="label">Upload Memo (PNG, JPG, JPEG)</label>
                            <div class="file-upload">
                                <label for="memo-upload" class="cursor-pointer">
                                    <input type="file" wire:model.live="memo" accept=".jpg,.jpeg,.png" class="hidden" id="memo-upload">
                                    @if ($memo)
                                    <div class="file-upload-preview">
                                        <div class="file-preview-item">
                                            <img src="{{ $memo->temporaryUrl() }}" alt="Memo preview" class="w-full h-full object-cover">
                                            <button type="button" @click.prevent="previewUrl = '{{ $memo->temporaryUrl() }}'" class="absolute top-1 left-1 bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center" title="Preview">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="$set('memo', null)" class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                                                ×
                                            </button>
                                        </div>
                                    </div>
                                    @else
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-600">Click to upload memo</p>
                                    <p class="text-sm text-gray-400">PNG, JPG, JPEG (Max 5MB)</p>
                                    @endif
                                </label>
                            </div>
                            @error('memo') <p class="error-message">{{ $message }}</p> @enderror
                        </div>

                        <!-- Preview Popup -->
                        <div x-show="previewUrl" x-cloak class="modal-overlay" @keydown.escape.window="previewUrl = null" @click.self="previewUrl = null">
                            <div class="relative">
                                <img :src="previewUrl" alt="Preview" class="max-w-full max-h-[85vh] rounded-xl shadow-2xl bg-white p-2">
                                <button type="button" @click="previewUrl = null" class="absolute -top-4 -right-4 bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center text-2xl shadow-lg hover:bg-red-700" aria-label="Close preview">
                                    ×
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Submit Trip Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>