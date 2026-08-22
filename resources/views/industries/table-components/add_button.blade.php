<div class="d-flex align-items-center gap-2 py-1">
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ route('industry.import') }}"
            data-sample-file="{{ asset('sample-imports/industries-sample.csv') }}">
        Import
    </button>
    <a type="button" class="btn btn-primary addIndustryModal">
        {{ __('messages.industry.add') }}
    </a>
</div>
