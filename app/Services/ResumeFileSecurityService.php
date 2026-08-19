<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use RuntimeException;
use ZipArchive;

class ResumeFileSecurityService
{
    private const MAX_DOCX_ENTRIES = 2000;

    private const MAX_DOCX_ENTRY_BYTES = 5 * 1024 * 1024;

    private const MAX_DOCX_UNCOMPRESSED_BYTES = 50 * 1024 * 1024;

    public function assertSafeUpload(UploadedFile $file): void
    {
        $path = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());

        if (! is_string($path) || $path === '') {
            throw new RuntimeException('The CV document could not be read.');
        }

        match ($extension) {
            'pdf' => $this->assertSignature($path, '%PDF-'),
            'jpg', 'jpeg' => $this->assertImage($path, ['image/jpeg']),
            'png' => $this->assertImage($path, ['image/png']),
            'doc' => $this->assertSignature($path, hex2bin('D0CF11E0A1B11AE1')),
            'docx' => $this->assertSafeDocxPath($path),
            default => throw new RuntimeException('Unsupported CV document type.'),
        };
    }

    public function assertSafeDocxPath(string $path): void
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            throw new RuntimeException('The CV document is not a valid DOCX file.');
        }

        try {
            $this->assertSafeDocxArchive($zip);
        } finally {
            $zip->close();
        }
    }

    public function assertSafeDocxArchive(ZipArchive $zip): void
    {
        if ($zip->numFiles > self::MAX_DOCX_ENTRIES) {
            throw new RuntimeException('The CV document contains too many files.');
        }

        if ($zip->locateName('[Content_Types].xml') === false || $zip->locateName('word/document.xml') === false) {
            throw new RuntimeException('The CV document is not a valid DOCX file.');
        }

        $totalBytes = 0;
        for ($index = 0; $index < $zip->numFiles; $index++) {
            $entry = $zip->statIndex($index);
            $entryBytes = (int) ($entry['size'] ?? 0);
            $totalBytes += $entryBytes;

            if ($entryBytes > self::MAX_DOCX_ENTRY_BYTES || $totalBytes > self::MAX_DOCX_UNCOMPRESSED_BYTES) {
                throw new RuntimeException('The CV document is too large when extracted.');
            }
        }
    }

    private function assertSignature(string $path, string $signature): void
    {
        $bytes = file_get_contents($path, false, null, 0, strlen($signature));
        if ($bytes !== $signature) {
            throw new RuntimeException('The CV document content does not match its file type.');
        }
    }

    private function assertImage(string $path, array $allowedMimeTypes): void
    {
        $imageInfo = @getimagesize($path);
        if ($imageInfo === false || ! in_array($imageInfo['mime'] ?? null, $allowedMimeTypes, true)) {
            throw new RuntimeException('The CV image is invalid.');
        }
    }
}
