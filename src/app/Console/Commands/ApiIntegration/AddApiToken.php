<?php

namespace App\Console\Commands\ApiIntegration;

use App\Models\ApiIntegration\Account;
use App\Models\ApiIntegration\ApiService;
use App\Models\ApiIntegration\ApiToken;
use App\Models\ApiIntegration\TokenType;
use Illuminate\Console\Command;

class AddApiToken extends Command
{
    /**
     * The name and signature of the console command.
     * Example: artisan api-integration:add-api-token YOUR-TOKEN ACCOUNT-ID SERVICE-ID TOKEN-TYPE-NAME
     *
     * @var string
     */
    protected $signature = 'api-integration:add-api-token {token} {accountId} {apiServiceId} {tokenType}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add api token';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $account = Account::find($this->argument('accountId'));
        $apiService = ApiService::find($this->argument('apiServiceId'));
        $tokenType = TokenType::where('name', $this->argument('tokenType'))->first();

        if (!$account || !$apiService || !$tokenType) {
            $this->warn('Wrong id data');
            return;
        }

        if (!$apiService->tokenTypes->contains($tokenType->id)) {
            $this->warn('Token type is not supported by the service');
            return;
        }

        ApiToken::create([
            'token' => $this->argument('token'),
            'account_id' => $account->id,
            'api_service_id' => $apiService->id,
            'token_type_id' => $tokenType->id,
        ]);
    }
}
