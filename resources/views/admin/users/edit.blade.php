@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-900">Edit User</h1>
            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')

                @if ($errors->any())
                <div class="alert alert-error">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Name -->
                <div class="form-group">
                    <label class="label" for="name">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" class="input" required value="{{ $user->name }}">
                    @error('name') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="label" for="email">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" class="input" required value="{{ $user->email }}">
                    @error('email') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Password (optional) -->
                <div class="form-group">
                    <label class="label" for="password">New Password (leave blank to keep current)</label>
                    <input type="password" id="password" name="password" class="input" minlength="8">
                    @error('password') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label class="label" for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="input" minlength="8">
                </div>

                <!-- Roles -->
                <div class="form-group">
                    <label class="label">Roles <span class="text-red-500">*</span></label>
                    <div class="space-y-2">
                        @foreach($roles as $role)
                        <label class="checkbox-group">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                            <span class="ml-2">{{ ucfirst($role->name) }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('roles') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Email Verified -->
                <div class="form-group">
                    <label class="checkbox-group">
                        <input type="checkbox" name="email_verified" {{ $user->email_verified_at ? 'checked' : '' }} value="1">
                        <span class="ml-2">Email Verified</span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection