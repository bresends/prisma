<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any Permission');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->can('view Permission');
    }

    public function create(User $user): bool
    {
        return $user->can('create Permission');
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->can('update Permission');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->can('delete Permission');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete-any Permission');
    }

    public function restore(User $user, Permission $permission): bool
    {
        return $user->can('restore Permission');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore-any Permission');
    }

    public function replicate(User $user, Permission $permission): bool
    {
        return $user->can('replicate Permission');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder Permission');
    }

    public function forceDelete(User $user, Permission $permission): bool
    {
        return $user->can('force-delete Permission');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force-delete-any Permission');
    }
}
