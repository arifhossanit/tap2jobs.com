<?php

namespace Tests\Unit;

use Tests\TestCase;

class CandidateCvPrivacyTest extends TestCase
{
    public function test_all_available_personal_details_are_included_in_the_cv(): void
    {
        $pdf = file_get_contents(resource_path('views/candidate/profile/application_cv_pdf.blade.php'));

        $this->assertStringContainsString('$personalRows = collect([', $pdf);
        $this->assertStringContainsString("'National ID No.'", $pdf);
        $this->assertStringContainsString("'Passport No.'", $pdf);
        $this->assertStringNotContainsString('include_sensitive_personal_data_in_cv', $pdf);

        $service = file_get_contents(app_path('Services/ApplicationCvService.php'));
        $this->assertStringContainsString("private const TEMPLATE_VERSION = 2", $service);
        $this->assertStringContainsString("'template_version', self::TEMPLATE_VERSION", $service);
    }

    public function test_resume_tab_has_no_cv_privacy_control(): void
    {
        $view = file_get_contents(resource_path('views/candidate/profile/resume.blade.php'));

        $this->assertStringNotContainsString("route('candidate.resumes.privacy')", $view);
        $this->assertStringNotContainsString('name="include_sensitive_personal_data_in_cv"', $view);
    }
}
