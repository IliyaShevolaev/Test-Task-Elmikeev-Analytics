<?php

namespace App\Services;

use App\Jobs\InsertDataJob;
use App\Models\ApiIntegration\Account;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\ApiIntegration\ApiService;
use App\Models\ApiIntegration\ApiToken;

class TargetApiService
{
    private const MAX_PAGES_TO_REQUEST = 10;
    private const LIMIT_PAGES_TO_REQUEST = 500;

    private $apiUrl;
    private $apiKey;
    private $account;
    private $apiService;

    public function __construct()
    {
        $apiService = ApiService::where('class_name', self::class)->first();

        $this->apiService = $apiService;
        $this->apiUrl = $apiService->url;
    }

    public function withAccount(Account $account)
    {
        $this->account = $account;

        $token = ApiToken::where('account_id', $account->id)->where('api_service_id', $this->apiService->id)->first();

        if (!$token) {
            throw new \Exception("API token not found");
        }

        $this->apiKey = $token->token;
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
        string $dateTo,
    ): void {
        Log::channel('importlog')->info('Starting imprort data from ' . $targetApiModel);

        InsertDataJob::dispatch(
            1,
            self::MAX_PAGES_TO_REQUEST,
            $targetApiModel,
            $targetApiEndpointPath,
            $dateFrom,
            $dateTo,
            $this->account->id
        )->delay(now()->addSeconds(1));
    }
}
