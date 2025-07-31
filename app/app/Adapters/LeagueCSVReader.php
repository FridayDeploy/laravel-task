<?php

namespace App\Adapters;

use App\Contracts\CSVAdapterInterface;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\UnavailableStream;

final readonly class LeagueCSVReader implements CSVAdapterInterface
{

    /**
     * @throws UnavailableStream
     * @throws Exception
     */
    public function readFile(string $path, int $headerOffset = 0, string $delimiter = ';'): iterable
    {
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset($headerOffset);
        $csv->setDelimiter($delimiter);

        foreach ($csv as $record) {
            yield $record;
        }
    }
}
