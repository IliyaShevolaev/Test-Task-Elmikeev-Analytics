<?php

namespace App\Models\TargetApi;

use Carbon\Carbon;
use App\Services\TargetApiService;
use Illuminate\Database\Eloquent\Model;

abstract class TargetApiModel extends Model
{
    protected static string $apiPath;

    abstract protected static function getDateFromForSyncData(): string;

    public static function importData(TargetApiService $targetApiService): void
    {
        static::truncate();
        
        $targetApiService->storeData(
            static::class,
            static::$apiPath,
            config('targetapi.default_date_from'),
            config('targetapi.default_date_to'),
        );
    }

    public static function syncData(TargetApiService $targetApiService): void
    {
        if (static::count() > 0) {
            $targetApiService->storeData(
                static::class,
                static::$apiPath,
                static::getDateFromForSyncData(),
                config('targetapi.default_date_to')
            );
        } else {
            static::importData($targetApiService);
        }
    }
}
