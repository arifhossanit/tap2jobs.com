<?php

namespace Tests\Unit;

use App\Http\Requests\CreateJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Job;
use App\Models\Company;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Mockery;
use ReflectionMethod;
use Tests\TestCase;

class EmployerJobFormTest extends TestCase
{
    public function test_employer_job_fields_have_strict_server_side_rules(): void
    {
        $request = CreateJobRequest::create('/employer/jobs', 'POST');
        $request->setRouteResolver(fn () => $this->namedRoute('job.store'));

        $rules = $request->rules();

        $this->assertContains('required', $rules['employment_status']);
        $this->assertContains('required', $rules['vacancy']);
        $this->assertContains('numeric', $rules['salary_from']);
        $this->assertContains('gte:salary_from', $rules['salary_to']);
        $this->assertContains('required', $rules['description']);
        $this->assertContains('required', $rules['key_responsibilities']);
        $this->assertContains('array', $rules['jobsSkill']);
        $this->assertContains('required', $rules['experience_unit']);
        $this->assertContains('required', $rules['experience_requirement']);
        $this->assertContains('boolean', $rules['freshers_encouraged']);
    }

    public function test_admin_job_form_keeps_employment_status_optional_but_requires_vacancy(): void
    {
        $request = CreateJobRequest::create('/admin/jobs', 'POST');
        $request->setRouteResolver(fn () => $this->namedRoute('admin.job.store'));

        $rules = $request->rules();

        $this->assertContains('nullable', $rules['employment_status']);
        $this->assertContains('required', $rules['vacancy']);
        $this->assertArrayNotHasKey('position', $rules);
    }

    public function test_employer_job_input_normalizes_booleans_and_freelance_status(): void
    {
        $request = CreateJobRequest::create('/employer/jobs', 'POST', [
            'employment_status' => Job::EMPLOYMENT_STATUS_FREELANCE,
            'work_from_home' => '1',
            'hide_salary' => '1',
            'salary_from' => '1,200',
            'salary_to' => '2,400',
            'experience_unit' => Job::EXPERIENCE_UNIT_MONTH_YEAR,
            'experience_requirement' => '6 months - 1 year',
            'freshers_encouraged' => '1',
        ]);
        $request->setRouteResolver(fn () => $this->namedRoute('job.store'));

        $prepare = new ReflectionMethod($request, 'prepareForValidation');
        $prepare->setAccessible(true);
        $prepare->invoke($request);

        $this->assertTrue($request->boolean('is_freelance'));
        $this->assertTrue($request->boolean('work_from_home'));
        $this->assertFalse($request->boolean('work_from_office'));
        $this->assertSame('1200', $request->input('salary_from'));
        $this->assertSame('2400', $request->input('salary_to'));
        $this->assertSame(0, $request->input('experience'));
        $this->assertTrue($request->boolean('freshers_encouraged'));
    }

    public function test_employer_can_only_authorize_updates_for_own_jobs(): void
    {
        $employer = Mockery::mock();
        $employer->owner_id = 13;
        $employer->shouldReceive('hasRole')->with('Employer')->andReturnTrue();

        Auth::shouldReceive('check')->andReturnTrue();
        Auth::shouldReceive('user')->andReturn($employer);

        $ownJob = (new Job())->forceFill(['company_id' => 13]);
        $otherJob = (new Job())->forceFill(['company_id' => 99]);

        $this->assertTrue($this->updateRequestFor($ownJob)->authorize());
        $this->assertFalse($this->updateRequestFor($otherJob)->authorize());
    }

    public function test_job_update_preserves_saved_location_when_select2_omits_it(): void
    {
        $job = (new Job())->forceFill([
            'country_id' => 18,
            'state_id' => 181,
            'city_id' => 1810,
        ]);
        $request = $this->updateRequestFor($job);

        $prepare = new ReflectionMethod($request, 'prepareForValidation');
        $prepare->setAccessible(true);
        $prepare->invoke($request);

        $this->assertSame(18, $request->input('country_id'));
        $this->assertSame(181, $request->input('state_id'));
        $this->assertSame(1810, $request->input('city_id'));
    }

