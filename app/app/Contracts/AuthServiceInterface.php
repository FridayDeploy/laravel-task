<?php

namespace App\Contracts;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthPatientResource;

interface AuthServiceInterface
{
    /**
     * @param AuthRequest $request
     * @return AuthPatientResource
     */
    public function loginPatient(AuthRequest $request): AuthPatientResource;
}
