<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateCurrencyRates extends Command
{
    protected $signature = 'currency:update
        {--api= : Free FX API URL (default: https://api.frankfurter.app/latest?from=EUR)}
        {--timeout=10 : HTTP timeout in seconds}';

    protected $description = 'Update hardcoded currency rates from a free FX API';

    public function handle(): int
    {
        $apiUrl = $this->option('api') ?? 'https://api.frankfurter.app/latest?from=EUR';

        $this->info('Fetching rates from: ' . $apiUrl);

        try {
            $response = Http::timeout((int) $this->option('timeout'))
                ->acceptJson()
                ->get($apiUrl);

            if (! $response->successful()) {
                $this->error('API returned status: ' . $response->status());
                return Command::FAILURE;
            }

            $data = $response->json();
            $rates = $data['rates'] ?? [];

            if (empty($rates)) {
                $this->error('No rates found in API response.');
                return Command::FAILURE;
            }

            $configPath = config_path('currencies.php');
            $config = require $configPath;

            $updated = 0;
            foreach ($config['rates'] as $code => $oldRate) {
                if ($code === 'EUR') continue; // base

                $apiRate = $rates[$code] ?? null;
                if ($apiRate) {
                    $config['rates'][$code] = round((float) $apiRate, 4);
                    $updated++;
                }
            }

            $this->info("Updated {$updated} currency rates.");

            $export = var_export($config, true);
            $php = "<?php\n\nreturn {$export};\n";
            file_put_contents($configPath, $php);

            $this->info('Rates written to: ' . $configPath);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
