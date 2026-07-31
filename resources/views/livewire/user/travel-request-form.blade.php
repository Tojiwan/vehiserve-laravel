<div class="max-w-4xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h2 class="text-xl font-semibold text-gray-900">Travel Order Request Form</h2>
            <p class="text-gray-500 mt-1">Fill out the travel order details below</p>
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

                <!-- Personnel Name -->
                <div class="form-group">
                    <label class="label" for="personnel_name">Name of Personnel <span class="text-red-500">*</span></label>
                    <input type="text" id="personnel_name" wire:model="personnel_name" class="input" required placeholder="Enter personnel name">
                    @error('personnel_name') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Official Station -->
                <div class="form-group">
                    <label class="label" for="official_station">Official Station <span class="text-red-500">*</span></label>
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

                    <div class="form-group">
                        <label class="label" for="vehicle_request">Vehicle Request <span class="text-red-500">*</span></label>
                        <select id="vehicle_request" wire:model="vehicle_request" class="input" required>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                            <option value="N/A">N/A</option>
                        </select>
                        @error('vehicle_request') <p class="error-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- File Uploads -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Submit Travel Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>