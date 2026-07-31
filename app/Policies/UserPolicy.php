<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view users');
    }

    public function view(User $user, User $model): bool
    {
        return $user->can('view users') || $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create users');
    }

    public function update(User $user, User $model): bool
    {
        return $user->can('edit users') || $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->can('delete users') && $user->id !== $model->id;
    }

    public function manageRoles(User $user, User $model): bool
    {
        return $user->can('manage user roles') && $user->id !== $model->id;
    }
}