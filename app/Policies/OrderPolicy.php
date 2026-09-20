<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function update(User $user, Order $order): bool
    {
        return (int) $user->id === (int) $order->booth->owner_id;
    }
}
