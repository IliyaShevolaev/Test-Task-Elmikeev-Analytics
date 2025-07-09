<?php

namespace App\Models\TargetApi;

use App\Models\ApiIntegration\Account;
use Carbon\Carbon;

class Sale extends TargetApiModel
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
        'is_supply',
        'is_realization',
        'promo_code_discount',
        'warehouse_name',
        'country_name',
        'oblast_okrug_name',
        'region_name',
        'income_id',
        'sale_id',
        'odid',
        'spp',
        'for_pay',
        'finished_price',
        'price_with_disc',
        'nm_id',
        'subject',
        'category',
        'brand',
        'is_storno',
        'account_id'
    ];

    protected static string $apiPath = 'sales';

    protected static function getDateFromForSyncData(Account $account): string
    {
        $latestSale = Sale::where('account_id', $account->id)->orderBy('date', 'desc')->first();

        return Carbon::parse($latestSale->date)->subDay()->format('Y-m-d');
    }
}
