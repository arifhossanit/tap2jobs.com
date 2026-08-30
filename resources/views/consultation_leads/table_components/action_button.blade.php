<div class="d-flex justify-content-center">
    <button type="button"
            class="btn px-2 text-primary fs-3 consultation-lead-view-btn"
            data-id="{{ $row->id }}"
            title="{{ __('messages.common.view') }}"
            data-bs-toggle="tooltip">
        <i class="fa-solid fa-eye"></i>
    </button>
    <button type="button"
            class="btn px-2 text-danger fs-3 consultation-lead-delete-btn"
            data-id="{{ $row->id }}"
            title="{{ __('messages.common.delete') }}"
            data-bs-toggle="tooltip">
        <i class="fa-solid fa-trash"></i>
    </button>
</div>
