<div class="candidate-employment-form-grid">
    @php
        $expertiseRows = $experience && $experience->relationLoaded('expertises') ? $experience->expertises : collect();
        if ($expertiseRows->isEmpty()) {
            $expertiseRows = collect([null]);
        }
    @endphp
    <div class="candidate-education-form-field">
        {{ Form::label('company', __('messages.candidate_profile.company_name'), ['class' => 'form-label required']) }}
        {{ Form::text('company', $experience->company ?? null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_company_name')]) }}
    </div>
    <div class="candidate-education-form-field">
        {{ Form::label('company_business', __('messages.candidate_profile.company_business'), ['class' => 'form-label required']) }}
        {{ Form::text('company_business', $experience->company_business ?? null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_company_business')]) }}
    </div>
    <div class="candidate-education-form-field">
        {{ Form::label('experience_title', __('messages.candidate_profile.designation'), ['class' => 'form-label required']) }}
        {{ Form::text('experience_title', $experience->experience_title ?? null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.enter_designation')]) }}
    </div>
    <div class="candidate-education-form-field">
        {{ Form::label('department', __('messages.candidate_profile.department'), ['class' => 'form-label']) }}
        {{ Form::text('department', $experience->department ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_department')]) }}
    </div>
    <div class="candidate-education-form-field">
        {{ Form::label('start_date', __('messages.candidate_profile.employment_period_start_date'), ['class' => 'form-label required']) }}
        <div class="candidate-employment-date-input">
            <i class="fa-regular fa-calendar candidate-employment-date-icon"></i>
            {{ Form::text('start_date', $startDate, ['class' => 'form-control', 'required', 'placeholder' => __('messages.candidate_profile.employment_period_start_date'), 'autocomplete' => 'off', 'data-employment-date' => 'start']) }}
        </div>
    </div>
    <div class="candidate-education-form-field">
        {{ Form::label('end_date', __('messages.candidate_profile.employment_period_end_date'), ['class' => 'form-label']) }}
        <div class="candidate-employment-date-input">
            <i class="fa-regular fa-calendar candidate-employment-date-icon"></i>
            {{ Form::text('end_date', $endDate, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.employment_period_end_date'), 'autocomplete' => 'off', 'data-employment-date' => 'end', 'data-employment-end-date' => true]) }}
        </div>
        <label class="candidate-employment-working-check">
            {{ Form::checkbox('currently_working', '1', $isWorking, ['class' => 'form-check-input', 'data-employment-working' => true]) }}
            <span>{{ __('messages.candidate_profile.currently_working') }}</span>
        </label>
    </div>
    <div class="candidate-education-form-field candidate-education-form-field--full">
        {{ Form::label('description', __('messages.candidate_profile.responsibility'), ['class' => 'form-label']) }}
        <div class="candidate-education-editor">
            {{ Form::textarea('description', $experience->description ?? null, ['class' => 'd-none', 'data-employment-quill-input' => true]) }}
            <div class="candidate-employment-quill" data-employment-quill-editor
                 data-placeholder="{{ __('messages.candidate_profile.enter_responsibility') }}"></div>
        </div>
    </div>
    <div class="candidate-education-form-field candidate-education-form-field--full">
        {{ Form::label('company_location', __('messages.candidate_profile.company_location'), ['class' => 'form-label']) }}
        {{ Form::text('company_location', $experience->company_location ?? null, ['class' => 'form-control', 'placeholder' => __('messages.candidate_profile.enter_company_location')]) }}
    </div>
    <div class="candidate-education-form-field candidate-education-form-field--full">
        {{ Form::label('area_of_expertise', __('messages.candidate_profile.area_of_expertise'), ['class' => 'form-label required']) }}
        <p class="candidate-employment-field-help">{{ __('messages.candidate_profile.add_area_of_expertise_help') }}</p>
        @foreach ($expertiseRows as $expertise)
            <div class="candidate-employment-expertise-row" data-employment-expertise-row>
                {{ Form::text('area_of_expertise[]', $expertise->name ?? null, ['class' => 'form-control candidate-employment-expertise-name']) }}
                <div class="candidate-employment-month-field">
                    {{ Form::number('expertise_duration[]', $expertise->duration_months ?? null, ['class' => 'form-control', 'min' => 0, 'data-employment-expertise-duration' => true]) }}
                    <span>{{ __('messages.candidate_profile.month_s') }}</span>
                </div>
                <button type="button" class="candidate-employment-remove-expertise" aria-label="{{ __('messages.candidate_profile.remove_expertise') }}" data-employment-expertise-remove>
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            </div>
        @endforeach
        <button type="button" class="candidate-employment-add-new" data-employment-expertise-add>
            <i class="fa-solid fa-plus"></i>
            <span>{{ __('messages.candidate_profile.add_new') }}</span>
        </button>
    </div>
</div>
<div class="candidate-profile-section-actions candidate-employment-form-actions">
    {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'candidate-skill-save']) }}
    <button type="button" class="candidate-skill-close" data-employment-form-close>
        {{ __('messages.common.close') }}
    </button>
</div>
