<?php

namespace Tests\Feature\Controllers;

use App\Models\Order;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientOrdersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_unauthenticated_user_cannot_access_results()
    {
        $response = $this->getJson('/api/results');

        $response->assertUnauthorized();
    }

    public function test_index_returns_404_if_no_orders_found()
    {
        /** @var Patient $patient */
        $patient = Patient::factory()->create();
        $this->actingAs($patient, 'api');

        $response = $this->getJson('/api/results');

        $response->assertStatus(404);
    }

    public function test_index_returns_patient_order_resource_if_orders_exist()
    {
        /** @var Patient $patient */
        $patient = Patient::factory()->create();
        Order::factory()->count(2)->create(['patient_id' => $patient->id]);

        $this->actingAs($patient, 'api');
        $response = $this->getJson('/api/results');

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'patient' => [
                    'id',
                    'name',
                    'surname',
                    'sex',
                    'birthDate'
                ],
                'orders' => [
                    '*' => [
                        'orderId',
                        'results' => [
                            '*' => [
                                'name',
                                'value',
                                'reference',
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }
}
