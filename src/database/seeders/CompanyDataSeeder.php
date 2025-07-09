<?php

namespace Database\Seeders;

use App\Models\ApiIntegration\Account;
use App\Models\ApiIntegration\ApiService;
use App\Models\ApiIntegration\ApiToken;
use App\Models\ApiIntegration\Company;
use App\Models\ApiIntegration\TokenType;
use Illuminate\Database\Seeder;

class CompanyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::create([
            'name' => 'company'
        ]);

        $account = Account::create([
            'name' => 'account',
            'company_id' => $company->id
        ]);

        $api = ApiService::create([
            'name' => config('targetapi.name'),
            'url' => 'http://' . config('targetapi.target_api_host') . ':' . config('targetapi.target_api_port'),
            'class_name' => config('targetapi.class_name')
        ]);

        $tokenType = TokenType::create([
            'name' => 'access_key'
        ]);

        $api->tokenTypes()->attach($tokenType->id);

        $apiToken = ApiToken::create([
            'token' => config('targetapi.target_api_access_key'),
            'account_id' => $account->id,
            'api_service_id' => $api->id,
            'token_type_id' => $tokenType->id
        ]);
    }
}
