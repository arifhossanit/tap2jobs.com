<div class="d-flex align-items-center gap-2 py-1">
    {{-- Job Tag import intentionally hidden from the UI. --}}
    {{--
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ route('jobTag.import') }}"
            data-sample-file="{{ asset('sample-imports/job-tags-sample.csv') }}">
        Import
    </button>
    --}}
    <a type="button" class="btn btn-primary addJobTagModalButton">
        {{ __('messages.common.add') }}
    </a>
</div>
