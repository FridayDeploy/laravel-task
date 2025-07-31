<?php

namespace App\Queries;

use App\Contracts\OrderQueryInterface;
use App\Models\Order;
use Illuminate\Support\Collection;

final readonly class EloquentOrderQuery implements OrderQueryInterface
{

    public function getOrdersByPatientId(int $patientId): Collection
    {
        return Order::query()
            ->where('patient_id', $patientId)
            ->get();
    }

    public function checkIfResultExists(int $orderId, int $patientId, string $name): bool
    {
        return Order::query()
            ->where('order_id', $orderId)
            ->where('patient_id', $patientId)
            ->where('name', $name)
            ->exists();
    }
}
