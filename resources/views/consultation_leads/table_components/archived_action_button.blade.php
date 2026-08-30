<div class="d-flex justify-content-center">
    @if ($row->lead_from === \App\Models\ConsultationLead::LEAD_FROM_EMPLOYER && $row->employer_id)
        <a href="{{ route('company.show', $row->employer_id) }}"
           class="btn px-2 text-info fs-3"
           title="View Employer"
           data-bs-toggle="tooltip">
            <i class="fa-solid fa-building"></i>
        </a>
    @endif

    <button type="button"
            class="btn px-2 text-success fs-3 consultation-lead-restore-btn"
            data-id="{{ $row->id }}"
            title="Restore"
            data-bs-toggle="tooltip">
        <i class="fa-solid fa-rotate-left"></i>
    </button>

    <button type="button"
            class="btn px-2 text-danger fs-3 consultation-lead-force-delete-btn"
            data-id="{{ $row->id }}"
            title="Delete Permanently"
            data-bs-toggle="tooltip">
        <i class="fa-solid fa-trash"></i>
    </button>
</div>
