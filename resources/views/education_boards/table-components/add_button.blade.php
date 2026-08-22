<div class="d-flex align-items-center gap-2 py-1">
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ route('educationBoards.import') }}"
            data-sample-file="{{ asset('sample-imports/education-boards-sample.csv') }}">
        Import
    </button>
    <a type="button" class="btn btn-primary addEducationBoardModal">
        {{ __('messages.common.add') }}
    </a>
</div>
