<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Driver;
use Illuminate\Auth\Access\HandlesAuthorization;

class DriverPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view drivers');
    }

    public function view(User $user, Driver $driver): bool
    {
        return $user->can('view drivers');
    }

    public function create(User $user): bool
    {
        return $user->can('create drivers');
    }

    public function update(User $user, Driver $driver): bool
    {
        return $user->can('edit drivers');
    }

    public function delete(User $user, Driver $driver): bool
    {
        return $user->can('delete drivers');
    }
}