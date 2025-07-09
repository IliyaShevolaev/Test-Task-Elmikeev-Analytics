<?php

namespace App\Models\TargetApi;

use App\Models\ApiIntegration\Account;
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
        'account_id'
    ];

    protected static string $apiPath = 'incomes';

    protected static function getDateFromForSyncData(Account $account): string
    {
        $latestIncome = Income::where('account_id', $account->id)->orderBy('date', 'desc')->first();

        return Carbon::parse($latestIncome->date)->subDay()->format('Y-m-d');
    }
}
