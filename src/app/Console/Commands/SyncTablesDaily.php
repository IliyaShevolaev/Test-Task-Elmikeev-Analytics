<?php

namespace App\Console\Commands;

use App\Models\TargetApi\Income;
use App\Models\TargetApi\Order;
use App\Models\TargetApi\Sale;
use App\Models\TargetApi\Stock;
use App\Services\TargetApiService;
use Illuminate\Console\Command;

class SyncTablesDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sync-tables-daily';

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
        Sale::syncData($targetApiService);
        Order::syncData($targetApiService);
        Income::syncData($targetApiService);
        Stock::syncData($targetApiService);
    }
}
