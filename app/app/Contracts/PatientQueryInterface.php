<?php

namespace App\Contracts;

use App\Models\Patient;

interface PatientQueryInterface
{
    public function getPatientByFullNameAndBirthDate(string $fullName, string $birthDate): Patient|null;

    public function checkIfPatientExists(int $patientId): bool;
}
