<?php

namespace Tests\Unit;

use Tests\TestCase;

class CandidateProfileValidationTest extends TestCase
{
    public function test_personal_details_reject_future_dates_and_invalid_enums(): void
    {
        $request = file_get_contents(app_path('Http/Requests/CandidateUpdatePersonalDetailsRequest.php'));

        $this->assertStringContainsString("'dob' => 'nullable|date|before_or_equal:today'", $request);
        $this->assertStringContainsString("'gender' => 'required|integer|in:0,1'", $request);
        $this->assertStringContainsString("'marital_status_id' => 'required|integer|exists:marital_status,id'", $request);
        $this->assertStringContainsString("'passport_issue_date' => 'nullable|date|before_or_equal:today'", $request);
    }

    public function test_location_ids_must_belong_to_the_selected_parent(): void
    {
        $address = file_get_contents(app_path('Http/Requests/CandidateUpdateAddressDetailsRequest.php'));
        $experience = file_get_contents(app_path('Http/Requests/CreateCandidateExperienceRequest.php'));
        $education = file_get_contents(app_path('Http/Requests/CreateCandidateEducationRequest.php'));

        foreach ([$address, $experience, $education] as $source) {
            $this->assertStringContainsString("->where('country_id', \$this->input('country_id'))", $source);
            $this->assertStringContainsString("->where('state_id', \$this->input('state_id'))", $source);
        }
    }

    public function test_experience_dates_and_education_cgpa_are_consistent(): void
    {
        $experience = file_get_contents(app_path('Http/Requests/CreateCandidateExperienceRequest.php'));
        $education = file_get_contents(app_path('Http/Requests/CreateCandidateEducationRequest.php'));

        $this->assertStringContainsString("'required|date|before_or_equal:today'", $experience);
        $this->assertStringContainsString('Rule::requiredIf(fn () => ! $this->boolean(\'currently_working\'))', $experience);
        $this->assertStringContainsString('after_or_equal:start_date', $experience);
        $this->assertStringContainsString('before_or_equal:today', $experience);
        $this->assertStringContainsString("'|lte:scale'", $education);
    }
}
