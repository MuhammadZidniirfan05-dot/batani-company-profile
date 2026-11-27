<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ContactMessagePolicy extends AdminOnlyPolicy
{
    public function create(User $user): bool
    {
        return false;
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
