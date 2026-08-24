<div class="d-flex align-items-center gap-2 py-1">
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ route('languages.import') }}"
            data-sample-file="{{ asset('sample-imports/languages-sample.csv') }}?v={{ filemtime(public_path('sample-imports/languages-sample.csv')) }}">
        Import
    </button>
    <a class="btn btn-primary addLanguageModal">
        {{ __('messages.job_category.add') }}
    </a>
</div>
