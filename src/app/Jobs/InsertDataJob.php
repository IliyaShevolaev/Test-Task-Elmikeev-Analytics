<?php

namespace App\Jobs;

use App\Services\TargetApiService;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class InsertDataJob implements ShouldQueue
{
    use Queueable;

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
        while ($this->maxPagesToRequest > $requestedPages) {
            $responseJson = $targetApiService->requestData(
                $this->targetApiEndpointPath,
                $this->currentPage,
                $this->dateFrom,
                $this->dateTo
            );

            if (empty($responseJson['data'])) {
                break;
            }

            $this->targetModel::insert($responseJson['data']);

            $this->currentPage++;
            $requestedPages++;
        }

        if (!empty($responseJson['links']['next'])) {
            self::dispatch(
                $this->currentPage,
                $this->maxPagesToRequest,
                $this->targetModel,
                $this->targetApiEndpointPath,
                $this->dateFrom,
                $this->dateTo
            )->delay(now()->addSeconds(1));
        }
    }
}
