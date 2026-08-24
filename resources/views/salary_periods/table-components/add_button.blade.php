<div class="d-flex align-items-center gap-2 py-1">
    {{-- Salary Period import intentionally hidden from the UI. --}}
    {{--
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ route('salaryPeriod.import') }}"
            data-sample-file="{{ asset('sample-imports/salary-periods-sample.csv') }}">
        Import
    </button>
    --}}
    <a class="btn btn-primary addSalaryPeriodModal ms-auto">
        {{ __('messages.marital_status.add') }}
    </a>
</div>
