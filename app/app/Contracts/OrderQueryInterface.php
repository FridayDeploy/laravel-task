<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface OrderQueryInterface
{
    public function getOrdersByPatientId(int $patientId): Collection;

    public function checkIfResultExists(int $orderId, int $patientId, string $name): bool;
}
