<?php

namespace App\Jobs;

use App\Models\ApiIntegration\Account;
use App\Services\TargetApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class InsertDataJob implements ShouldQueue
{
    use Queueable;

    private $secondsDelayBetweenRetry = 15;
    private $secondsDelayBetweenNextJob = 1;

    protected $currentPage;
    protected $maxPagesToRequest;
    protected $targetModel;
    protected $targetApiEndpointPath;
    protected $dateFrom;
    protected $dateTo;
    protected $accountId;

    /**
     * Create a new job instance.
     */
    public function __construct(
        int $currentPage,
        int $maxPagesToRequest,
        string $targetModel,
        string $targetApiEndpointPath,
        string $dateFrom,
        string $dateTo,
        int $accountId
    ) {
        $this->currentPage = $currentPage;
        $this->maxPagesToRequest = $maxPagesToRequest;
        $this->targetModel = $targetModel;
        $this->targetApiEndpointPath = $targetApiEndpointPath;
        $this->dateTo = $dateTo;
        $this->dateFrom = $dateFrom;
        $this->accountId = $accountId;
    }

    /**
     * Execute the job.
     */
    public function handle(TargetApiService $targetApiService): void
    {
        $targetApiService->withAccount(Account::find($this->accountId));

        $requestedPages = 0;
        $totalInsert = 0;

        while ($this->maxPagesToRequest > $requestedPages) {
            $response = $targetApiService->requestData(
                $this->targetApiEndpointPath,
                $this->currentPage,
                $this->dateFrom,
                $this->dateTo
            );

            if ($response['status'] == 429) {
                Log::channel('importlog')->info('Too many requests to API, waiting ' . $this->secondsDelayBetweenRetry . ' sec');

                self::dispatch(
                    $this->currentPage,
                    $this->maxPagesToRequest,
                    $this->targetModel,
                    $this->targetApiEndpointPath,
                    $this->dateFrom,
                    $this->dateTo,
                    $this->accountId
                )->delay(now()->addSeconds($this->secondsDelayBetweenRetry));

                $this->fail();
            }

            foreach($response['data']['data'] as &$item) {
                $item['account_id'] = $this->accountId;
                $item['hash_sum'] = $this->targetModel::generateHashSum($item);
            }
            
            $this->targetModel::insertOrIgnore($response['data']['data']);
            $totalInsert += count($response['data']['data']);

            $this->currentPage++;
            $requestedPages++;
        }

        Log::channel('importlog')->info('Inserted ' . $totalInsert . ' records per job to' . $this->targetModel);

        if (!empty($response['data']['links']['next'])) {
            self::dispatch(
                $this->currentPage,
                $this->maxPagesToRequest,
                $this->targetModel,
                $this->targetApiEndpointPath,
                $this->dateFrom,
                $this->dateTo,
                $this->accountId
            )->delay(now()->addSeconds($this->secondsDelayBetweenNextJob));
        } else {
            Log::channel('importlog')->info('End imprort data from ' . $this->targetModel);
            Log::channel('importlog')->info('Records in request API: ' . $response['data']['meta']['total']);
            Log::channel('importlog')->info('Records in DB: ' . $this->targetModel::count());
        }
    }
}
