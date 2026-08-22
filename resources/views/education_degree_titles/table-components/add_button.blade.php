<div class="d-flex align-items-center gap-2 py-1">
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ route('educationDegreeTitles.import') }}"
            data-sample-file="{{ asset('sample-imports/education-degree-titles-sample.csv') }}">
        Import
    </button>
    <a type="button" class="btn btn-primary addEducationDegreeTitleModal">
        {{ __('messages.common.add') }}
    </a>
</div>
