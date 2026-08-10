<?php

namespace Tests\Unit;

use Tests\TestCase;

class FrontJobSearchFilterTest extends TestCase
{
    public function test_search_jobs_page_loads_the_reusable_filter_component(): void
    {
        $page = file_get_contents(resource_path('views/front_web/jobs/index.blade.php'));
        $component = file_get_contents(resource_path('views/components/front/job-search-filter.blade.php'));

        $this->assertStringContainsString('<x-front.job-search-filter', $page);
        $this->assertStringContainsString('id="fresherJobs"', $component);
        $this->assertStringContainsString('data-bs-target="#jobTypeFilterOptions"', $component);
        $this->assertStringContainsString('id="salaryRange"', $component);
        $this->assertStringNotContainsString('id="salaryFrom"', $component);
        $this->assertStringNotContainsString('id="salaryTo"', $component);
    }

    public function test_filter_script_and_livewire_support_the_new_range_filters(): void
    {
        $script = file_get_contents(resource_path('assets/js/jobs/front/job_search.js'));
        $component = file_get_contents(app_path('Livewire/JobSearch.php'));

        $this->assertStringContainsString("type: 'double'", $script);
        $this->assertStringContainsString("Livewire.dispatch('changeSalaryRange'", $script);
        $this->assertStringContainsString("listenChange('#fresherJobs'", $script);
        $this->assertStringContainsString("public bool \$freshersOnly = false", $component);
        $this->assertStringContainsString("where('experience', '<='", $component);
        $this->assertStringContainsString("where('freshers_encouraged', true)", $component);
        $this->assertStringContainsString("where('salary_to', '>=', \$this->salaryFrom)", $component);
        $this->assertStringContainsString("where('salary_from', '<=', \$this->salaryTo)", $component);
    }
}
