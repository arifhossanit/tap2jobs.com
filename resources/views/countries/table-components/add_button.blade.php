<div class="d-flex align-items-center gap-2 py-1">
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ route('countries.import') }}"
            data-sample-file="{{ asset('sample-imports/countries-sample.csv') }}">
        Import
    </button>
    <a type="button" class="btn btn-primary addCountryModal">
        {{ __('messages.common.add') }}
    </a>
</div>
