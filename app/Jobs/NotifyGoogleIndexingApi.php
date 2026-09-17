<?php

namespace App\Jobs;

use App\Services\GoogleIndexingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyGoogleIndexingApi implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $uniqueFor = 600;

    public bool $afterCommit = true;

    public function __construct(public string $url, public string $type)
    {
        $this->onQueue((string) config('seo.indexing_api.queue', 'default'));
    }

    public function uniqueId(): string
    {
        return sha1($this->type.'|'.$this->url);
    }

    public function backoff(): array
    {
        return [60, 300, 900];
    }

    public function handle(GoogleIndexingService $indexing): void
    {
        $indexing->notify($this->url, $this->type);
    }
}
