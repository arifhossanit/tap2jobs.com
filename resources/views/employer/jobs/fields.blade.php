<div class="row">
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('job_title', __('messages.job.job_title').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('job_title', null, ['class' => 'form-control','required', 'placeholder' => __('messages.job.job_title')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('job_type_id', __('messages.job.job_type').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('job_type_id', $data['jobType'],null, ['id'=>'jobTypeId','class' => 'form-select','placeholder' => __('messages.company.select_job_type'),'data-control'=>'select2','required']) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('job_category_id', __('messages.job_category.job_category').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('job_category_id', $data['jobCategory'],null, ['id'=>'jobCategoryId','class' => 'form-select','placeholder' => __('messages.company.select_job_category'),'data-control'=>'select2','required']) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('skill_id', __('messages.job.job_skill').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div class="input-group flex-nowrap">
            {{ Form::select('jobsSkill[]', $data['jobSkill'], old('jobsSkill'), ['class' => 'form-select job-skill-select', 'id' => 'SkillId', 'multiple' => true, 'data-placeholder' => __('messages.company.select_job_skill'), 'required']) }}
            <div class="input-group-text border-0">
                <a href="javascript:void(0)" class="text-gray-500 createSkillModal" title="{{ __('messages.common.add') }}" data-bs-toggle="tooltip">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('tagId', __('messages.job_tag.show_job_tag').':', ['class' => 'form-label']) }}
        <div class="input-group flex-nowrap">
            {{ Form::select('jobTag[]', $data['jobTag'], null, ['class' => 'form-select job-tag-select', 'id' => 'tagId', 'multiple' => true, 'data-placeholder' => __('messages.company.select_job_tag')]) }}
            <div class="input-group-text border-0">
                <a href="javascript:void(0)" class="text-gray-500 createJobTagModal" title="{{ __('messages.common.add') }}" data-bs-toggle="tooltip">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>
    </div>
    @include('employer.jobs.employment_workplace_fields')
    <div class="col-xl-12 col-md-12 col-sm-12 mb-5">
        {{ Form::label('description', 'Requirements:', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div class="job-rich-editor-shell">
            <div id="details" class="job-rich-editor" aria-required="true"></div>
            <div class="job-rich-editor-resize-handle" role="separator" aria-orientation="horizontal" title="Resize editor"></div>
        </div>
        {{ Form::hidden('description', old('description'), ['id' => 'job_desc', 'required']) }}
    </div>
    <div class="col-xl-12 col-md-6 col-sm-12 mb-5">
        {{ Form::label('key_responsibilities', __('messages.job.key_responsibilities').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div class="job-rich-editor-shell">
            <div id="response" class="job-rich-editor" aria-required="true"></div>
            <div class="job-rich-editor-resize-handle" role="separator" aria-orientation="horizontal" title="Resize editor"></div>
        </div>
        {{ Form::hidden('key_responsibilities', old('key_responsibilities'), ['id' => 'key_responsibilities', 'required']) }}
    </div>
    <div class="col-xl-12 col-md-6 col-sm-12 mb-5">
        {{ Form::label('compensation_and_other_benefits', 'Compensation and other benefits:', ['class' => 'form-label']) }}
        <div class="job-rich-editor-shell">
            <div id="compensationAndBenefits" class="job-rich-editor"></div>
            <div class="job-rich-editor-resize-handle" role="separator" aria-orientation="horizontal" title="Resize editor"></div>
        </div>
        {{ Form::hidden('compensation_and_other_benefits', old('compensation_and_other_benefits'), ['id' => 'compensation_and_other_benefits']) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('no_preference', __('messages.candidate.gender').':', ['class' => 'form-label']) }}
        {{ Form::select('no_preference', $data['preference'], null, ['id'=>'preferenceId','class' => 'form-select','data-control'=>'select2','placeholder' => __('messages.company.select_gender')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('job_expiry_date', __('messages.job.job_expiry_date').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div class="input-group">
            <div class="input-group-text border-0">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <input type="text" name="job_expiry_date" id="availableAt" class="form-control expiryDatepicker  {{(getLoggedInUser()->theme_mode) ? 'bg-light' : 'bg-white'}}" autocomplete="off" required value="{{ old('job_expiry_date', isset($job->job_expiry_date) ? $job->job_expiry_date : null) }}" placeholder="{{__('messages.job.job_expiry_date')}}">
{{--            {{ Form::text('job_expiry_date', isset($job->job_expiry_date) ? $job->job_expiry_date : null, ['class' => 'form-control expiryDatepicker', 'required', 'autocomplete' => 'off', 'placeholder' => __('messages.job.job_expiry_date')]) }}--}}
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('salary_from', __('messages.job.salary_from').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('salary_from', null, ['class' => 'form-control salary', 'id' => 'fromSalary', 'required', 'autocomplete' => 'off', 'inputmode' => 'decimal', 'placeholder' => __('messages.job.salary_from')]) }}
    </div>
    <div class="col-xl-5 col-md-5 col-sm-12 mb-5">
        {{ Form::label('salary_to', __('messages.job.salary_to').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('salary_to', null, ['class' => 'form-control salary', 'id' => 'toSalary', 'required', 'autocomplete' => 'off', 'inputmode' => 'decimal', 'placeholder' =>__('messages.job.salary_to')]) }}
        <span id="salaryToErrorMsg" class="text-danger"></span>
    </div>
    <div class="col-xl-1 col-md-1 col-sm-12 mb-5">
        <label class="form-label">{{ __('messages.job.hide_salary').':' }}</label><br>
        <label class="form-check form-switch form-switch-sm {{ checkLanguageSession() == 'ar' ? 'float-end' : 'float-start' }}">
            <input type="checkbox" name="hide_salary" class="form-check-input"
                   value="1"
                   id="salary" {{ old('hide_salary', isset($job) ? $job->hide_salary : false) ? 'checked' : '' }}>
            <input type="hidden" name="is_freelance" value="{{ old('is_freelance', isset($job) ? $job->is_freelance : 0) }}">
        </label>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('currency_id', __('messages.job.currency').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('currency_id', $data['currencies'], null,
                ['id'=>'currencyId','class' => 'form-select','placeholder' => __('messages.company.select_currency'),'data-control'=>'select2','required']) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('salary_period_id', __('messages.job.salary_period').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('salary_period_id', $data['salaryPeriods'], null, ['id'=>'salaryPeriodsId','class' => 'form-select','placeholder' => __('messages.company.select_salary_period'),'data-control'=>'select2','required']) }}
    </div>
    <div class="col-xl-3 col-md-6 col-sm-12 mb-5">
        {{ Form::label('country', __('messages.company.country').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('country_id', $data['countries'], $data['selected_country_id'] ?? $data['default_country_id'] ?? null, ['id'=>'countryId','class' => 'form-select','data-control'=>'select2','required']) }}
    </div>
    <div class="col-xl-3 col-md-6 col-sm-12 mb-5">
        {{ Form::label('state', __('messages.company.state').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('state_id', $data['default_country_states'] ?? [], old('state_id'), ['id'=>'stateId','class' => 'form-select','placeholder' => __('messages.company.select_state'),'data-control'=>'select2','required']) }}
    </div>
    <div class="col-xl-3 col-md-6 col-sm-12 mb-5">
        {{ Form::label('city', __('messages.company.city').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('city_id', $data['selected_state_cities'] ?? [], old('city_id'), ['id'=>'cityId','class' => 'form-select','placeholder' => __('messages.company.select_city'),'data-control'=>'select2','required']) }}
    </div>
    <div class="col-xl-3 col-md-6 col-sm-12 mb-5">
        {{ Form::label('thana', __('messages.thana.thana_name').':', ['class' => 'form-label']) }}
        {{ Form::select('thana_id', $data['selected_city_thanas'] ?? [], old('thana_id'), ['id'=>'thanaId','class' => 'form-select','placeholder' => __('messages.company.select_thana'),'data-control'=>'select2']) }}
    </div>
    <div class="col-xl-3 col-md-6 col-sm-12 mb-5">
        {{ Form::label('city_village_name', __('messages.city_village.city_villages').':', ['class' => 'form-label']) }}
        {{ Form::text('city_village_name', old('city_village_name'), ['class' => 'form-control', 'placeholder' => 'Enter Area / City / Village']) }}
    </div>
    <div class="col-xl-9 col-md-6 col-sm-12 mb-5">
        {{ Form::label('address', __('messages.candidate.address').':', ['class' => 'form-label']) }}
        {{ Form::textarea('address', old('address'), ['id' => 'jobAddress', 'class' => 'form-control', 'rows' => 3, 'placeholder' => __('messages.candidate.address')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('career_level_id', __('messages.job.career_level').':', ['class' => 'form-label']) }}
        <div class="input-group flex-nowrap">
            {{ Form::select('career_level_id', $data['careerLevels'],null, ['id'=>'careerLevelsId','class' => 'form-select','data-control'=>'select2','placeholder' => __('messages.company.select_career_level')]) }}
            <div class="input-group-text border-0">
                <a href="javascript:void(0)" class="text-gray-500 createCareerLevelModal" title="{{ __('messages.common.add') }}" data-bs-toggle="tooltip">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('job_shift_id', __('messages.job.job_shift').':', ['class' => 'form-label']) }}
        {{ Form::select('job_shift_id', $data['jobShift'], null, ['id'=>'jobShiftId','class' => 'form-select','data-control'=>'select2','placeholder' => __('messages.company.select_job_shift')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('degree_level_id', __('messages.job.degree_level').':', ['class' => 'form-label']) }}
        {{ Form::select('degree_level_id', $data['requiredDegreeLevel'], null, ['id'=>'requiredDegreeLevelId','class' => 'form-select','data-control'=>'select2','placeholder' => __('messages.company.select_degree_level')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('degree_title_id', __('messages.candidate_profile.degree_title').':', ['class' => 'form-label']) }}
        {{ Form::select('degree_title_id', [], null, ['id' => 'jobDegreeTitleId', 'class' => 'form-select', 'data-control' => 'select2', 'placeholder' => __('messages.candidate_profile.exam_degree_title'), 'data-selected-value' => old('degree_title_id')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('functional_area_id', __('messages.job.functional_area').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div class="input-group flex-nowrap">
            {{ Form::select('functional_area_id', $data['functionalArea'], null, ['id'=>'functionalAreaId','class' => 'form-select','placeholder' => __('messages.company.select_functional_area'),'data-control'=>'select2','required']) }}
            <div class="input-group-text border-0">
                <a href="javascript:void(0)" class="text-gray-500 createFunctionalAreaModal" title="{{ __('messages.common.add') }}" data-bs-toggle="tooltip">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>
    </div>
    @include('jobs.experience_fields')
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('vacancy', __('messages.job.vacancy').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('vacancy',  null, ['id'=>'vacancyId','class' => 'form-control','required', 'min' => 1, 'max' => 4294967295, 'placeholder' => __('messages.job.vacancy'), 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")']) }}
    </div>
    <!-- Submit Field -->
    <div class="d-flex justify-content-end mt-5">
        <input name="saveAsDraft" type="hidden" value="" id="saveAsDraft">
        {{ Form::button(__('messages.common.save_as_draft'), ['type' => 'submit', 'name' => 'saveDraft', 'class' => 'btn btn-primary me-3 saveDraft','id' => 'saveDraft','value'=>'draft','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> ".__('messages.common.process')]) }}
        {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'name' => 'save', 'class' => 'btn btn-primary me-3','id' => 'jobsSaveBtn','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> ".__('messages.common.process')]) }}
        <a href="{{ route('job.index') }}"
           class="btn btn-secondary me-2">{{__('messages.common.cancel')}}</a>
    </div>

</div>

<script>window.jobDegreeTitleOptions = @json($data['educationDegreeTitleOptions'] ?? []);</script>

