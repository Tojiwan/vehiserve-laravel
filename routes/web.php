<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentDownloadController;
use App\Http\Controllers\Admin\UsersController;
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
    
    // User Management
    Route::get('/users', fn() => view('admin.users.index'))->name('users.index');
    Route::get('/users/create', fn() => view('admin.users.create'))->name('users.create');
    Route::get('/users/{user}/edit', fn() => view('admin.users.edit'))->name('users.edit');
    
    // Vehicle Management
    Route::get('/vehicles', fn() => view('admin.vehicles.index'))->name('vehicles.index');
    Route::get('/vehicles/create', fn() => view('admin.vehicles.create'))->name('vehicles.create');
    Route::get('/vehicles/{vehicle}/edit', fn() => view('admin.vehicles.edit'))->name('vehicles.edit');
    
    // Driver Management
    Route::get('/drivers', fn() => view('admin.drivers.index'))->name('drivers.index');
    Route::get('/drivers/create', fn() => view('admin.drivers.create'))->name('drivers.create');
    Route::get('/drivers/{driver}/edit', fn() => view('admin.drivers.edit'))->name('drivers.edit');
    
    // Reports
    Route::get('/reports', fn() => view('admin.reports'))->name('reports');
    Route::get('/reports/vehicle-usage', fn() => view('admin.reports.vehicle-usage'))->name('reports.vehicle-usage');
    Route::get('/reports/driver-performance', fn() => view('admin.reports.driver-performance'))->name('reports.driver-performance');
    
    // Audit Logs
    Route::get('/audit-logs', fn() => view('admin.audit-logs'))->name('audit-logs');
    
    // System Settings
    Route::get('/settings', fn() => view('admin.settings'))->name('settings');
});

// Staff routes
Route::middleware(['auth', 'verified', 'can:access-staff-panel'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', fn() => view('staff.dashboard'))->name('dashboard');
    
    // Approval Queue
    Route::get('/approval-queue', fn() => view('staff.approval-queue'))->name('approval-queue');
    Route::get('/approval-detail/{type}/{id}', fn() => view('staff.approval-detail'))->name('approval-detail');
    
    // Vehicle Management
    Route::get('/vehicles', fn() => view('staff.vehicles.index'))->name('vehicles.index');
    Route::get('/vehicles/create', fn() => view('staff.vehicles.create'))->name('vehicles.create');
    Route::get('/vehicles/{vehicle}/edit', fn() => view('staff.vehicles.edit'))->name('vehicles.edit');
    
    // Driver Management
    Route::get('/drivers', fn() => view('staff.drivers.index'))->name('drivers.index');
    Route::get('/drivers/create', fn() => view('staff.drivers.create'))->name('drivers.create');
    Route::get('/drivers/{driver}/edit', fn() => view('staff.drivers.edit'))->name('drivers.edit');
    
    // Calendar & Schedules
    Route::get('/calendar', fn() => view('staff.calendar'))->name('calendar');
    Route::get('/schedule', fn() => view('staff.schedule'))->name('schedule');
    
    // Trips
    Route::get('/trips', fn() => view('staff.trips.index'))->name('trips.index');
    
    // Attendance
    Route::get('/attendance', fn() => view('staff.attendance'))->name('attendance');
    Route::get('/attendance/report', fn() => view('staff.attendance-report'))->name('attendance-report');
    
    // Reminders
    Route::get('/reminders', fn() => view('staff.reminders'))->name('reminders');
});

// Approver routes (Dean, VP, SUC)
Route::middleware(['auth', 'verified', 'can:access-approver-panel'])->prefix('approver')->name('approver.')->group(function () {
    Route::get('/dashboard', fn() => view('approver.dashboard'))->name('dashboard');
    
    // Approval Queue
    Route::get('/queue', fn() => view('approver.queue'))->name('queue');
    Route::get('/review/{type}/{id}', fn() => view('approver.review'))->name('review');
    
    // History
    Route::get('/history', fn() => view('approver.history'))->name('history');
    
    // Settings
    Route::get('/settings', fn() => view('approver.settings'))->name('settings');
});

require __DIR__.'/auth.php';