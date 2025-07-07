<?php

use Carbon\Carbon;
use App\Models\TargetApi\Sale;
use App\Models\TargetApi\Order;
use App\Models\TargetApi\Stock;
use App\Models\TargetApi\Income;
use App\Services\TargetApiService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function (TargetApiService $targetApiService) {
    //dd(Order::count());
    //Order::importData($targetApiService);
});