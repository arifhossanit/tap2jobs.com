<div class="d-flex align-items-center gap-2 py-1">
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ route('jobType.import') }}"
            data-sample-file="{{ asset('sample-imports/job-types-sample.csv') }}?v={{ filemtime(public_path('sample-imports/job-types-sample.csv')) }}">
        Import
    </button>
    <a type="button" class="btn btn-primary addJobTypeModalButton">
        {{ __('messages.common.add') }}
    </a>
</div>
