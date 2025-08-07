<?php

namespace App\Policies;

use App\Models\SitioCultural;
use App\Models\User;

class SitioCulturalPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, SitioCultural $sitioCultural): bool
    {
        return $sitioCultural->activo || ($user && ($user->canManageContent() || $user->id === $sitioCultural->user_id));
    }

    public function create(User $user): bool
    {
        return $user->activo;
    }

    public function update(User $user, SitioCultural $sitioCultural): bool
    {
        return $user->canManageContent() || $user->id === $sitioCultural->user_id;
    }

    public function delete(User $user, SitioCultural $sitioCultural): bool
    {
        return $user->isAdmin() || $user->id === $sitioCultural->user_id;
    }
}