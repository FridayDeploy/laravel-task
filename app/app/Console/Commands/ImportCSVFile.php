<?php

namespace App\Console\Commands;

use App\Contracts\CSVServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportCSVFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv {path : Absolute path to CSV file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from CSV file into a database';

    public function __construct(
        private readonly CSVServiceInterface $csvService
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $counters = $this->csvService->loadCSVFileIntoDatabase(
                $this->argument('path')
            );

            $message = "Successfully imported ".$counters['patients']." patients and ".$counters['results']." results.";
            $this->info($message);
            Log::info($message);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            Log::error($e->getMessage());
        }
    }
}
