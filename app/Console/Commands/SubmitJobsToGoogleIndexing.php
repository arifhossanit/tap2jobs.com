<?php

namespace App\Console\Commands;

use App\Jobs\NotifyGoogleIndexingApi;
use App\Models\Job;
use App\Services\GoogleIndexingService;
use Illuminate\Console\Command;

class SubmitJobsToGoogleIndexing extends Command
{
    protected $signature = 'seo:index-jobs
                            {--type=updated : updated, deleted, or expired}
                            {--limit= : Maximum URLs to enqueue}';

    protected $description = 'Queue eligible job URLs for the Google Indexing API';

    public function handle(GoogleIndexingService $indexing): int
    {
        if (! $indexing->enabled()) {
            $this->error('Google Indexing API is disabled or its credentials file is missing.');

            return self::FAILURE;
        }

        $type = strtolower((string) $this->option('type'));
        if (! in_array($type, ['updated', 'deleted', 'expired'], true)) {
            $this->error('The --type option must be updated, deleted, or expired.');

            return self::INVALID;
        }

        $limit = min(
            max(1, (int) ($this->option('limit') ?: config('seo.indexing_api.batch_size', 180))),
            200
        );
        $notificationType = $type === 'updated'
            ? GoogleIndexingService::URL_UPDATED
            : GoogleIndexingService::URL_DELETED;

        $query = Job::query()->select(['id', 'slug', 'updated_at'])->latest('updated_at');
        if ($type === 'updated') {
            $query->availableForPublic();
        } elseif ($type === 'expired') {
            $query->whereDate('job_expiry_date', now()->subDay()->toDateString());
        } else {
            $query->whereDate('job_expiry_date', '<', now()->toDateString());
        }

        $jobs = $query->limit($limit)->get();
        foreach ($jobs as $job) {
            NotifyGoogleIndexingApi::dispatch($job->front_url, $notificationType);
        }

        $this->info("Queued {$jobs->count()} {$notificationType} notification(s).");

        return self::SUCCESS;
    }
}
