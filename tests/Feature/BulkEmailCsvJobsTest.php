<?php

namespace Tests\Feature;

use App\Jobs\DispatchBulkEmail;
use App\Jobs\DispatchImportedBulkEmail;
use App\Jobs\ImportBulkEmailCsv;
use App\Services\BulkEmailCsvReader;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BulkEmailCsvJobsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');

        Schema::create('bulk_email_import_recipients', function (Blueprint $table) {
            $table->id();
            $table->uuid('import_id');
            $table->string('email');
            $table->timestamp('queued_at')->nullable();
            $table->unique(['import_id', 'email']);
        });
    }

    public function test_import_streams_large_files_and_deduplicates_recipients(): void
    {
        Storage::fake('local');
        Queue::fake([DispatchImportedBulkEmail::class]);

        $rows = ["email,name"];
        for ($index = 0; $index < 1201; $index++) {
            $rows[] = "person{$index}@example.com,Person {$index}";
        }
        $rows[] = 'PERSON0@example.com,Duplicate';
        $rows[] = 'invalid,Invalid';
        Storage::disk('local')->put('bulk-email-imports/test.csv', implode("\n", $rows));

        $job = new ImportBulkEmailCsv(
            '3ef6a176-f474-49e7-aade-1d5a3812de07',
            'bulk-email-imports/test.csv',
            'Subject',
            '<p>Body</p>'
        );
        $job->handle(new BulkEmailCsvReader);

        $this->assertSame(1201, DB::table('bulk_email_import_recipients')->count());
        Storage::disk('local')->assertMissing('bulk-email-imports/test.csv');
        Queue::assertPushed(DispatchImportedBulkEmail::class, 1);
    }

    public function test_dispatcher_creates_constant_sized_chunks_and_cleans_staging_rows(): void
    {
        Queue::fake([DispatchBulkEmail::class]);
        $importId = '3ef6a176-f474-49e7-aade-1d5a3812de07';

        foreach (array_chunk(range(0, 1000), 250) as $indexes) {
            DB::table('bulk_email_import_recipients')->insert(array_map(
                fn (int $index) => ['import_id' => $importId, 'email' => "person{$index}@example.com"],
                $indexes
            ));
        }

        (new DispatchImportedBulkEmail($importId, 'Subject', '<p>Body</p>'))->handle();

        $sizes = [];
        Queue::assertPushed(DispatchBulkEmail::class, function (DispatchBulkEmail $job) use (&$sizes): bool {
            $sizes[] = count($job->recipients);

            return true;
        });

        $this->assertSame([500, 500, 1], $sizes);
        $this->assertSame(0, DB::table('bulk_email_import_recipients')->count());
    }
}
