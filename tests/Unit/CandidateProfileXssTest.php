<?php

namespace Tests\Unit;

use App\Http\Middleware\XSS;
use Illuminate\Http\Request;
use Tests\TestCase;

class CandidateProfileXssTest extends TestCase
{
    public function test_candidate_search_escapes_candidate_names(): void
    {
        $view = file_get_contents(resource_path('views/livewire/candidate-search.blade.php'));

        $this->assertStringNotContainsString('{!! $candidate->user->full_name !!}', $view);
        $this->assertStringContainsString('{{ $candidate->user->full_name }}', $view);
    }

    public function test_xss_middleware_removes_scripts_and_event_handlers(): void
    {
        $request = Request::create('/', 'POST', [
            'first_name' => '<img src=x onerror="alert(1)">Alice<script>alert(2)</script>',
            'career_summary' => '<p onclick="alert(3)"><strong>Safe summary</strong></p>',
            'password' => 'keep<script>literal</script>',
        ]);

        $response = (new XSS())->handle($request, function (Request $sanitizedRequest) {
            return response()->json($sanitizedRequest->all());
        });
        $payload = json_decode($response->getContent(), true);

        $this->assertStringNotContainsString('<script', $payload['first_name']);
        $this->assertStringNotContainsString('onerror', $payload['first_name']);
        $this->assertStringNotContainsString('onclick', $payload['career_summary']);
        $this->assertStringContainsString('<strong>Safe summary</strong>', $payload['career_summary']);
        $this->assertSame('keep<script>literal</script>', $payload['password']);
    }
}
