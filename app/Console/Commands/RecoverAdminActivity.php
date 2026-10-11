<?php

namespace App\Console\Commands;

use App\Models\AdminActivityLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RecoverAdminActivity extends Command
{
    protected $signature = 'activities:recover';

    protected $description = 'Recover staff audit records queued during database errors';

    public function handle(): int
    {
        $disk = Storage::disk('local');
        $count = 0;
        foreach ($disk->files('audit-outbox') as $path) {
            if (! str_ends_with($path, '.json')) {
                continue;
            }
            $lock = fopen($disk->path($path.'.lock'), 'c');
            if (! $lock || ! flock($lock, LOCK_EX | LOCK_NB)) {
                if ($lock) {
                    fclose($lock);
                }

                continue;
            }
            try {
                if (! $disk->exists($path)) {
                    continue;
                }
                $attributes = json_decode($disk->get($path), true, 512, JSON_THROW_ON_ERROR);
                $eventId = $attributes['metadata']['audit_event_id'];
                if (! AdminActivityLog::where('metadata->audit_event_id', $eventId)->exists()) {
                    AdminActivityLog::create($attributes);
                }
                $disk->delete($path);
                $count++;
            } catch (\Throwable $exception) {
                report($exception);
                $this->error('Audit recovery failed; queued records have been retained.');

                return self::FAILURE;
            } finally {
                flock($lock, LOCK_UN);
                fclose($lock);
            }
        }
        $this->info("Recovered {$count} staff audit records.");

        return self::SUCCESS;
    }
}
