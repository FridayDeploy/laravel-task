<?php

namespace App\Contracts;

interface CSVAdapterInterface
{
    public function readFile(string $path): iterable;
}
