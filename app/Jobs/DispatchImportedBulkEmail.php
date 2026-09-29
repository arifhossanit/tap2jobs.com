<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class DispatchImportedBulkEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 1200;

    public function __construct(
        public string $importId,
        public string $subject,
        public string $body
    ) {}

    public function handle(): void
    {
        $delaySeconds = DB::table('bulk_email_import_recipients')
            ->where('import_id', $this->importId)
            ->whereNotNull('queued_at')
            ->count() * 2;

        DB::table('bulk_email_import_recipients')
            ->where('import_id', $this->importId)
            ->whereNull('queued_at')
            ->orderBy('id')
            ->chunkById(500, function ($rows) use (&$delaySeconds): void {
                $ids = $rows->pluck('id')->all();
                $emails = $rows->pluck('email')->all();

                DB::transaction(function () use ($ids, $emails, $delaySeconds): void {
                    DispatchBulkEmail::dispatch('custom', $emails, $this->subject, $this->body)
                        ->onConnection('database')
                        ->delay(now()->addSeconds($delaySeconds));

                    DB::table('bulk_email_import_recipients')
                        ->whereIn('id', $ids)
                        ->update(['queued_at' => now()]);
                });

                $delaySeconds += count($emails) * 2;
            });

        DB::table('bulk_email_import_recipients')->where('import_id', $this->importId)->delete();
    }
}
