@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h1 class="text-xl font-bold text-gray-900">Create New User</h1>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                @csrf

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
                    <input type="text" id="name" name="name" wire:model="name" class="input" required value="{{ old('name') }}">
                    @error('name') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="label" for="email">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" class="input" required value="{{ old('email') }}">
                    @error('email') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="label" for="password">Password <span class="text-red-500">*</span></label>
                    <input type="password" id="password" name="password" class="input" required minlength="8">
                    @error('password') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label class="label" for="password_confirmation">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="input" required minlength="8">
                </div>

                <!-- Roles -->
                <div class="form-group">
                    <label class="label">Roles <span class="text-red-500">*</span></label>
                    <div class="space-y-2">
                        @foreach($roles as $role)
                        <label class="checkbox-group">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ old('roles') && in_array($role->name, old('roles')) ? 'checked' : '' }}>
                            <span class="ml-2">{{ ucfirst($role->name) }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('roles') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <!-- Email Verified -->
                <div class="form-group">
                    <label class="checkbox-group">
                        <input type="checkbox" name="email_verified" {{ old('email_verified') ? 'checked' : '' }} value="1">
                        <span class="ml-2">Email Verified</span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection