<?php

namespace App\Console\Commands\ApiIntegration;

use Illuminate\Console\Command;
use App\Models\ApiIntegration\TokenType;
use App\Models\ApiIntegration\ApiService;

class AttachTokenTypeToService extends Command
{
    /**
     * The name and signature of the console command.
     * Example: artisan api-integration:attach-token-type-to-service SERVICE-ID TOKEN-TYPE-ID
     * 
     * @var string
     */
    protected $signature = 'api-integration:attach-token-type-to-service {serviceId} {tokenTypeId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attach token type to api service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = ApiService::find($this->argument('serviceId'));
        $tokenType = TokenType::find($this->argument('tokenTypeId'));

        if (!$service || !$tokenType) {
            $this->warn('Wrong id data');
            return;
        }

        if ($service->tokenTypes->contains($this->argument('tokenTypeId'))) {
            $this->warn('This token type is already attached');
            return;
        }

        $service->tokenTypes()->attach($this->argument('tokenTypeId'));
    }
}
