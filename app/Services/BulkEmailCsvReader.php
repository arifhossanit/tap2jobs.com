<?php

namespace App\Services;

use Generator;
use SplFileObject;

class BulkEmailCsvReader
{
    public function emailColumn(string $path): ?int
    {
        $file = $this->open($path);
        $header = $file->fgetcsv() ?: [];

        foreach ($header as $index => $column) {
            $normalized = strtolower(trim((string) $column));
            $normalized = ltrim($normalized, "\xEF\xBB\xBF");

            if (in_array($normalized, ['email', 'email_address', 'email address'], true)) {
                return $index;
            }
        }

        return null;
    }

    public function emails(string $path): Generator
    {
        $file = $this->open($path);
        $emailColumn = $this->emailColumn($path);
        $file->fgetcsv();

        if ($emailColumn === null) {
            return;
        }

        while (! $file->eof()) {
            $row = $file->fgetcsv();
            if (! is_array($row) || ! array_key_exists($emailColumn, $row)) {
                continue;
            }

            $email = strtolower(trim((string) $row[$emailColumn]));
            if ($email !== '' && strlen($email) <= 255 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                yield $email;
            }
        }
    }

    private function open(string $path): SplFileObject
    {
        $file = new SplFileObject($path);
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
        $file->setCsvControl($this->delimiter($path), '"', '');

        return $file;
    }

    private function delimiter(string $path): string
    {
        $handle = fopen($path, 'rb');
        $firstLine = $handle === false ? '' : (string) fgets($handle);
        if (is_resource($handle)) {
            fclose($handle);
        }

        $counts = [',' => substr_count($firstLine, ','), ';' => substr_count($firstLine, ';'), "\t" => substr_count($firstLine, "\t")];
        arsort($counts);

        return (string) array_key_first($counts);
    }
}
