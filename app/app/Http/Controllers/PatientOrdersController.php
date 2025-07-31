<?php

namespace App\Http\Controllers;

use App\Contracts\OrderQueryInterface;
use App\Http\Resources\PatientOrderResource;
use App\Http\Resources\PatientResource;

class PatientOrdersController extends Controller
{
    public function __construct(
        private readonly OrderQueryInterface $orderQuery
    )
    {}

    public function index(): PatientOrderResource
    {
        $orders = $this->orderQuery->getOrdersByPatientId(auth()->id());

        if($orders->isEmpty()) {
            abort(404);
        }

        return new PatientOrderResource(
            $orders,
            new PatientResource(auth()->user())
        );
    }
}
