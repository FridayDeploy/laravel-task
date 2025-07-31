<?php

namespace App\Services;

use App\Contracts\AuthServiceInterface;
use App\Contracts\PatientQueryInterface;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthPatientResource;
use App\Models\Patient;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

final readonly class JWTAuthService implements AuthServiceInterface
{
    public function __construct(
        private PatientQueryInterface $patientQuery,
    ) {}

    /**
     * @param AuthRequest $request
     * @return AuthPatientResource
     * @throws Exception
     */
    public function loginPatient(AuthRequest $request): AuthPatientResource
    {
        $fullName = $request->get('fullName') ?? "";
        $dateOfBirth = $request->get('dateOfBirth') ?? "";

        $patient = $this->patientQuery->getPatientByFullNameAndBirthDate($fullName, $dateOfBirth);

        if (!$patient) {
            throw new Exception;
        }

        return new AuthPatientResource(
            $this->generateJWT($patient)
        );
    }

    private function generateJWT(Patient $patient): string
    {
        return JWTAuth::claims([
            'sub' => $patient->id,
            //'exp' => now()->timestamp + 5 //For auto logout testing
        ])->fromUser($patient);
    }
}
