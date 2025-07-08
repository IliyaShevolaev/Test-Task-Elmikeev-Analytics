<?php

namespace App\Console\Commands\ApiIntegration;

use App\Models\ApiIntegration\TokenType;
use Illuminate\Console\Command;

class AddTokenType extends Command
{
    /**
     * The name and signature of the console command.
     * Example: artisan api-integration:add-token-type YOUR-TOKEN-TYPE-NAME
     *
     * @var string
     */
    protected $signature = 'api-integration:add-token-type {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add token type';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tokenTypeWithSameName = TokenType::where('name', $this->argument('name'))->first();

        if ($tokenTypeWithSameName) {
            $this->warn("Token of this type already exists");
            return;
        }

        TokenType::create([
            'name' => $this->argument('name')
        ]);
    }
}
