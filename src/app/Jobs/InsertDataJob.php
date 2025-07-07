<?php

namespace App\Jobs;

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

    /**
     * Create a new job instance.
     */
    public function __construct(
        int $currentPage,
        int $maxPagesToRequest,
        string $targetModel,
        string $targetApiEndpointPath,
        string $dateFrom,
        string $dateTo
    ) {
        $this->currentPage = $currentPage;
        $this->maxPagesToRequest = $maxPagesToRequest;
        $this->targetModel = $targetModel;
        $this->targetApiEndpointPath = $targetApiEndpointPath;
        $this->dateTo = $dateTo;
        $this->dateFrom = $dateFrom;
    }

    /**
     * Execute the job.
     */
    public function handle(TargetApiService $targetApiService): void
    {
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
                    $this->dateTo
                )->delay(now()->addSeconds($this->secondsDelayBetweenRetry));

                $this->fail();
                return;
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
                $this->dateTo
            )->delay(now()->addSeconds($this->secondsDelayBetweenNextJob));
        } else {
            Log::channel('importlog')->info('End imprort data from ' . $this->targetModel);
            Log::channel('importlog')->info('Records in request API: ' . $response['data']['meta']['total']);
            Log::channel('importlog')->info('Records in DB: ' . $this->targetModel::count());
        }
    }
}
