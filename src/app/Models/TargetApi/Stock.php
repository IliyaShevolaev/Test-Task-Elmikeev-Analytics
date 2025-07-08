<?php

namespace App\Models\TargetApi;

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
    ];

    protected static string $apiPath = 'stocks';

    protected static function getDateFromForSyncData(): string
    {
        return Carbon::now()->format('Y-m-d');
    }

    public static function importData(TargetApiService $targetApiService): void
    {
        self::syncData($targetApiService);
    }

    public static function syncData(TargetApiService $targetApiService): void
    {
        static::truncate();
        parent::importData($targetApiService);
    }
}
