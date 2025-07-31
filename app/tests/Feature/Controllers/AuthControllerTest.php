<?php

namespace Feature\Controllers;

use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_returns_auth_patient_resource_on_success()
    {
        /** @var Patient $patient */
        $patient = Patient::factory()->create([
            'name' => 'Jan',
            'surname' => 'Kowalski',
            'sex' => 'male',
            'birth_date' => '1980-01-01',
        ]);

        $response = $this->postJson('/api/login', [
            'fullName' => $patient->name.$patient->surname,
            'dateOfBirth' => $patient->birth_date,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'token'
            ]
        ]);
    }

    public function test_login_returns_unauthorized_if_patient_not_found()
    {
        $response = $this->postJson('/api/login', [
            'fullName' => 'qwerty',
            'dateOfBirth' => '1990-12-12',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['errors' => ['Patient not found']]);
    }

    public function test_logout_logs_out_the_user()
    {
        /** @var Patient $patient */
        $patient = Patient::factory()->create();
        $token = auth()->login($patient);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertExactJson([]);
    }
}
