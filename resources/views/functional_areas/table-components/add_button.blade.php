<div class="d-flex align-items-center gap-2 py-1">
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ route('functionalArea.import') }}"
            data-sample-file="{{ asset('sample-imports/functional-areas-sample.csv') }}">
        Import
    </button>
    <a type="button" class="btn btn-primary addFunctionalAreaModal">
        {{ __('messages.common.add') }}
    </a>
</div>
