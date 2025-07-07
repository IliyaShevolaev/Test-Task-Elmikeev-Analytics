<?php

namespace App\Models\TargetApi;

use Carbon\Carbon;

class Income extends TargetApiModel
{
    protected $fillable = [
        'income_id',
        'number',
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'quantity',
        'total_price',
        'date_close',
        'warehouse_name',
        'nm_id',
    ];

    protected static string $apiPath = 'incomes';

    protected static function getDateFromForSyncData(): string
    {
        return Carbon::parse(Income::orderBy('date', 'desc')->first()->date)->subDay()->format('Y-m-d');
    }
}
