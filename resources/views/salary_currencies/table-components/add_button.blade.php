<div class="d-flex align-items-center gap-2 py-1">
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ route('salaryCurrency.import') }}"
            data-sample-file="{{ asset('sample-imports/salary-currencies-sample.csv') }}">
        Import
    </button>
    <a type="button" class="btn btn-primary addCurrency">
        {{ __('messages.marital_status.add') }}
    </a>
</div>
