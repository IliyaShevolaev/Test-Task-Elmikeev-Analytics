<?php

namespace App\Console\Commands\ApiIntegration;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\ApiIntegration\ApiService;

class AddApiService extends Command
{
    /**
     * The name and signature of the console command.
     * Example: artisan api-integration:add-api-service YOUR-SERVICE-NAME
     * @var string
     */
    protected $signature = 'api-integration:add-api-service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add api service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiServiceWithSameName = ApiService::where('name', $this->argument('name'))->first();

        if ($apiServiceWithSameName) {
            $this->warn("ApiService with this name already exists");
            return;
        }

        ApiService::create([
            'name' => $this->argument('name')
        ]);

        $pathToService = app_path("Services/{$this->argument('name')}.php");

        File::put($pathToService, $this->getServiceTemplate($this->argument('name')));
    }

    private function getServiceTemplate(string $className): string
    {
        return 
        <<<PHP
        <?php

        namespace App\Services;

        class {$className}
        {
            public function request()
            {
                
            }
        }
        PHP;
    }
}
