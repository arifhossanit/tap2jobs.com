<?php

namespace Tests\Unit;

use Tests\TestCase;

class CandidateProfileMassAssignmentTest extends TestCase
{
    public function test_candidate_profile_controllers_only_forward_validated_input(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/Candidates/CandidateController.php'));

        $this->assertStringContainsString('$input = $request->validated();', $controller);
        $this->assertStringContainsString('updateProfile($input)', $controller);
        $this->assertSame(2, substr_count($controller, 'updateGeneralInformation($request->validated())'));
        $this->assertStringContainsString('$input = $request->validated();', $controller);
        $this->assertStringNotContainsString('updateGeneralInformation($request->all())', $controller);
    }

    public function test_candidate_profile_repository_cannot_mass_assign_security_fields(): void
    {
        $repository = file_get_contents(app_path('Repositories/Candidates/CandidateRepository.php'));
        $profileUpdateStart = strpos($repository, 'public function profileUpdate(array $input): bool');
        $profileUpdate = substr($repository, $profileUpdateStart, 900);
        $generalUpdateStart = strpos($repository, 'public function updateGeneralInformation(array $input)');
        $generalUpdate = substr($repository, $generalUpdateStart, 1700);

        $this->assertStringContainsString('Arr::only($input', $profileUpdate);
        $this->assertStringNotContainsString('$user->update($input)', $profileUpdate);
        $this->assertStringNotContainsString("'owner_id'", $profileUpdate);
        $this->assertStringNotContainsString("'password'", $profileUpdate);
        $this->assertStringNotContainsString('$user->candidate->update($input)', $generalUpdate);
    }

    public function test_candidate_email_change_requires_fresh_verification(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/Candidates/CandidateController.php'));
        $repository = file_get_contents(app_path('Repositories/Candidates/CandidateRepository.php'));
        $javascript = file_get_contents(resource_path('assets/js/candidates/candidate-profile/candidate-general.js'));

        $this->assertStringContainsString("\$userInput['email_verified_at'] = null;", $repository);
        $this->assertStringContainsString('$user->sendEmailVerificationNotification();', $repository);
        $this->assertStringContainsString('Auth::logout();', $controller);
        $this->assertStringContainsString("['redirectUrl' => route('front.candidate.login')]", $controller);
        $this->assertStringContainsString('result.data.redirectUrl', $javascript);
    }
}
