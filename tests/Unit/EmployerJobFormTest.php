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
            'hybrid' => '1',
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
        $this->assertTrue($request->boolean('hybrid'));
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
        $this->assertTrue($job->isFillable('hybrid'));
        $this->assertTrue($job->isFillable('vacancy'));
        $this->assertFalse($job->isFillable('position'));
        $this->assertTrue($job->isFillable('experience_unit'));
        $this->assertTrue($job->isFillable('experience_requirement'));
        $this->assertTrue($job->isFillable('freshers_encouraged'));
        $this->assertSame('boolean', $job->getCasts()['work_from_office']);
        $this->assertSame('boolean', $job->getCasts()['hybrid']);
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

    public function test_job_details_uses_compact_actions_and_negotiable_hidden_salary(): void
    {
        $view = file_get_contents(resource_path('views/front_web/jobs/job_details.blade.php'));
        $styles = file_get_contents(resource_path('assets/front_web_css/job-details.css'));

        $this->assertStringContainsString('fa-solid fa-share-nodes', $view);
        $this->assertStringContainsString('fa-regular fa-flag', $view);
        $this->assertStringContainsString("__('messages.front_job_details.negotiable')", $view);
        $this->assertStringContainsString('job-hero-meta d-flex flex-wrap align-items-center', $view);
        $this->assertStringContainsString('.job-details-page .job-header-action', $styles);
        $this->assertStringContainsString('class="btn job-already-applied-btn ml-2" disabled', $view);
        $this->assertStringContainsString('.job-details-page .job-already-applied-btn:hover', $styles);
        $this->assertStringContainsString('class="btn btn-primary job-apply-btn"', $view);
        $this->assertStringContainsString('.job-details-page .job-apply-btn:hover', $styles);
    }

    public function test_candidate_job_details_preserves_the_loaded_skill_list(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/Web/JobController.php'));

        $this->assertStringContainsString(
            'array_merge($data, $this->jobRepository->getJobDetails($job))',
            $controller
        );
        $this->assertStringNotContainsString(
            '$data = $this->jobRepository->getJobDetails($job);',
            $controller
        );
    }

    public function test_job_details_language_toggle_uses_message_files_without_translating_database_content(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/Web/JobController.php'));
        $view = file_get_contents(resource_path('views/front_web/jobs/job_details.blade.php'));
        $english = require lang_path('en/messages.php');
        $bangla = require lang_path('bn/messages.php');

        $this->assertStringNotContainsString('GoogleTranslate::translate', $controller);
        $this->assertStringNotContainsString('use App\\Utils\\GoogleTranslate;', $controller);
        $this->assertStringContainsString('messages.front_job_details.job_details', $view);
        $this->assertStringNotContainsString("'web.job_details", $view);
        $this->assertSame(array_keys($english['front_job_details']), array_keys($bangla['front_job_details']));
        $this->assertSame('Negotiable', $english['front_job_details']['negotiable']);
        $this->assertSame('আলোচনা সাপেক্ষ', $bangla['front_job_details']['negotiable']);
    }

    public function test_candidate_can_discard_only_their_own_application_draft(): void
    {
        $routes = file_get_contents(base_path('routes/web.php'));
        $controller = file_get_contents(app_path('Http/Controllers/Web/JobApplicationController.php'));
        $view = file_get_contents(resource_path('views/front_web/jobs/job_details.blade.php'));

        $this->assertStringContainsString("->name('discard-job-draft')", $routes);
        $this->assertStringContainsString('public function discardDraft(string $jobId)', $controller);
        $this->assertStringContainsString("->where('candidate_id', \$candidate->id)", $controller);
        $this->assertStringContainsString("->where('status', JobApplication::STATUS_DRAFT)", $controller);
        $this->assertStringContainsString('class="d-inline-block discard-job-draft-form"', $view);
        $this->assertStringContainsString("@method('DELETE')", $view);
        $this->assertStringContainsString('onsubmit="return window.confirm(this.dataset.confirm);"', $view);
        $this->assertStringContainsString("Flash::success(__('messages.flash.job_application_delete'))", $controller);
        $this->assertStringContainsString("return redirect()->route('front.job.details', \$job->job_id);", $controller);
    }

    public function test_job_application_draft_flow_is_transactional_and_has_no_employer_side_effects(): void
    {
        $request = file_get_contents(app_path('Http/Requests/ApplyJobRequest.php'));
        $repository = file_get_contents(app_path('Repositories/JobApplicationRepository.php'));
        $controller = file_get_contents(app_path('Http/Controllers/Web/JobApplicationController.php'));
        $form = file_get_contents(resource_path('views/front_web/jobs/apply_job/apply_job.blade.php'));
        $migration = file_get_contents(database_path('migrations/2026_08_09_120000_harden_job_application_draft_flow.php'));

        $this->assertStringContainsString("'application_type' => 'required|in:apply,draft'", $request);
        $this->assertStringContainsString("'expected_salary' => 'required_if:application_type,apply|nullable|numeric", $request);
        $this->assertStringContainsString("'g-recaptcha-response'] = 'required_if:application_type,apply'", $request);
        $this->assertStringContainsString('return DB::transaction(function () use ($input): bool', $repository);
        $this->assertStringContainsString('->lockForUpdate()', $repository);
        $this->assertStringNotContainsString('$jobApplication->delete();', $repository);
        $this->assertStringContainsString("if (\$input['application_type'] === 'draft')", $controller);
        $this->assertMatchesRegularExpression(
            '/if \(\$input\[\'application_type\'\] === \'draft\'\).*?return \$this->sendResponse.*?\$employerId/s',
            $controller
        );
        $this->assertStringContainsString('class="btn apply-job-draft-button save-draft"', $form);
        $this->assertStringNotContainsString('@if(!$isJobDrafted)', $form);
        $this->assertStringContainsString("->unique(['job_id', 'candidate_id']", $migration);
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

    public function test_create_job_form_restores_custom_fields_after_validation_failure(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/JobController.php'));
        $fields = file_get_contents(resource_path('views/employer/jobs/fields.blade.php'));
        $script = file_get_contents(resource_path('assets/js/jobs/create-edit.js'));

        $this->assertStringContainsString("\$selectedCountryId = old('country_id'", $controller);
        $this->assertStringContainsString("\$data['default_country_states'] = \$selectedCountryId ? getStates(\$selectedCountryId) : [];", $controller);
        $this->assertStringContainsString("\$data['selected_state_cities'] = \$selectedStateId ? getCities(\$selectedStateId) : [];", $controller);

        $this->assertStringContainsString("old('description')", $fields);
        $this->assertStringContainsString("old('key_responsibilities')", $fields);
        $this->assertStringContainsString("old('job_expiry_date'", $fields);
        $this->assertStringContainsString("old('hide_salary'", $fields);
        $this->assertStringContainsString("old('is_freelance'", $fields);
        $this->assertStringContainsString("\$data['selected_state_cities'] ?? []", $fields);

        $this->assertStringContainsString('details.root.innerHTML = $("#job_desc").val() || "";', $script);
        $this->assertStringContainsString('response.root.innerHTML = $("#key_responsibilities").val() || "";', $script);
        $this->assertStringContainsString('$("#saveAsDraft").val($(this).val() === "draft" ? "1" : "0");', $script);
        $this->assertStringNotContainsString('console.log($(this).val());', $script);
    }

    public function test_required_job_editor_and_expiry_fields_have_html_required_attributes(): void
    {
        $createFields = file_get_contents(resource_path('views/employer/jobs/fields.blade.php'));
        $editFields = file_get_contents(resource_path('views/employer/jobs/edit_fields.blade.php'));

        $this->assertStringContainsString("['id' => 'job_desc', 'required']", $createFields);
        $this->assertStringContainsString("['id' => 'key_responsibilities', 'required']", $createFields);
        $this->assertStringContainsString('id="details" aria-required="true"', $createFields);
        $this->assertStringContainsString('id="response" aria-required="true"', $createFields);

        $this->assertStringContainsString("['id' => 'editJobDescription', 'required']", $editFields);
        $this->assertStringContainsString("['id' => 'edit_responsibilities', 'required']", $editFields);
        $this->assertStringContainsString('id="editDetails" aria-required="true"', $editFields);
        $this->assertStringContainsString('id="editResponse" aria-required="true"', $editFields);
        $this->assertStringContainsString('autocomplete="off" required value=', $editFields);
    }

    public function test_location_dropdowns_preserve_valid_selections_during_ajax_refresh(): void
    {
        $locationScript = file_get_contents(resource_path('assets/js/custom/state_country.js'));
        $jobScript = file_get_contents(resource_path('assets/js/jobs/create-edit.js'));
        $validation = file_get_contents(app_path('Http/Requests/Concerns/ValidatesJob.php'));

        $this->assertStringContainsString('const selectedState = $(\'#stateId\').val();', $locationScript);
        $this->assertStringContainsString('const selectedCity = $(\'#cityId\').val();', $locationScript);
        $this->assertStringContainsString('stateStillExists', $locationScript);
        $this->assertStringContainsString('cityStillExists', $locationScript);
        $this->assertStringContainsString("trigger('change.select2')", $locationScript);
        $this->assertStringNotContainsString('$("#countryId").trigger("change");', $jobScript);
        $this->assertStringContainsString('$this->restoreLocationHierarchy();', $validation);
        $this->assertStringContainsString("State::whereKey(\$stateId)->value('country_id')", $validation);
    }

    public function test_job_submit_runs_browser_validation_and_normalizes_salary_first(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/JobController.php'));
        $script = file_get_contents(resource_path('assets/js/jobs/create-edit.js'));
        $createFields = file_get_contents(resource_path('views/employer/jobs/fields.blade.php'));
        $editFields = file_get_contents(resource_path('views/employer/jobs/edit_fields.blade.php'));

        $this->assertStringContainsString('function prepareJobFormForSubmission(formSelector)', $script);
        $this->assertStringContainsString('field.value = removeCommas(field.value).trim();', $script);
        $this->assertStringContainsString('if (!form.checkValidity())', $script);
        $this->assertStringContainsString('displayErrorMessage(Lang.get("js.required_field_messages"));', $script);
        $this->assertStringNotContainsString('form.reportValidity();', $script);
        $this->assertStringContainsString('if (!prepareJobFormForSubmission("#createJobForm"))', $script);
        $this->assertStringContainsString('if (!prepareJobFormForSubmission("#editJobForm"))', $script);
        $this->assertStringContainsString('listenClick("#editJobsSaveBtn, #saveDraft", function(e) {', $script);
        $this->assertMatchesRegularExpression('/listenClick\("#editJobsSaveBtn, #saveDraft", function\(e\) \{\s+e\.preventDefault\(\);/', $script);
        $this->assertStringContainsString("'inputmode' => 'decimal'", $createFields);
        $this->assertStringContainsString("'inputmode' => 'decimal'", $editFields);
        $this->assertStringContainsString("\$data['jobSkills'] = \$job->jobsSkill()->pluck('skill_id')->toArray();", $controller);
        $this->assertStringContainsString("old('jobsSkill', \$data['jobSkills'])", $editFields);
        $this->assertStringContainsString('select.select2("data")', $script);
        $this->assertStringContainsString('countrySelect.find("option").first().val()', $script);
    }

    public function test_job_form_selects_the_first_country_when_no_default_exists(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/JobController.php'));
        $createFields = file_get_contents(resource_path('views/employer/jobs/fields.blade.php'));
        $editFields = file_get_contents(resource_path('views/employer/jobs/edit_fields.blade.php'));
        $script = file_get_contents(resource_path('assets/js/jobs/create-edit.js'));

        $this->assertStringContainsString("\$firstCountryId = array_key_first(\$data['countries']);", $controller);
        $this->assertStringContainsString("\$data['default_country_id'] ?: \$firstCountryId", $controller);
        $this->assertStringContainsString("\$data['selected_country_id'] = \$selectedCountryId;", $controller);
        $this->assertStringContainsString("\$data['selected_country_id']", $editFields);
        $this->assertStringNotContainsString("'placeholder' => __('messages.company.select_country')", $createFields);
        $this->assertStringNotContainsString("'placeholder' => __('messages.company.select_country')", $editFields);
        $this->assertStringContainsString('function initializeJobSelect2(selector, options)', $script);
        $this->assertStringContainsString('const selectedValue = select.val();', $script);
        $this->assertStringContainsString('select.val(selectedValue).trigger("change.select2");', $script);
        $this->assertStringContainsString('countrySelect.val(countrySelect.find("option").first().val());', $script);
    }

    public function test_employer_job_index_renders_flash_messages_as_default_toasts(): void
    {
        $index = file_get_contents(resource_path('views/employer/jobs/index.blade.php'));
        $toasts = file_get_contents(resource_path('views/layouts/flash-toasts.blade.php'));

        $this->assertStringContainsString("@include('layouts.flash-toasts')", $index);
        $this->assertStringNotContainsString("@include('flash::message')", $index);
        $this->assertStringContainsString('displaySuccessMessage(@json($message[\'message\']))', $toasts);
        $this->assertStringContainsString("session()->forget('flash_notification')", $toasts);
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
