<?php

namespace App\Console\Commands;

use App\Services\TargetApiService;
use Illuminate\Console\Command;

class ImportEndPointData extends Command
{
    /**
     * The name and signature of the console command.
     * Example: docker compose run --rm artisan import:endpoints-data "App\\Models\\TargetApi\\Income" incomes 2023-01-01 2026-01-01
     *
     * @var string
     */
    protected $signature = 'import:endpoints-data {className} {apiPath} {dateFrom} {dateTo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(TargetApiService $targetApiService)
    {
        $this->info('Starting data import');

        $targetApiService->storeData(
            $this->argument('className'),
            $this->argument('apiPath'),
            $this->argument('dateFrom'),
            $this->argument('dateTo')
        );
    }
}
