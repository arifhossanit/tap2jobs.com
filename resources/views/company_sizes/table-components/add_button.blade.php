<div class="d-flex align-items-center gap-2 py-1">
    {{-- Company Size import intentionally hidden from the UI. --}}
    {{--
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ route('companySize.import') }}"
            data-sample-file="{{ asset('sample-imports/company-sizes-sample.csv') }}">
        Import
    </button>
    --}}
    <a class="btn btn-primary addCompanySizeModal">
        {{ __('messages.marital_status.add') }}
    </a>
</div>
