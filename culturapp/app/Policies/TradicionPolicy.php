<?php

namespace App\Policies;

use App\Models\Tradicion;
use App\Models\User;

class TradicionPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Tradicion $tradicion): bool
    {
        return $tradicion->activo || ($user && ($user->canManageContent() || $user->id === $tradicion->user_id));
    }

    public function create(User $user): bool
    {
        return $user->activo;
    }

    public function update(User $user, Tradicion $tradicion): bool
    {
        return $user->canManageContent() || $user->id === $tradicion->user_id;
    }

    public function delete(User $user, Tradicion $tradicion): bool
    {
        return $user->isAdmin() || $user->id === $tradicion->user_id;
    }
}