<?php

namespace App\Services;

use App\Contracts\CSVAdapterInterface;
use App\Contracts\CSVServiceInterface;
use App\Contracts\OrderQueryInterface;
use App\Contracts\PatientQueryInterface;
use App\Models\Order;
use App\Models\Patient;

final readonly class CSVService implements CSVServiceInterface
{
    public function __construct(
        private CSVAdapterInterface $csvAdapter,
        private PatientQueryInterface $patientQuery,
        private OrderQueryInterface $orderQuery,
    )
    {}

    public function loadCSVFileIntoDatabase(string $path): array
    {
        $counters = ['patients' => 0, 'results' => 0];
        foreach ($this->csvAdapter->readFile($path) as $line) {
            if(!$this->patientQuery->checkIfPatientExists($line['patientId'])) {
                $this->insertNewPatient($line);
                $counters['patients']++;
            }

            if(!$this->orderQuery->checkIfResultExists($line['orderId'], $line['patientId'], $line['testName'])) {
                $this->insertNewOrder($line);
                $counters['results']++;
            }

        }

        return $counters;
    }

    private function insertNewPatient(array $line): void
    {
        $patient = new Patient();
        $patient->id = $line['patientId'];
        $patient->name = $line['patientName'];
        $patient->surname = $line['patientSurname'];
        $patient->sex = $line['patientSex'];
        $patient->birth_date = $line['patientBirthDate'];
        $patient->save();
    }

    private function insertNewOrder(array $line): void
    {
        $order = new Order();
        $order->order_id = $line['orderId'];
        $order->patient_id = $line['patientId'];
        $order->name = $line['testName'];
        $order->value = $line['testValue'];
        $order->reference = $line['testReference'];
        $order->save();
    }
}
