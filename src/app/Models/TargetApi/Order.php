<?php

namespace App\Models\TargetApi;

use App\Models\ApiIntegration\Account;
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
        'account_id'
    ];

    protected static string $apiPath = 'orders';

    public static function getDateFromForSyncData(Account $account): string
    {
        $latestOrder = Order::where('account_id', $account->id)->orderBy('date', 'desc')->first();

        if (!$latestOrder) {
            return config('targetapi.default_date_from');
        }

        return Carbon::parse($latestOrder->date)->subDay()->format('Y-m-d');
    }
}
