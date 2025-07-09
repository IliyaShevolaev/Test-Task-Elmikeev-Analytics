<?php

namespace App\Console\Commands\ApiIntegration;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\ApiIntegration\ApiService;

class AddApiService extends Command
{
    /**
     * The name and signature of the console command.
     * Example: artisan api-integration:add-api-service YOUR-SERVICE-NAME YOUR-URL
     * @var string
     */
    protected $signature = 'api-integration:add-api-service {name} {url}';

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
            'name' => $this->argument('name'),
            'url' => $this->argument('url'),
            'class_name' => 'App\Services\\' . $this->argument('name'),
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

use App\Models\ApiIntegration\Account;
use Illuminate\Support\Facades\Http;
use App\Models\ApiIntegration\ApiService;
use App\Models\ApiIntegration\ApiToken;

class {$className}
{
    private \$apiUrl;
    private \$apiKey;
    private \$account;
    private \$apiService;

    public function __construct()
    {
        \$apiService = ApiService::where('class_name', self::class)->first();

        \$this->apiService = \$apiService;
        \$this->apiUrl = \$apiService->url;
    }

    public function withAccount(Account \$account)
    {
        \$this->account = \$account;

        \$token = ApiToken::where('account_id', \$account->id)
            ->where('api_service_id', \$this->apiService->id)
            ->first();

        if (!\$token) {
            throw new \Exception("API token not found");
        }

        \$this->apiKey = \$token->token;
    }

    public function requestData(): array {
        // Your code here
    }

    public function storeData(): void {
        // Your code here
    }
}
PHP;
    }
}
