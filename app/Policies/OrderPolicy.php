<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine if the given user can request cancellation for the order.
     */
    public function requestCancellation(User $user, Order $order)
    {
        if ($user->id !== $order->user_id) return false;

        // allow only pending or processing orders
        return in_array($order->status, ['pending', 'processing']);
    }
}
