<?php

namespace App\Models\TargetApi;

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
        'is_storno'
    ];

    protected static string $apiPath = 'sales';

    protected static function getDateFromForSyncData(): string
    {
        return Carbon::parse(Income::orderBy('date', 'desc')->first()->date)->subDay()->format('Y-m-d');
    }
}
