@props([
    'jobCategories',
    'jobSkills',
    'genders',
    'careerLevels',
    'functionalAreas',
    'jobTypes',
    'maximumExperience' => 30,
    'input' => [],
])

@php
    $selectedType = (string) data_get($input, 'job_type', '');
@endphp

<aside class="latest-job-left find-jobs-filter">
    <form class="find-jobs-filter__form" autocomplete="off">
        <div class="find-jobs-filter__header">
            <h2><i class="fa-solid fa-sliders" aria-hidden="true"></i>{{ __('messages.common.filters') }}</h2>
            <button type="button" class="btn reset-filter">{{ __('web.reset_filter') }}</button>
        </div>

        <div class="form-group find-jobs-filter__group">
            <label for="searchByLocation">@lang('web.web_jobs.search_by_keywords')</label>
            <input type="text" class="form-control" value="{{ data_get($input, 'keywords') }}"
                   name="listing-search" id="searchByLocation"
                   placeholder="@lang('web.web_home.job_title_keywords_company')">
        </div>

        <div class="form-group find-jobs-filter__group">
            <label for="searchCategories">@lang('web.post_menu.categories')</label>
            <select class="form-select" name="search-categories" id="searchCategories">
                <option value="">@lang('web.job_menu.none')</option>
                @foreach ($jobCategories as $key => $value)
                    <option value="{{ $key }}" @selected((string) data_get($input, 'categories') === (string) $key)>
                        {{ html_entity_decode($value) }}
                    </option>
                @endforeach
            </select>
        </div>

        @if ($jobSkills->isNotEmpty())
            <div class="form-group find-jobs-filter__group">
                <label for="searchSkill">@lang('messages.candidate.candidate_skill')</label>
                <select class="form-select" name="search-skills" id="searchSkill">
                    <option value="">@lang('web.job_menu.none')</option>
                    @foreach ($jobSkills as $key => $value)
                        <option value="{{ $key }}">{{ html_entity_decode($value) }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="form-group find-jobs-filter__group">
            <label for="searchGender">@lang('messages.candidate.gender')</label>
            <select class="form-select" name="search-gender" id="searchGender">
                <option value="">@lang('web.job_menu.none')</option>
                @foreach ($genders as $key => $value)
                    <option value="{{ $key }}">{{ html_entity_decode($value) }}</option>
                @endforeach
            </select>
        </div>

        @if ($careerLevels->isNotEmpty())
            <div class="form-group find-jobs-filter__group">
                <label for="searchCareerLevel">@lang('messages.job.career_level')</label>
                <select class="form-select" name="search-career-level" id="searchCareerLevel">
                    <option value="">@lang('web.job_menu.none')</option>
                    @foreach ($careerLevels as $key => $value)
                        <option value="{{ $key }}">{{ html_entity_decode($value) }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if ($functionalAreas->isNotEmpty())
            <div class="form-group find-jobs-filter__group">
                <label for="searchFunctionalArea">@lang('messages.job.functional_area')</label>
                <select class="form-select" name="search-functional-area" id="searchFunctionalArea">
                    <option value="">@lang('web.job_menu.none')</option>
                    @foreach ($functionalAreas as $key => $value)
                        <option value="{{ $key }}">{{ html_entity_decode($value) }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="form-group find-jobs-filter__group find-jobs-filter__freshers">
            <input class="form-check-input" type="checkbox" id="fresherJobs" value="1">
            <label class="form-check-label" for="fresherJobs">
                <span>{{ __('messages.job.fresher_jobs') }}</span>
                {{-- <small>{{ __('messages.job.fresher_jobs_hint') }}</small> --}}
            </label>
        </div>

        @if ($jobTypes->isNotEmpty())
            <div class="form-group find-jobs-filter__group find-jobs-filter__types">
                <button class="find-jobs-filter__toggle" type="button" data-bs-toggle="collapse"
                        data-bs-target="#jobTypeFilterOptions" aria-expanded="{{ $selectedType !== '' ? 'true' : 'false' }}"
                        aria-controls="jobTypeFilterOptions">
                    <span>@lang('web.job_menu.type')</span>
                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                </button>
                <div class="collapse {{ $selectedType !== '' ? 'show' : '' }}" id="jobTypeFilterOptions">
                    <div class="find-jobs-filter__type-options">
                        @foreach ($jobTypes as $jobType)
                            @continue($jobType->jobs_count <= 0)
                            <div class="find-jobs-filter__type-option">
                                <input class="form-check-input jobType" type="checkbox" name="job-type[]"
                                       id="jobType{{ $jobType->id }}" value="{{ $jobType->id }}"
                                       @checked($selectedType === (string) $jobType->id)>
                                <label class="form-check-label" for="jobType{{ $jobType->id }}"
                                       title="{{ $jobType->name }}">
                                    <span>{{ html_entity_decode(Str::limit($jobType->name, 42)) }}</span>
                                    <small>{{ $jobType->jobs_count }}</small>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="form-group find-jobs-filter__group find-jobs-filter__range">
            <label for="salaryRange">{{ __('messages.job.salary_range') }}</label>
            <div dir="ltr">
                <input type="text" id="salaryRange" data-max="150000" autocomplete="off" tabindex="-1" readonly>
            </div>
        </div>

        <div class="form-group find-jobs-filter__group find-jobs-filter__range">
            <label for="jobExperience">{{ __('messages.job.maximum_experience') }}</label>
            <div dir="ltr">
                <input type="text" id="jobExperience" data-max="{{ max(1, (int) $maximumExperience) }}" autocomplete="off" tabindex="-1" readonly>
            </div>
        </div>
    </form>
</aside>
