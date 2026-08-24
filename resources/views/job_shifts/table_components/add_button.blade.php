<div class="d-flex align-items-center gap-2 py-1">
    {{-- Job Shift import intentionally hidden from the UI. --}}
    {{--
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ route('jobShift.import') }}"
            data-sample-file="{{ asset('sample-imports/job-shifts-sample.csv') }}">
        Import
    </button>
    --}}
    <a type="button" class="btn btn-primary addJobShiftButton">
        {{ __('messages.common.add') }}
    </a>
</div>
