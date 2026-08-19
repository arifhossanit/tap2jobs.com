<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CandidateProfilePrivacyTest extends TestCase
{
    public function test_candidate_details_requires_an_authenticated_employer_or_admin(): void
    {
        $route = Route::getRoutes()->getByName('front.candidate.details');
        $middleware = $route->gatherMiddleware();

        $this->assertContains('auth', $middleware);
        $this->assertContains('role:Admin|Employer', $middleware);
        $this->get('/candidate-details/not-a-real-candidate')->assertRedirect();
    }

    public function test_candidate_details_only_loads_active_verified_candidates_and_404s_cleanly(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/Web/CandidateController.php'));

        $this->assertStringContainsString("->where('is_active', true)", $controller);
        $this->assertStringContainsString("->whereNotNull('email_verified_at')", $controller);
        $this->assertStringContainsString('->firstOrFail()', $controller);
        $this->assertStringNotContainsString('->first();', $controller);
    }
}
