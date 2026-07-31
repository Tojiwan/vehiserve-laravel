<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TravelRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class TravelRequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view travel requests');
    }

    public function view(User $user, TravelRequest $request): bool
    {
        if ($user->id === $request->user_ID) {
            return true;
        }

        return $user->can('view all travel requests');
    }

    public function create(User $user): bool
    {
        return $user->can('create travel requests');
    }

    public function update(User $user, TravelRequest $request): bool
    {
        if ($user->id === $request->user_ID) {
            return in_array($request->vehicle_status, ['Pending Dean', 'Cancelled by User']);
        }

        return $user->can('edit travel requests');
    }

    public function delete(User $user, TravelRequest $request): bool
    {
        if ($user->id === $request->user_ID) {
            return in_array($request->vehicle_status, ['Pending Dean', 'Pending VP', 'Pending SUC', 'Pending Motor Pool']);
        }

        return $user->can('delete travel requests');
    }

    public function approve(User $user, TravelRequest $request): bool
    {
        $currentApproval = $request->approvals()->where('status', 'Pending')->first();
        
        if (!$currentApproval) {
            return false;
        }

        return $user->hasRole($currentApproval->role) && $user->can('approve travel requests');
    }

    public function reject(User $user, TravelRequest $request): bool
    {
        return $this->approve($user, $request);
    }
}