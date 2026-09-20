<?php

namespace App\Policies;

use App\Models\Booth;
use App\Models\User;

class BoothPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'penjual';
    }

    public function view(User $user, Booth $booth): bool
    {
        return $user->id === $booth->owner_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'penjual';
    }

    public function update(User $user, Booth $booth): bool
    {
        return $user->id === $booth->owner_id;
    }

    public function delete(User $user, Booth $booth): bool
    {
        return $user->id === $booth->owner_id;
    }
}
