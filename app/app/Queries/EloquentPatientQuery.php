<?php

namespace App\Queries;

use App\Contracts\PatientQueryInterface;
use App\Models\Patient;

final readonly class EloquentPatientQuery implements PatientQueryInterface
{

    public function getPatientByFullNameAndBirthDate(string $fullName, string $birthDate): Patient|null
    {
        $fullName = preg_split('/(?=[A-Z])/', $fullName, 2, PREG_SPLIT_NO_EMPTY);
        if(count($fullName) !== 2) {
            return null;
        }

        return Patient::query()
            ->where('name', $fullName[0])
            ->where('surname', $fullName[1])
            ->where('birth_date', $birthDate)
            ->first();
    }

    public function checkIfPatientExists(int $patientId): bool
    {
        return Patient::query()
            ->where('id', $patientId)
            ->exists();
    }
}
