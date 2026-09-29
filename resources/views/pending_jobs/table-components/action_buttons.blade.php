<div class="d-flex justify-content-center align-items-center gap-2">
    <a href="{{ route('admin.jobs.show', $row->id) }}" title="{{ __('messages.common.view') }}"
       class="btn btn-sm btn-icon rounded-circle border border-2 border-info text-info d-inline-flex align-items-center justify-content-center p-0"
       style="width: 34px; height: 34px;" data-bs-toggle="tooltip">
        <i class="fa-solid fa-eye"></i>
    </a>
    <button type="button" title="{{__('messages.pending_jobs.accepted') }}" data-id="{{ $row->id }}" data-status="{{ \App\Models\Job::STATUS_OPEN }}"
        class="live-btn btn btn-sm btn-icon rounded-circle border border-2 border-primary text-primary d-inline-flex align-items-center justify-content-center p-0"
        style="width: 34px; height: 34px;" data-bs-toggle="tooltip">
        <i class="fa-solid fa-check fs-6 fw-bold"></i>
    </button>
    <button type="button" title="{{__('messages.pending_jobs.reject') }}" data-id="{{ $row->id }}" data-status="{{ \App\Models\Job::STATUS_SUSPENDED }}"
        class="suspend-btn btn btn-sm btn-icon rounded-circle border border-2 border-danger text-danger d-inline-flex align-items-center justify-content-center p-0"
        style="width: 34px; height: 34px;" data-bs-toggle="tooltip">
        <i class="fa-solid fa-xmark fs-6 fw-bold"></i>
    </button>
</div>
