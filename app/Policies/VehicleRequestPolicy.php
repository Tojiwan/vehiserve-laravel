<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VehicleRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class VehicleRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view vehicle requests');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, VehicleRequest $request): bool
    {
        // User can view their own requests
        if ($user->id === $request->user_ID) {
            return true;
        }

        // Staff, Admin, Dean, VP, SUC can view all
        return $user->can('view all vehicle requests');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create vehicle requests');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VehicleRequest $request): bool
    {
        // User can update their own pending/cancelled requests
        if ($user->id === $request->user_ID) {
            return in_array($request->vehicle_status, ['Pending Motor Pool', 'Cancelled by User']);
        }

        return $user->can('edit vehicle requests');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VehicleRequest $request): bool
    {
        // User can cancel their own pending requests
        if ($user->id === $request->user_ID) {
            return in_array($request->vehicle_status, ['Pending Motor Pool', 'Pending Dean', 'Pending VP', 'Pending SUC']);
        }

        return $user->can('delete vehicle requests');
    }

    /**
     * Determine whether the user can approve the request.
     */
    public function approve(User $user, VehicleRequest $request): bool
    {
        $currentApproval = $request->approvals()->where('status', 'Pending')->first();
        
        if (!$currentApproval) {
            return false;
        }

        // Check if user has the required role for this approval step
        return $user->hasRole($currentApproval->role) && $user->can('approve vehicle requests');
    }

    /**
     * Determine whether the user can reject the request.
     */
    public function reject(User $user, VehicleRequest $request): bool
    {
        return $this->approve($user, $request);
    }

    /**
     * Determine whether the user can assign vehicle/driver.
     */
    public function assignVehicleDriver(User $user, VehicleRequest $request): bool
    {
        $currentApproval = $request->approvals()->where('status', 'Pending')->first();
        
        if (!$currentApproval || $currentApproval->role !== 'Motor Pool') {
            return false;
        }

        return $user->hasRole('Motor Pool') && $user->can('assign vehicle driver');
    }
}