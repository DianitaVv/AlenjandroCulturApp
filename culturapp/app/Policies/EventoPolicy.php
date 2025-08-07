<?php

namespace App\Policies;

use App\Models\Evento;
use App\Models\User;

class EventoPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Evento $evento): bool
    {
        return $evento->activo || ($user && ($user->canManageContent() || $user->id === $evento->user_id));
    }

    public function create(User $user): bool
    {
        return $user->activo;
    }

    public function update(User $user, Evento $evento): bool
    {
        return $user->canManageContent() || $user->id === $evento->user_id;
    }

    public function delete(User $user, Evento $evento): bool
    {
        return $user->isAdmin() || $user->id === $evento->user_id;
    }
}