<?php

namespace Tests\Unit;

use App\Services\ApplicationCvService;
use ReflectionMethod;
use Tests\TestCase;

class ApplicationCvWorkflowTest extends TestCase
{
    public function test_application_cv_is_generated_and_integrated_with_the_resume_collection(): void
    {
        $service = file_get_contents(app_path('Services/ApplicationCvService.php'));
        $repository = file_get_contents(app_path('Repositories/JobApplicationRepository.php'));

        $this->assertSame('Application CV', ApplicationCvService::TITLE);
        $this->assertStringContainsString("Pdf::loadView('candidate.profile.application_cv_pdf'", $service);
        $this->assertStringContainsString('addMediaFromString($pdfContent)', $service);
        $this->assertStringContainsString('ApplicationCvService::class)->ensure($candidate)', $repository);
    }

    public function test_application_cv_removes_html_tags_but_preserves_readable_lists(): void
    {
        $plainText = new ReflectionMethod(ApplicationCvService::class, 'plainText');
        $plainText->setAccessible(true);

        $result = $plainText->invoke(new ApplicationCvService(), '<p>Hello <strong>world</strong></p><ul><li>First item</li></ul>');

        $this->assertStringNotContainsString('<p>', $result);
        $this->assertStringNotContainsString('<strong>', $result);
        $this->assertStringContainsString('Hello world', $result);
        $this->assertStringContainsString('• First item', $result);
    }

    public function test_resume_selection_lives_in_the_resume_toolbar_not_the_upload_modal(): void
    {
        $modal = file_get_contents(resource_path('views/candidate/profile/modals/upload_resume_modal.blade.php'));
        $toolbar = file_get_contents(resource_path('views/candidate/profile/resume_table_components/add_button.blade.php'));
        $script = file_get_contents(resource_path('assets/js/candidates/candidate-profile/candidate-resume.js'));
        $candidateRepository = file_get_contents(app_path('Repositories/Candidates/CandidateRepository.php'));

        $this->assertStringNotContainsString('name="selected_cv"', $modal);
        $this->assertStringContainsString('candidate-default-resume-select', $toolbar);
        $this->assertStringContainsString("route('candidate.resumes.default')", $toolbar);
        $this->assertStringContainsString("type: 'PUT'", $script);
        $this->assertStringContainsString('$hasUploadedResume', $candidateRepository);
        $this->assertStringContainsString('resume_upload_limit', $candidateRepository);
    }

    public function test_apply_form_selects_the_saved_default_resume(): void
    {
        $applyForm = file_get_contents(resource_path('views/front_web/jobs/apply_job/apply_job.blade.php'));

        $this->assertStringContainsString('$key == $default_resume', $applyForm);
        $this->assertStringContainsString("['section' => 'resume']", file_get_contents(resource_path('views/candidate/profile/profile_menu.blade.php')));
    }

    public function test_resume_action_opens_a_secure_inline_preview_modal(): void
    {
        $action = file_get_contents(resource_path('views/candidate/profile/resume_table_components/action_button.blade.php'));
        $page = file_get_contents(resource_path('views/candidate/profile/resume.blade.php'));
        $script = file_get_contents(resource_path('assets/js/candidates/candidate-profile/candidate-resume.js'));
        $controller = file_get_contents(app_path('Http/Controllers/Candidates/CandidateController.php'));
        $previewService = file_get_contents(app_path('Services/ResumePreviewService.php'));

        $this->assertStringContainsString('preview-resume', $action);
        $this->assertStringNotContainsString('fa-download', $action);
        $this->assertStringContainsString('resume_preview_modal', $page);
        $this->assertStringContainsString('candidateResumePreviewFrame', $script);
        $this->assertStringContainsString('resumePreviewService->preview', $controller);
        $this->assertStringContainsString("where('model_id', \$candidate->id)", $controller);
        $this->assertStringContainsString("strtolower(\$media->extension) === 'docx'", $previewService);
        $this->assertStringContainsString("'Content-Disposition' => 'inline", $previewService);
    }
}
