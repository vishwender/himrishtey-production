<?php

namespace App\Console\Commands;

use App\Models\Site;
use App\Services\SiteDatabaseService;
use Illuminate\Console\Command;

class MigrateSites extends Command
{
    protected $signature = 'sites:migrate {--site=* : Site codes; defaults to all configured sites} {--force : Run in production}';

    protected $description = 'Run shared website and admin migrations in each site database';

    public function handle(SiteDatabaseService $databases): int
    {
        $codes = $this->option('site');
        $sites = Site::query()->when($codes, fn ($query) => $query->whereIn('code', $codes))->get();
        if ($sites->isEmpty() || array_diff($codes, $sites->pluck('code')->all())) {
            $this->error('No matching sites or an unknown site code was supplied.');

            return self::FAILURE;
        }

        foreach ($sites as $site) {
            $this->info("Migrating site database for {$site->name}");
            $databases->connect($site);
            $result = $this->call('migrate', [
                '--database' => 'site',
                '--path' => 'database/migrations/site',
                '--force' => $this->option('force'),
            ]);
            if ($result !== self::SUCCESS) {
                return $result;
            }
        }

        return self::SUCCESS;
    }
}
