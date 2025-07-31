<?php

namespace Tests\Feature\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class ImportCSVFileTest extends TestCase
{
    use RefreshDatabase;

    public function test_reading_csv_file_and_saving_to_database()
    {
        $path = $this->createFakeCSVFile();

        $this->artisan('import:csv', ['path' => $path])
            ->expectsOutput('Successfully imported 2 patients and 2 results.')
            ->assertExitCode(0);

        $this->assertDatabaseCount('patients', 2);
        $this->assertDatabaseCount('orders', 2);

        $this->assertDatabaseHas('patients', ['id' => '1', 'name' => 'Piotr', 'surname' => 'Kowalski', 'birth_date' => '1983-04-12', 'sex' => 'm']);
        $this->assertDatabaseHas('patients', ['id' => '2', 'name' => 'Anna', 'surname' => 'Jabłońska', 'birth_date' => '2002-12-12', 'sex' => 'f']);
        $this->assertDatabaseHas('orders', ['order_id' => '1', 'patient_id' => '1', 'name' => 'Protoporfiryna cynkowa', 'value' => '4.0', 'reference' => '< 40,0']);
        $this->assertDatabaseHas('orders', ['order_id' => '3', 'patient_id' => '2', 'name' => 'Azotyny', 'value' => 'nieobecne', 'reference' => 'nieobecne']);
    }

    public function test_wrong_file_path_output()
    {
        Log::spy();

        $this->artisan('import:csv', ['path' => '/invalid/path.csv'])
            ->expectsOutputToContain('No such file or directory')
            ->assertExitCode(0);

        Log::shouldHaveReceived('error')
            ->once()
            ->with(Mockery::on(fn ($message) => str_contains($message, 'No such file or directory')));
    }

    public function test_wrong_file_format_output()
    {
        $path = $this->createFakeCSVFile(true);

        Log::spy();

        $this->artisan('import:csv', ['path' => $path])
            ->expectsOutputToContain('The header record contains duplicate column names')
            ->assertExitCode(0);

        Log::shouldHaveReceived('error')
            ->once()
            ->with(Mockery::on(fn ($message) => str_contains($message, 'The header record contains duplicate column names')));
    }

    private function createFakeCSVFile($wrongFormat = false): string
    {
        Storage::fake('local');

        $extraContent = $wrongFormat ? ";;;;;" : "";
        $csvContent = <<<CSV
            patientId;patientName;patientSurname;patientSex;patientBirthDate;orderId;testName;testValue;testReference{$extraContent}
            1;Piotr;Kowalski;male;1983-04-12;1;Protoporfiryna cynkowa;4.0;< 40,0
            2;Anna;Jabłońska;female;2002-12-12;3;Azotyny;nieobecne;nieobecne
            CSV;
        $path = storage_path('app/test-data.csv');
        file_put_contents($path, $csvContent);
        return $path;
    }
}
