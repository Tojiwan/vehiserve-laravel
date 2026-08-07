<div class="max-w-3xl mx-auto space-y-6">
    <div class="card">
        <div class="card-header">
            <h2 class="text-xl font-semibold text-gray-900">Profile Settings</h2>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="updateProfile" class="space-y-6">
                <!-- Avatar -->
                <div class="flex items-center gap-4">
                    <div class="relative">
                        @if (auth()->user()->getFirstMedia('avatar'))
                        <img src="{{ auth()->user()->getFirstMediaUrl('avatar') }}" alt="Avatar" class="avatar-lg">
                        @else
                        <div class="avatar-lg bg-gray-200 flex items-center justify-center text-gray-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        @endif
                        <input type="file" wire:model="avatar" accept="image/*" class="hidden" id="avatar-upload">
                        <label for="avatar-upload" class="btn btn-secondary btn-sm mt-2">Change Avatar</label>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                        @if (auth()->user()->getRoleNames()->count())
                        <span class="badge badge-primary mt-1">{{ auth()->user()->getRoleNames()->first() }}</span>
                        @endif
                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Name -->
                <div class="form-group">
                    <label class="label" for="name">Full Name</label>
                    <input type="text" id="name" wire:model="name" class="input" required>
                    @error('name') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="label" for="email">Email Address</label>
                    <input type="email" id="email" wire:model="email" class="input" required>
                    @error('email') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Save Button -->
                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Change -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-xl font-semibold text-gray-900">Change Password</h2>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="updatePassword" class="space-y-4">
                <div class="form-group">
                    <label class="label" for="current_password">Current Password</label>
                    <input type="password" id="current_password" wire:model="current_password" class="input" required>
                    @error('current_password') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="label" for="new_password">New Password</label>
                    <input type="password" id="new_password" wire:model="new_password" class="input" required minlength="8">
                    @error('new_password') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="label" for="new_password_confirmation">Confirm New Password</label>
                    <input type="password" id="new_password_confirmation" wire:model="new_password_confirmation" class="input" required>
                    @error('new_password_confirmation') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>

