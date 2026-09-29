<?php

namespace Tests\Feature;

use App\Services\BulkEmailCsvReader;
use PHPUnit\Framework\TestCase;

class BulkEmailCsvReaderTest extends TestCase
{
    public function test_it_streams_valid_normalized_emails_from_a_bom_csv(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'bulk-email-');
        file_put_contents($path, "\xEF\xBB\xBFName,Email\nOne, FIRST@Example.com \nTwo,invalid\nThree,second@example.com\n");

        try {
            $reader = new BulkEmailCsvReader;

            $this->assertSame(1, $reader->emailColumn($path));
            $this->assertSame(
                ['first@example.com', 'second@example.com'],
                iterator_to_array($reader->emails($path), false)
            );
        } finally {
            @unlink($path);
        }
    }

    public function test_it_supports_semicolon_delimited_files_and_rejects_missing_email_headers(): void
    {
        $validPath = tempnam(sys_get_temp_dir(), 'bulk-email-');
        $invalidPath = tempnam(sys_get_temp_dir(), 'bulk-email-');
        file_put_contents($validPath, "name;email_address\nOne;one@example.com\n");
        file_put_contents($invalidPath, "name,phone\nOne,123\n");

        try {
            $reader = new BulkEmailCsvReader;

            $this->assertSame(['one@example.com'], iterator_to_array($reader->emails($validPath), false));
            $this->assertNull($reader->emailColumn($invalidPath));
        } finally {
            @unlink($validPath);
            @unlink($invalidPath);
        }
    }
}
