<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserPolicy extends AdminOnlyPolicy
{
    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(
        User $user,
        Model $record
    ): bool {
        return $user->isSuperAdmin();
    }

    public function delete(
        User $user,
        Model $record
    ): bool {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return false;
    }
}
