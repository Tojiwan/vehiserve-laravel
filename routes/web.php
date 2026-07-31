<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentDownloadController;
use App\Livewire\User\Dashboard;
use App\Livewire\User\VehicleRequestForm;
use App\Livewire\User\TravelRequestForm;
use App\Livewire\User\DocumentTracking;
use App\Livewire\User\DocumentList;
use App\Livewire\User\ProfileSettings;
use Illuminate\Support\Facades\Route;

// Home redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// Role-based dashboard redirect
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    }
    
    if ($user->hasRole('Staff')) {
        return redirect()->route('staff.dashboard');
    }
    
    if ($user->hasAnyRole(['Dean', 'VP', 'SUC'])) {
        return redirect()->route('approver.dashboard');
    }
    
    // Default to user dashboard
    return redirect()->route('user.dashboard');
})->name('dashboard');

// Guest routes
Route::middleware('guest')->group(function () {
    require __DIR__.'/auth.php';
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // User Portal Routes
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        
        // Vehicle Requests
        Route::get('/vehicle-request/create', VehicleRequestForm::class)->name('vehicle-request.create');
        Route::get('/vehicle-requests', DocumentTracking::class)->name('vehicle-requests');
        
        // Travel Requests
        Route::get('/travel-request/create', TravelRequestForm::class)->name('travel-request.create');
        Route::get('/travel-requests', DocumentTracking::class)->name('travel-requests');
        
        // Document Tracking
        Route::get('/document-tracking', DocumentTracking::class)->name('document-tracking');
        // Route::get('/document-tracking/{type}/{id}', \App\Livewire\User\DocumentTrackingDetail::class)->name('document-tracking.detail');
        
        // Documents List
        Route::get('/documents', DocumentList::class)->name('documents');
        
        // Profile
        Route::get('/profile', ProfileSettings::class)->name('profile');
    });

    // Document Download Route
    Route::get('/document/download/{path}', [DocumentDownloadController::class, 'download'])
        ->where('path', '.*')
        ->name('document.download');

    // Profile routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'verified', 'can:access-admin-panel'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    // Add more admin routes here
});

// Staff routes
Route::middleware(['auth', 'verified', 'can:access-staff-panel'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', fn() => view('staff.dashboard'))->name('dashboard');
    // Add more staff routes here
});

// Approver routes (Dean, VP, SUC)
Route::middleware(['auth', 'verified', 'can:access-approver-panel'])->prefix('approver')->name('approver.')->group(function () {
    Route::get('/dashboard', fn() => view('approver.dashboard'))->name('dashboard');
    // Add more approver routes here
});

require __DIR__.'/auth.php';