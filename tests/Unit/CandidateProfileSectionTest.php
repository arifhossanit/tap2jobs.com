<?php

namespace Tests\Unit;

use Tests\TestCase;

class CandidateProfileSectionTest extends TestCase
{
    public function test_profile_section_is_whitelisted_before_building_the_view_name(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/Candidates/CandidateController.php'));
        $whitelistPosition = strpos($controller, '$allowedSections = [');
        $abortPosition = strpos($controller, 'abort_unless(in_array($sectionName, $allowedSections, true), 404);');
        $viewPosition = strpos($controller, 'return view("candidate.profile.$sectionName"');

        $this->assertNotFalse($whitelistPosition);
        $this->assertNotFalse($abortPosition);
        $this->assertNotFalse($viewPosition);
        $this->assertLessThan($viewPosition, $abortPosition);
        $this->assertStringContainsString("'personal-information'", $controller);
        $this->assertStringContainsString("'resume'", $controller);
    }
}
