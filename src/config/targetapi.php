<?php 

return [
    'target_api_host' => env('TARGET_API_HOST'),
    'target_api_port' => env('TARGET_API_PORT'),
    'target_api_access_key' => env('TARGET_API_ACCESS_KEY'),

    'default_date_from' => '2020-01-01',
    'default_date_to' => '2030-01-01',

    'name' => 'wb_api',
    'class_name' => 'App\Services\TargetApiService',
];