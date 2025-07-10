<?php

use App\Models\ApiIntegration\Account;
use App\Models\TargetApi\Income;
use App\Services\TargetApiService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function (TargetApiService $targetApiService) {
    Income::syncData($targetApiService, Account::first());
});
