<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class PatientOrderResource extends JsonResource
{
    public function __construct(
        private readonly Collection $orders,
        private readonly PatientResource $patient)
    {
        parent::__construct($this->orders);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'patient' => $this->patient,
            'orders' => $this->resource->groupBy('order_id')
                ->map(function ($items, $orderId) {
                    return [
                        'orderId' => $orderId,
                        'results' => $items->map(fn ($item) => [
                            'name' => $item->name,
                            'value' => $item->value,
                            'reference' => $item->reference,
                        ])->values()
                    ];
                })
                ->values(),
        ];
    }
}
