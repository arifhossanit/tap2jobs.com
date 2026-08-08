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
}
