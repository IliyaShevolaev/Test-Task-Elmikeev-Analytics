<?php

namespace App\Console\Commands\ApiIntegration;

use App\Models\ApiIntegration\Company;
use Illuminate\Console\Command;

class AddCompany extends Command
{
    /**
     * The name and signature of the console command.
     *  Example: artisan api-integration:add-company YOUR-COMPANY-NAME
     * 
     * @var string
     */
    protected $signature = 'api-integration:add-company {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add company';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companyWithSameName = Company::where('name', $this->argument('name'))->first();

        if ($companyWithSameName) {
            $this->warn("Company with this name already exists");
            return;
        }

        Company::create([
            'name' => $this->argument('name')
        ]);
    }
}
