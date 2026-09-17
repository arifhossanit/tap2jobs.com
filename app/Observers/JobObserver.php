<?php

namespace App\Observers;

use App\Jobs\NotifyGoogleIndexingApi;
use App\Models\Job;
use App\Services\GoogleIndexingService;
use Illuminate\Support\Carbon;

class JobObserver
{
    public function saved(Job $job): void
    {
        if (! config('seo.indexing_api.enabled')) {
            return;
        }

        $wasPublic = ! $job->wasRecentlyCreated && $this->attributesWerePublic($job->getOriginal());
        $isPublic = $job->isApplyable();

        if ($isPublic) {
            $this->dispatch($job, GoogleIndexingService::URL_UPDATED);
        } elseif ($wasPublic) {
            $this->dispatch($job, GoogleIndexingService::URL_DELETED);
        }
    }

    public function deleted(Job $job): void
    {
        if (config('seo.indexing_api.enabled') && $job->job_id) {
            $this->dispatch($job, GoogleIndexingService::URL_DELETED);
        }
    }

    private function attributesWerePublic(array $attributes): bool
    {
        if (empty($attributes['job_expiry_date'])) {
            return false;
        }

        return (int) ($attributes['status'] ?? Job::STATUS_DRAFT) === Job::STATUS_OPEN
            && (int) ($attributes['is_suspended'] ?? Job::YES) === Job::NOT_SUSPENDED
            && Carbon::parse($attributes['job_expiry_date'])->endOfDay()->isFuture();
    }

    private function dispatch(Job $job, string $type): void
    {
        NotifyGoogleIndexingApi::dispatch($job->front_url, $type);
    }
}
