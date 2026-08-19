<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CandidateResumeAuthorizationTest extends TestCase
{
    public function test_candidate_download_route_uses_the_strict_candidate_controller(): void
    {
        $route = Route::getRoutes()->getByName('download.resume');

        $this->assertSame(
            'App\\Http\\Controllers\\Candidates\\CandidateController@downloadResume',
            $route->getActionName()
        );
        $this->assertContains('auth', $route->gatherMiddleware());
        $this->assertContains('role:Candidate', $route->gatherMiddleware());
    }

    public function test_resume_delete_is_scoped_by_owner_model_and_collection(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/Candidates/CandidateController.php'));
        $methodStart = strpos($controller, 'public function deletedResume(Media $media)');
        $method = substr($controller, $methodStart, 1700);

        $this->assertStringContainsString("->where('model_type', \\App\\Models\\Candidate::class)", $method);
        $this->assertStringContainsString("->where('model_id', getLoggedInUser()->candidate->id)", $method);
        $this->assertStringContainsString("->where('collection_name', \\App\\Models\\Candidate::RESUME_PATH)", $method);
        $this->assertStringContainsString('$mediaFile->delete()', $method);
        $this->assertStringNotContainsString('$media->delete()', $method);
    }

    public function test_admin_resume_operations_cannot_target_other_media_collections(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/CandidateController.php'));

        $this->assertSame(2, substr_count($controller, "->where('model_type', Candidate::class)"));
        $this->assertSame(2, substr_count($controller, "->where('collection_name', Candidate::RESUME_PATH)"));
    }

    public function test_employer_application_download_rechecks_resume_owner_model_and_collection(): void
    {
        $repository = file_get_contents(app_path('Repositories/JobApplicationRepository.php'));
        $methodStart = strpos($repository, 'public function downloadMedia(JobApplication $jobApplication)');
        $method = substr($repository, $methodStart, 1400);

        $this->assertStringContainsString("->where('model_type', Candidate::class)", $method);
        $this->assertStringContainsString('->where(\'model_id\', $jobApplication->candidate_id)', $method);
        $this->assertStringContainsString("->where('collection_name', Candidate::RESUME_PATH)", $method);
        $this->assertStringContainsString('->whereKey($jobApplication->resume_id)', $method);
        $this->assertStringNotContainsString('Media::find($jobApplication->resume_id)', $method);
    }
}
