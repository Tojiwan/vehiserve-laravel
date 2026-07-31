<?php

use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Support\Facades\Broadcast;
use App\Models\VehicleRequest;
use App\Models\TravelRequest;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private channel for user notifications
Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Channel for vehicle request updates
Broadcast::channel('vehicle-request.{requestId}', function ($user, $requestId) {
    $request = VehicleRequest::with('approvals')->find($requestId);
    
    if (!$request) {
        return false;
    }

    // User can listen if they own the request or have approval permissions
    if ($request->user_ID === $user->id) {
        return true;
    }

    // Check if user is an approver for this request
    $currentApproval = $request->approvals()->where('status', 'Pending')->first();
    if ($currentApproval && $user->hasRole($currentApproval->role)) {
        return true;
    }

    // Staff, Admin, Dean, VP, SUC can listen to all
    return $user->hasAnyRole(['Staff', 'Admin', 'Dean', 'VP', 'SUC', 'Super Admin']);
});

// Channel for travel request updates
Broadcast::channel('travel-request.{requestId}', function ($user, $requestId) {
    $request = TravelRequest::with('approvals')->find($requestId);
    
    if (!$request) {
        return false;
    }

    // User can listen if they own the request or have approval permissions
    if ($request->user_ID === $user->id) {
        return true;
    }

    // Check if user is an approver for this request
    $currentApproval = $request->approvals()->where('status', 'Pending')->first();
    if ($currentApproval && $user->hasRole($currentApproval->role)) {
        return true;
    }

    // Staff, Admin, Dean, VP, SUC can listen to all
    return $user->hasAnyRole(['Staff', 'Admin', 'Dean', 'VP', 'SUC', 'Super Admin']);
});

// Admin channel for dashboard updates
Broadcast::channel('admin.dashboard', function ($user) {
    return $user->hasAnyRole(['Admin', 'Staff', 'Super Admin']);
});

// Staff channel for motor pool updates
Broadcast::channel('staff.dashboard', function ($user) {
    return $user->hasAnyRole(['Staff', 'Admin', 'Super Admin']);
});