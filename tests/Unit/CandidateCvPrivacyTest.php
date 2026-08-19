<?php

namespace Tests\Unit;

use Tests\TestCase;

class CandidateCvPrivacyTest extends TestCase
{
    public function test_sensitive_cv_details_are_opt_in_and_default_off(): void
    {
        $migration = file_get_contents(database_path('migrations/2026_08_19_000002_add_cv_privacy_preference_to_candidates.php'));
        $pdf = file_get_contents(resource_path('views/candidate/profile/application_cv_pdf.blade.php'));

        $this->assertStringContainsString("->default(false)", $migration);
        $this->assertStringContainsString('$candidate->include_sensitive_personal_data_in_cv ? collect([', $pdf);
        $this->assertStringContainsString("'National ID No.'", $pdf);
        $this->assertStringContainsString(': collect();', $pdf);
    }

    public function test_candidate_has_an_explicit_validated_cv_privacy_control(): void
    {
        $request = file_get_contents(app_path('Http/Requests/CandidateUpdateCvPrivacyRequest.php'));
        $controller = file_get_contents(app_path('Http/Controllers/Candidates/CandidateController.php'));
        $view = file_get_contents(resource_path('views/candidate/profile/resume.blade.php'));

        $this->assertStringContainsString("['required', 'boolean']", $request);
        $this->assertStringContainsString('$candidate->update($request->validated())', $controller);
        $this->assertStringContainsString('$this->applicationCvService->ensure($candidate->fresh())', $controller);
        $this->assertStringContainsString("route('candidate.resumes.privacy')", $view);
        $this->assertStringContainsString('name="include_sensitive_personal_data_in_cv"', $view);
    }
}
