<?php

namespace App\Jobs;

use App\Services\BulkEmailCsvReader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ImportBulkEmailCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 1200;

    public function __construct(
        public string $importId,
        public string $path,
        public string $subject,
        public string $body
    ) {}

    public function handle(BulkEmailCsvReader $reader): void
    {
        $disk = Storage::disk('local');

        if (! $disk->exists($this->path)) {
            return;
        }

        $absolutePath = $disk->path($this->path);
        if ($reader->emailColumn($absolutePath) === null) {
            $disk->delete($this->path);

            return;
        }

        $chunk = [];

        foreach ($reader->emails($absolutePath) as $email) {
            $chunk[] = ['import_id' => $this->importId, 'email' => $email];

            if (count($chunk) === 1000) {
                $this->storeChunk($chunk);
                $chunk = [];
            }
        }

        if ($chunk !== []) {
            $this->storeChunk($chunk);
        }

        DispatchImportedBulkEmail::dispatch($this->importId, $this->subject, $this->body)
            ->onConnection('database');
        $disk->delete($this->path);
    }

    public function failed(Throwable $exception): void
    {
        Storage::disk('local')->delete($this->path);
        if (Schema::hasTable('bulk_email_import_recipients')) {
            DB::table('bulk_email_import_recipients')->where('import_id', $this->importId)->delete();
        }
    }

    private function storeChunk(array $rows): void
    {
        DB::table('bulk_email_import_recipients')->insertOrIgnore($rows);
    }

}
