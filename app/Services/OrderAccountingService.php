<?php

namespace App\Services;

use App\Models\Order;

class OrderAccountingService
{
    public function countsAsPaid(Order $order): bool
    {
        if ($order->status === Order::STATUS_CANCELLED) {
            return false;
        }

        return $order->status === Order::STATUS_DELIVERED
            || in_array($order->payment_method, ['GCash', 'PayMaya'], true);
    }
}
