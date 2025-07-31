<?php

namespace App\Contracts;

interface CSVServiceInterface
{
    public function loadCSVFileIntoDatabase(string $path): array;
}
