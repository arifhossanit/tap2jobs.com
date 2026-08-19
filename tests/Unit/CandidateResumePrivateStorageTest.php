<?php

namespace Tests\Unit;

use Tests\TestCase;

class CandidateResumePrivateStorageTest extends TestCase
{
    public function test_all_resume_writes_use_the_private_resume_disk(): void
    {
        $candidateRepository = file_get_contents(app_path('Repositories/Candidates/CandidateRepository.php'));
        $applicationCvService = file_get_contents(app_path('Services/ApplicationCvService.php'));

        $this->assertStringContainsString("toMediaCollection(Candidate::RESUME_PATH, config('app.resume_disk'))", $candidateRepository);
        $this->assertStringContainsString("toMediaCollection(Candidate::RESUME_PATH, config('app.resume_disk'))", $applicationCvService);
        $this->assertStringNotContainsString("toMediaCollection(Candidate::RESUME_PATH, config('app.media_disc'))", $candidateRepository.$applicationCvService);
        $this->assertSame('private', config('app.resume_disk'));
        $this->assertSame('private', config('filesystems.disks.private.visibility'));
    }

    public function test_application_download_reads_the_disk_recorded_on_the_media(): void
    {
        $repository = file_get_contents(app_path('Repositories/JobApplicationRepository.php'));
        $application = file_get_contents(app_path('Models/JobApplication.php'));

        $this->assertStringContainsString('Storage::disk($documentMedia->disk)', $repository);
        $this->assertStringContainsString('$documentMedia->getPathRelativeToRoot()', $repository);
        $this->assertStringNotContainsString("config('app.media_disc')", $repository);
        $this->assertStringContainsString("route('employer.resume.download', \$this)", $application);
        $this->assertStringNotContainsString('$media->getFullUrl()', $application);
    }

    public function test_existing_resume_migration_copies_then_removes_public_files(): void
    {
        $migration = file_get_contents(database_path('migrations/2026_08_19_000001_move_candidate_resumes_to_private_disk.php'));

        $this->assertStringContainsString("->where('model_type', Candidate::class)", $migration);
        $this->assertStringContainsString("->where('collection_name', Candidate::RESUME_PATH)", $migration);
        $this->assertStringContainsString('$targetDisk->writeStream', $migration);
        $this->assertStringContainsString('$media->forceFill', $migration);
        $this->assertStringContainsString('$sourceDisk->deleteDirectory', $migration);
    }
}
