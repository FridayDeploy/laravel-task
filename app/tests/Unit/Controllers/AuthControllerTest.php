<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\AuthController;
use App\Contracts\AuthServiceInterface;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthPatientResource;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    protected AuthController $controller;
    protected AuthServiceInterface $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = Mockery::mock(AuthServiceInterface::class);
        $this->controller = new AuthController($this->authService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_login_succeeds_with_valid_credentials()
    {
        $mockRequest = Mockery::mock(AuthRequest::class);
        $expectedResponse = Mockery::mock(AuthPatientResource::class);

        $this->authService
            ->shouldReceive('loginPatient')
            ->once()
            ->with($mockRequest)
            ->andReturn($expectedResponse);

        $response = $this->controller->login($mockRequest);

        $this->assertInstanceOf(AuthPatientResource::class, $response);
    }

    public function test_login_returns_401_when_patient_not_found()
    {
        $mockRequest = Mockery::mock(AuthRequest::class);
        $this->authService
            ->shouldReceive('loginPatient')
            ->once()
            ->with($mockRequest)
            ->andThrows(new \Exception());

        $response = $this->controller->login($mockRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals(['errors' => ['Patient not found']], $response->getData(true));
    }

    public function test_logout_calls_auth_logout_and_returns_empty_response()
    {
        $authMock = Mockery::mock();
        $authMock->shouldReceive('logout')->once();
        app()->instance('auth', $authMock);

        $response = $this->controller->logout();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEmpty($response->getData());
    }
}