    public function test_job_model_persists_all_new_form_fields(): void
    {
        $job = new Job();

        $this->assertTrue($job->isFillable('employment_status'));
        $this->assertTrue($job->isFillable('work_from_office'));
        $this->assertTrue($job->isFillable('work_from_home'));
        $this->assertTrue($job->isFillable('vacancy'));
        $this->assertFalse($job->isFillable('position'));
        $this->assertTrue($job->isFillable('experience_unit'));
        $this->assertTrue($job->isFillable('experience_requirement'));
        $this->assertTrue($job->isFillable('freshers_encouraged'));
        $this->assertSame('boolean', $job->getCasts()['work_from_office']);
        $this->assertSame('integer', $job->getCasts()['vacancy']);
        $this->assertSame('boolean', $job->getCasts()['freshers_encouraged']);
    }

    public function test_job_formats_month_year_and_mixed_experience_ranges(): void
    {
        $yearJob = (new Job())->forceFill([
            'experience_unit' => Job::EXPERIENCE_UNIT_YEAR,
            'experience_requirement' => '1-2',
        ]);
        $monthJob = (new Job())->forceFill([
            'experience_unit' => Job::EXPERIENCE_UNIT_MONTH,
            'experience_requirement' => '6-11',
        ]);
        $mixedJob = (new Job())->forceFill([
            'experience_unit' => Job::EXPERIENCE_UNIT_MONTH_YEAR,
            'experience_requirement' => '6 months - 1 year',
        ]);

        $this->assertSame('1-2 Years', $yearJob->formatted_experience);
        $this->assertSame('6-11 Months', $monthJob->formatted_experience);
        $this->assertSame('6 months - 1 year', $mixedJob->formatted_experience);
    }

    public function test_job_repairs_legacy_quill_list_attributes(): void
    {
        $job = (new Job())->forceFill([
            'description' => '<ol><li data-list=\\bullet\\><span class=\\ql-ui\\ contenteditable=\\false\\></span>First item</li></ol>',
            'key_responsibilities' => '<ol><li data-list=\\ordered\\>First responsibility</li></ol>',
        ]);

        $this->assertStringContainsString('data-list="bullet"', $job->description);
        $this->assertStringContainsString('class="ql-ui"', $job->description);
        $this->assertStringContainsString('contenteditable="false"', $job->description);
        $this->assertStringContainsString('data-list="ordered"', $job->key_responsibilities);
    }

    public function test_job_details_renders_saved_editor_html_without_nl2br(): void
    {
        $view = file_get_contents(resource_path('views/front_web/jobs/job_details.blade.php'));
        $styles = file_get_contents(resource_path('assets/front_web_css/job-details.css'));

        $this->assertStringContainsString('job-description job-editor-content', $view);
        $this->assertStringContainsString('key-responsibilities job-editor-content', $view);
        $this->assertStringNotContainsString('nl2br($job->description)', $view);
        $this->assertStringNotContainsString('nl2br($job->key_responsibilities)', $view);
        $this->assertStringContainsString('li[data-list="bullet"]', $styles);
        $this->assertStringContainsString('li[data-list="ordered"]', $styles);
    }

    public function test_company_overview_uses_canonical_company_data_and_safe_links(): void
    {
        $view = file_get_contents(resource_path('views/front_web/jobs/job_details.blade.php'));
        $company = new Company();

        $this->assertStringContainsString('$company?->company_name', $view);
        $this->assertStringContainsString('href="{{ route(\'front.company.details\'', $view);
        $this->assertStringNotContainsString('hred=', $view);
        $this->assertStringContainsString('rel="noopener noreferrer"', $view);
        $this->assertStringContainsString("'+'.\$companyRegionCode.' '.\$companyPhone", $view);
        $this->assertStringContainsString('employer-image.png', $company->company_url);
    }

    private function namedRoute(string $name): Route
    {
        return (new Route(['POST'], '/', fn () => null))->name($name);
    }

    private function updateRequestFor(Job $job): UpdateJobRequest
    {
        $route = Mockery::mock();
        $route->shouldReceive('parameter')->with('job', null)->andReturn($job);
        $route->shouldReceive('named')->with('job.store', 'job.update')->andReturnTrue();

        $request = UpdateJobRequest::create('/employer/jobs/1', 'PUT');
        $request->setRouteResolver(fn () => $route);

        return $request;
    }
}
