<?php

namespace App\Console\Commands\ApiIntegration;

use App\Models\ApiIntegration\Account;
use App\Models\ApiIntegration\Company;
use Illuminate\Console\Command;

class AddAccount extends Command
{
    /**
     * The name and signature of the console command.
     * Example: artisan api-integration:add-account YOUR-ACCOUNT-NAME EXISTS-COMPANY-NAME
     *
     * @var string
     */
    protected $signature = 'api-integration:add-account {name} {companyName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $company = Company::where('name', $this->argument('companyName'))->first();

        if (!$company) {
            $this->warn("No company with this name");
            return;
        }

        Account::updateOrCreate([
            'name' => $this->argument('name'),
            'company_id' => $company->id
        ]);
    }
}
