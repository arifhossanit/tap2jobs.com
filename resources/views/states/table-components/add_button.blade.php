<div class="d-flex align-items-center gap-2 py-1">
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ route('states.import') }}"
            data-sample-file="{{ asset('sample-imports/states-sample.csv') }}">
        Import
    </button>
    <a type="button" class="btn btn-primary form-btn addStateModal">
        {{ __('messages.common.add') }}
    </a>
</div>
