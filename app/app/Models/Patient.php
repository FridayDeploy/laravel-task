<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $sex
 * @property string $birth_date
 */
class Patient extends Authenticatable implements JWTSubject
{
    use HasFactory;

    public function setSexAttribute(string $value): void
    {
        $this->attributes['sex'] = match($value) {
            'male' => 'm',
            'female' => 'f',
        };
    }

    public function getSexAttribute(string $value): string
    {
        return match ($value) {
            'm' => 'male',
            'f' => 'female',
        };
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
