<?php

namespace Tests\Unit\Services;

use App\Contracts\PatientQueryInterface;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthPatientResource;
use App\Models\Patient;
use App\Services\JWTAuthService;
use Exception;
use Mockery;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthServiceTest extends TestCase
{
    protected PatientQueryInterface $patientQueryMock;
    protected JWTAuthService $authService;
    protected JWTAuth $jwtMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->patientQueryMock = Mockery::mock(PatientQueryInterface::class);
        $this->authService = new JWTAuthService($this->patientQueryMock);

        JWTAuth::shouldReceive('claims')->andReturnSelf();
        JWTAuth::shouldReceive('fromUser')->andReturn('jwt');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_loginPatient_returns_auth_patient_resource_when_credentials_are_valid()
    {
        $patient = $this->getFakePatient();
        $fullName = "$patient->name $patient->surname";

        $requestMock = Mockery::mock(AuthRequest::class);
        $requestMock->shouldReceive('get')->with('fullName')->andReturn($fullName);
        $requestMock->shouldReceive('get')->with('dateOfBirth')->andReturn($patient->birth_date);

        $this->patientQueryMock
            ->shouldReceive('getPatientByFullNameAndBirthDate')
            ->with($fullName, $patient->birth_date)
            ->once()
            ->andReturn($patient);

        $response = $this->authService->loginPatient($requestMock);

        $this->assertInstanceOf(AuthPatientResource::class, $response);
        $this->assertEquals('jwt', $response->response()->getData(true)['data']['token'] ?? null);
    }

    /**
     * @throws Exception
     */
    public function test_loginPatient_throws_unauthorized_exception_when_patient_not_found()
    {
        $patient = $this->getFakePatient();
        $requestMock = Mockery::mock(AuthRequest::class);
        $requestMock->shouldReceive('get')->with('fullName')->andReturn($patient->name);
        $requestMock->shouldReceive('get')->with('dateOfBirth')->andReturn($patient->birth_date);

        $this->patientQueryMock
            ->shouldReceive('getPatientByFullNameAndBirthDate')
            ->with($patient->name, $patient->birth_date)
            ->once()
            ->andReturn(null);

        $this->expectException(Exception::class);

        $this->authService->loginPatient($requestMock);
    }

    private function getFakePatient(): Patient
    {
        $patient = new Patient();
        $patient->id = 1;
        $patient->name = 'Jan';
        $patient->surname = 'Kowalski';
        $patient->sex = 'male';
        $patient->birth_date = '1980-01-01';
        return $patient;
    }
}

