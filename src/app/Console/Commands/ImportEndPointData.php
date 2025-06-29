<?php

namespace App\Console\Commands;

use App\Services\TargetApiService;
use Illuminate\Console\Command;

class ImportEndPointData extends Command
{
    /**
     * The name and signature of the console command.
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
