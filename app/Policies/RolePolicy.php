<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any Role');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->can('view Role');
    }

    public function create(User $user): bool
    {
        return $user->can('create Role');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->can('update Role');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->can('delete Role');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any Role');
    }

    public function restore(User $user, Role $role): bool
    {
        return $user->can('restore Role');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any Role');
    }

    public function replicate(User $user, Role $role): bool
    {
        return $user->can('replicate Role');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder Role');
    }

    public function forceDelete(User $user, Role $role): bool
    {
        return $user->can('force-delete Role');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any Role');
    }
}
