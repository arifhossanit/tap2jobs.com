<?php

namespace Tests\Unit;

use App\Services\ResumeFileSecurityService;
use Illuminate\Http\UploadedFile;
use RuntimeException;
use Tests\TestCase;
use ZipArchive;

class CandidateResumeFileSecurityTest extends TestCase
{
    public function test_pdf_extension_cannot_hide_non_pdf_content(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'resume-security-');
        file_put_contents($path, '<html>not a pdf</html>');

        try {
            $file = new UploadedFile($path, 'resume.pdf', 'application/pdf', null, true);

            $this->expectException(RuntimeException::class);
            (new ResumeFileSecurityService())->assertSafeUpload($file);
        } finally {
            @unlink($path);
        }
    }

    public function test_valid_pdf_signature_is_accepted(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'resume-security-');
        file_put_contents($path, "%PDF-1.4\n%%EOF");

        try {
            $file = new UploadedFile($path, 'resume.pdf', 'application/pdf', null, true);
            (new ResumeFileSecurityService())->assertSafeUpload($file);
            $this->addToAssertionCount(1);
        } finally {
            @unlink($path);
        }
    }

    public function test_docx_zip_bomb_sized_entry_is_rejected(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'resume-security-');
        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', '<Types/>');
        $zip->addFromString('word/document.xml', str_repeat('A', (5 * 1024 * 1024) + 1));
        $zip->close();

        try {
            $this->expectException(RuntimeException::class);
            (new ResumeFileSecurityService())->assertSafeDocxPath($path);
        } finally {
            @unlink($path);
        }
    }

    public function test_upload_and_preview_both_use_the_security_service(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/Candidates/CandidateController.php'));
        $request = file_get_contents(app_path('Http/Requests/CandidateResumeUploadRequest.php'));
        $preview = file_get_contents(app_path('Services/ResumePreviewService.php'));

        $this->assertStringContainsString('uploadResume(CandidateResumeUploadRequest $request)', $controller);
        $this->assertStringContainsString('ResumeFileSecurityService::class', $request);
        $this->assertStringContainsString('$this->fileSecurity->assertSafeDocxArchive($zip)', $preview);
    }
}
