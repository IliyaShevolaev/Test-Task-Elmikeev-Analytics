<?php

namespace App\Models\TargetApi;

use App\Models\ApiIntegration\Account;
use Carbon\Carbon;
use App\Services\TargetApiService;

class Stock extends TargetApiModel
{
    protected $fillable = [
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'quantity',
        'is_supply',
        'is_realization',
        'quantity_full',
        'warehouse_name',
        'in_way_to_client',
        'in_way_from_client',
        'nm_id',
        'subject',
        'category',
        'brand',
        'sc_code',
        'price',
        'discount',
        'account_id'
    ];

    protected static string $apiPath = 'stocks';

    protected static function getDateFromForSyncData(Account $account): string
    {
        return Carbon::now()->format('Y-m-d');
    }

    public static function importData(TargetApiService $targetApiService, Account $account): void
    {
        self::syncData($targetApiService, $account);
    }

    public static function syncData(TargetApiService $targetApiService, Account $account): void
    {
        static::truncateAccountData($account);
        parent::syncData($targetApiService, $account);
    }
}
