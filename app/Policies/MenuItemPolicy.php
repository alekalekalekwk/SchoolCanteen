<?php

namespace App\Policies;

use App\Models\MenuItem;
use App\Models\User;

class MenuItemPolicy
{
    public function update(User $user, MenuItem $menuItem): bool
    {
        return (int) $user->id === (int) $menuItem->booth->owner_id;
    }
}
