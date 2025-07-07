<?php

namespace App\Models\TargetApi;

use Carbon\Carbon;

class Order extends TargetApiModel
{
    protected $fillable = [
        'g_number',
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'total_price',
        'discount_percent',
        'warehouse_name',
        'oblast',
        'income_id',
        'odid',
        'nm_id',
        'subject',
        'category',
        'brand',
        'is_cancel',
        'cancel_dt',
    ];

    protected static string $apiPath = 'orders';

    protected static function getDateFromForSyncData(): string
    {
        return Carbon::parse(Income::orderBy('date', 'desc')->first()->date)->subDay()->format('Y-m-d');
    }
}
