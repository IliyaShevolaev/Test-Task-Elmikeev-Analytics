<?php

namespace App\Models\TargetApi;

use App\Models\ApiIntegration\Account;
use Carbon\Carbon;
use App\Services\TargetApiService;
use Illuminate\Database\Eloquent\Model;

abstract class TargetApiModel extends Model
{
    protected static string $apiPath;

    abstract protected static function getDateFromForSyncData(Account $account): string;

    public static function importData(TargetApiService $targetApiService, Account $account): void
    {
        $targetApiService->withAccount($account);

        $targetApiService->storeData(
            static::class,
            static::$apiPath,
            config('targetapi.default_date_from'),
            config('targetapi.default_date_to'),
        );
    }

    public static function syncData(TargetApiService $targetApiService, Account $account): void
    {
        $targetApiService->withAccount($account);

        $targetApiService->storeData(
            static::class,
            static::$apiPath,
            static::getDateFromForSyncData($account),
            config('targetapi.default_date_to')
        );
    }

    public static function truncateAccountData(Account $account): void
    {
        static::where('account_id', $account->id)->delete();
    }

    protected static function generateHashSum(array $data): string
    {
        $stringToHash = '';

        foreach ($data as $item) {
            $stringToHash = $item . '-' . $stringToHash;
        }

        return hash('sha256', $stringToHash);
    }
}
