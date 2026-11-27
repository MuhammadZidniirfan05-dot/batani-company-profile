<?php

namespace App\Policies;

use App\Models\User;

class AdminOnlyPolicy extends ContentPolicy
{
    protected function allowed(User $user): bool
    {
        return $user->isSuperAdmin();
    }
}
