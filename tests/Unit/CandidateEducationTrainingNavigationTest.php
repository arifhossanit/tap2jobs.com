<?php

namespace Tests\Unit;

use Tests\TestCase;

class CandidateEducationTrainingNavigationTest extends TestCase
{
    public function test_add_education_and_training_scroll_to_the_opened_form(): void
    {
        $educationScript = file_get_contents(resource_path('assets/js/candidates/candidate-profile/candidate_career_informations.js'));
        $educationTrainingView = file_get_contents(resource_path('views/candidate/profile/education-training.blade.php'));
        $styles = file_get_contents(resource_path('assets/sass/new-custom.scss'));

        $this->assertStringContainsString('scrollToEducationInlineForm();', $educationScript);
        $this->assertStringContainsString("document.querySelector('[data-education-add-form]')", $educationScript);
        $this->assertStringContainsString('trainingFormWrap.getBoundingClientRect().top - stickyOffset', $educationTrainingView);
        $this->assertStringContainsString("behavior: 'smooth'", $educationScript);
        $this->assertStringContainsString('scroll-margin-top: 150px', $styles);
    }

    public function test_education_training_sections_use_the_shared_synchronised_accordion(): void
    {
        $view = file_get_contents(resource_path('views/candidate/profile/education-training.blade.php'));
        $script = file_get_contents(resource_path('assets/js/candidates/candidate-profile/candidate_career_informations.js'));
        $styles = file_get_contents(resource_path('assets/sass/new-custom.scss'));

        $this->assertStringContainsString('id="candidateEducationAccordion"', $view);
        $this->assertSame(3, substr_count($view, 'candidate-education-panel candidate-profile-section'));
        $this->assertSame(3, substr_count($view, 'class="candidate-profile-section__toggle"'));
        $this->assertSame(3, substr_count($view, 'data-bs-parent="#candidateEducationAccordion"'));
        $this->assertSame(3, substr_count($view, 'class="fa-solid fa-chevron-up"'));
        $this->assertStringContainsString('function initCandidateEducationAccordion()', $script);
        $this->assertStringContainsString("menuSelector: '[data-career-section-link]'", $script);
        $this->assertStringContainsString('initCandidateEducationAccordion();', $script);
        $this->assertStringNotContainsString('const careerSectionBodies', $view);
        $this->assertStringContainsString('#candidateEducationAccordion .candidate-profile-section__toggle[aria-expanded="false"] .fa-chevron-up', $styles);
    }

    public function test_education_lookup_data_is_available_before_the_page_bundle_runs(): void
    {
        $view = file_get_contents(resource_path('views/candidate/profile/education-training.blade.php'));

        $lookupConfigPosition = strpos($view, 'window.candidateEducationExamTitleOptions = @json($educationExamTitleOptions);');
        $pushedScriptPosition = strpos($view, "@push('scripts')");

        $this->assertNotFalse($lookupConfigPosition);
        $this->assertNotFalse($pushedScriptPosition);
        $this->assertLessThan($pushedScriptPosition, $lookupConfigPosition);
    }
}
