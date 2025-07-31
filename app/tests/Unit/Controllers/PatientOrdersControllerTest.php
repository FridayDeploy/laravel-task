<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\PatientOrdersController;
use App\Contracts\OrderQueryInterface;
use App\Http\Resources\PatientOrderResource;
use Tests\TestCase;
use Mockery;

class PatientOrdersControllerTest extends TestCase
{
    protected PatientOrdersController $controller;
    protected OrderQueryInterface $orderQueryMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->orderQueryMock = Mockery::mock(OrderQueryInterface::class);
        $this->controller = new PatientOrdersController($this->orderQueryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_patient_orders_resource_when_data_exists()
    {
        $this->setupAuthMock();
        $orders = collect([
            ['id' => 1, 'patient_id' => 1],
            ['id' => 2, 'patient_id' => 1]
        ]);

        $this->orderQueryMock
            ->shouldReceive('getOrdersByPatientId')
            ->with(1)
            ->once()
            ->andReturn($orders);

        $response = $this->controller->index();

        $this->assertInstanceOf(PatientOrderResource::class, $response);
        $this->assertEquals($orders, $response->resource);
    }

    public function test_index_throws_404_when_no_orders_found()
    {
        $this->setupAuthMock();
        $emptyOrders = collect();

        $this->orderQueryMock
            ->shouldReceive('getOrdersByPatientId')
            ->once()
            ->with(1)
            ->andReturn($emptyOrders);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        $this->controller->index();
    }

    private function setupAuthMock(): void
    {
        $patient = new \App\Models\Patient();
        $patient->id = 1;
        $authMock = Mockery::mock();
        $authMock->shouldReceive('id')->andReturn(1);
        $authMock->shouldReceive('user')->andReturn($patient);
        app()->instance('auth', $authMock);
    }
}
