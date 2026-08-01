<div class="card">
    <div class="card-header">
        <h2 class="text-lg font-semibold text-gray-900">Vehicle Request Form</h2>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="submit" class="space-y-6">
            @if (session()->has('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <!-- Request Date & Requesting Person -->
            <div class="form-row">
                <div class="form-group">
                    <label class="label" for="request_date">Date of Request <span class="text-red-500">*</span></label>
                    <input type="date" id="request_date" wire:model="request_date" class="input" required>
                    @error('request_date') <p class="error-message">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="label" for="requesting_person">Name of Requesting Personnel <span class="text-red-500">*</span></label>
                    <input type="text" id="requesting_person" wire:model="requesting_person" class="input" required placeholder="Enter full name">
                    @error('requesting_person') <p class="error-message">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Office/College -->
            <div class="form-group">
                <label class="label" for="office_college">Office/College <span class="text-red-500">*</span></label>
                <select id="office_college" wire:model="office_college" class="input" required>
                    <option value="">Select Office/College</option>
                    @foreach ($offices as $office)
                    <option value="{{ $office }}">{{ $office }}</option>
                    @endforeach
                </select>
                @error('office_college') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <!-- Other Office Input -->
            @if ($showOtherOffice)
            <div class="form-group">
                <label class="label" for="other_office">Please Specify <span class="text-red-500">*</span></label>
                <input type="text" id="other_office" wire:model="other_office" class="input" required placeholder="Enter other office/college">
            </div>
            @endif

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
                        <button type="button" wire:click="$set('passengers.{{ $index }}', '')" class="btn btn-ghost btn-sm btn-icon text-red-500 hover:text-red-700">
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

            <!-- File Uploads -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Signature -->
                <div class="form-group">
                    <label class="label">Upload Signature (PNG, JPG, JPEG) <span class="text-red-500">*</span></label>
                    <div class="file-upload" wire:click="$set('signature', null)">
                        <input type="file" wire:model="signature" accept=".jpg,.jpeg,.png" class="hidden" id="signature-upload" @change="$set('signature', $event.target.files[0])">
                        @if ($signature)
                        <div class="file-upload-preview">
                            <div class="file-preview-item">
                                <img src="{{ $signature->temporaryUrl() }}" alt="Signature preview" class="w-full h-full object-cover">
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
                        <p class="text-sm text-gray-400">PNG, JPG, JPEG (Max 2MB)</p>
                        @endif
                    </div>
                    @error('signature') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Valid ID -->
                <div class="form-group">
                    <label class="label">Upload Valid ID (PNG, JPG, JPEG) <span class="text-red-500">*</span></label>
                    <div class="file-upload" wire:click="$set('valid_id', null)">
                        <input type="file" wire:model="valid_id" accept=".jpg,.jpeg,.png" class="hidden" id="valid-id-upload" @change="$set('valid_id', $event.target.files[0])">
                        @if ($valid_id)
                        <div class="file-upload-preview">
                            <div class="file-preview-item">
                                <img src="{{ $valid_id->temporaryUrl() }}" alt="Valid ID preview" class="w-full h-full object-cover">
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
                    </div>
                    @error('valid_id') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Memo -->
                <div class="form-group">
                    <label class="label">Upload Memo (PNG, JPG, JPEG)</label>
                    <div class="file-upload" wire:click="$set('memo', null)">
                        <input type="file" wire:model="memo" accept=".jpg,.jpeg,.png" class="hidden" id="memo-upload" @change="$set('memo', $event.target.files[0])">
                        @if ($memo)
                        <div class="file-upload-preview">
                            <div class="file-preview-item">
                                <img src="{{ $memo->temporaryUrl() }}" alt="Memo preview" class="w-full h-full object-cover">
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
                    </div>
                    @error('memo') <p class="error-message">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="btn btn-primary btn-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Submit & Proceed to Travel Order
                </button>
            </div>
        </form>
    </div>
</div>