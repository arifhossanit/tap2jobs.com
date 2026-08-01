<div class="candidate-employment-form-grid">
    <div class="candidate-education-form-field">
        {{ Form::label('company', 'Company Name', ['class' => 'form-label required']) }}
        {{ Form::text('company', $experience->company ?? null, ['class' => 'form-control', 'required', 'placeholder' => 'Enter Company Name']) }}
    </div>
    <div class="candidate-education-form-field">
        {{ Form::label('company_business', 'Company Business', ['class' => 'form-label required']) }}
        {{ Form::text('company_business', null, ['class' => 'form-control', 'placeholder' => 'Enter Company Business']) }}
    </div>
    <div class="candidate-education-form-field">
        {{ Form::label('experience_title', 'Designation', ['class' => 'form-label required']) }}
        {{ Form::text('experience_title', $experience->experience_title ?? null, ['class' => 'form-control', 'required', 'placeholder' => 'Enter Designation']) }}
    </div>
    <div class="candidate-education-form-field">
        {{ Form::label('department', 'Department', ['class' => 'form-label']) }}
        {{ Form::text('department', null, ['class' => 'form-control', 'placeholder' => 'Enter Department']) }}
    </div>
    <div class="candidate-education-form-field">
        {{ Form::label('start_date', 'Employment Period Start Date', ['class' => 'form-label required']) }}
        <div class="candidate-employment-date-input">
            <i class="fa-regular fa-calendar candidate-employment-date-icon"></i>
            {{ Form::text('start_date', $startDate, ['class' => 'form-control', 'required', 'placeholder' => 'Employment Period Start Date', 'autocomplete' => 'off', 'data-employment-date' => 'start']) }}
        </div>
    </div>
    <div class="candidate-education-form-field">
        {{ Form::label('end_date', 'Employment Period End Date', ['class' => 'form-label']) }}
        <div class="candidate-employment-date-input">
            <i class="fa-regular fa-calendar candidate-employment-date-icon"></i>
            {{ Form::text('end_date', $endDate, ['class' => 'form-control', 'placeholder' => 'Employment Period End Date', 'autocomplete' => 'off', 'data-employment-date' => 'end', 'data-employment-end-date' => true]) }}
        </div>
        <label class="candidate-employment-working-check">
            {{ Form::checkbox('currently_working', '1', $isWorking, ['class' => 'form-check-input', 'data-employment-working' => true]) }}
            <span>{{ __('messages.candidate_profile.currently_working') }}</span>
        </label>
    </div>
    <div class="candidate-education-form-field candidate-education-form-field--full">
        {{ Form::label('description', 'Responsibility', ['class' => 'form-label']) }}
        <div class="candidate-education-editor">
            {{ Form::textarea('description', $experience->description ?? null, ['class' => 'd-none', 'data-employment-quill-input' => true]) }}
            <div class="candidate-employment-quill" data-employment-quill-editor
                 data-placeholder="Enter Responsibility"></div>
        </div>
    </div>
    <div class="candidate-education-form-field candidate-education-form-field--full">
        {{ Form::label('company_location', 'Company Location', ['class' => 'form-label']) }}
        {{ Form::text('company_location', null, ['class' => 'form-control', 'placeholder' => 'Enter Company location']) }}
    </div>
    <div class="candidate-education-form-field candidate-education-form-field--full">
        {{ Form::label('area_of_expertise', 'Area of Expertise', ['class' => 'form-label required']) }}
        <p class="candidate-employment-field-help">Add your area of expertise with duration (max 10)</p>
        <div class="candidate-employment-expertise-row" data-employment-expertise-row>
            {{ Form::text('area_of_expertise[]', null, ['class' => 'form-control candidate-employment-expertise-name']) }}
            <div class="candidate-employment-month-field">
                {{ Form::number('expertise_duration[]', null, ['class' => 'form-control', 'min' => 0, 'max' => 12, 'data-employment-expertise-duration' => true]) }}
                <span>Month(s)</span>
            </div>
            <button type="button" class="candidate-employment-remove-expertise" aria-label="Remove expertise" data-employment-expertise-remove>
                <i class="fa-regular fa-trash-can"></i>
            </button>
        </div>
        <button type="button" class="candidate-employment-add-new" data-employment-expertise-add>
            <i class="fa-solid fa-plus"></i>
            <span>Add New</span>
        </button>
    </div>
</div>
<div class="candidate-profile-section-actions candidate-employment-form-actions">
    {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
    <button type="button" class="btn btn-secondary" data-employment-form-close>
        {{ __('messages.common.close') }}
    </button>
</div>
