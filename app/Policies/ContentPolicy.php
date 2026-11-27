<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ContentPolicy
{
    protected function allowed(User $user): bool
    {
        return $user->canManageContent();
    }

    public function viewAny(User $user): bool
    {
        return $this->allowed($user);
    }

    public function view(
        User $user,
        Model $record
    ): bool {
        return $this->allowed($user);
    }

    public function create(User $user): bool
    {
        return $this->allowed($user);
    }

    public function update(
        User $user,
        Model $record
    ): bool {
        return $this->allowed($user);
    }

    public function delete(
        User $user,
        Model $record
    ): bool {
        return $this->allowed($user);
    }

    public function deleteAny(User $user): bool
    {
        return $this->allowed($user);
    }

    public function reorder(User $user): bool
    {
        return $this->allowed($user);
    }

    public function replicate(
        User $user,
        Model $record
    ): bool {
        return false;
    }

    public function restore(
        User $user,
        Model $record
    ): bool {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function forceDelete(
        User $user,
        Model $record
    ): bool {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }
}
