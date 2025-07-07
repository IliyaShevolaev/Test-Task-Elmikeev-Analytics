<?php

namespace App\Services;

use App\Jobs\InsertDataJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TargetApiService
{
    private const MAX_PAGES_TO_REQUEST = 10;
    private const LIMIT_PAGES_TO_REQUEST = 500;

    private $apiUrl;
    private $apiKey;

    public function __construct()
    {
        $this->apiUrl = 'http://' . config('targetapi.target_api_host') . ':' . config('targetapi.target_api_port');
        $this->apiKey = config('targetapi.target_api_access_key');
    }

    public function requestData(
        string $endPointPath,
        int $page,
        string $dateFrom,
        string $dateTo
    ): array {
        $response = Http::get($this->apiUrl . '/api/' . $endPointPath, [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'key' => $this->apiKey,
            'limit' => self::LIMIT_PAGES_TO_REQUEST,
        ]);

        return [
            'data' => $response->json(),
            'status' => $response->status(),
        ];
    }

    public function storeData(
        string $targetApiModel,
        string $targetApiEndpointPath,
        string $dateFrom,
        string $dateTo
    ): void {
        Log::channel('importlog')->info('Starting imprort data from ' . $targetApiModel);

        InsertDataJob::dispatch(
            1,
            self::MAX_PAGES_TO_REQUEST,
            $targetApiModel,
            $targetApiEndpointPath,
            $dateFrom,
            $dateTo
        )->delay(now()->addSeconds(1));
    }
}
