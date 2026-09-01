<div class="d-flex align-items-center justify-content-center gap-2">
    @if($row->user->email_verified_at)
        <button type="button"
                title="Mark Unverified"
                class="badge bg-light-success text-success border-0 employer-email-status-toggle"
                data-id="{{ $row->id }}"
                data-action="unverify"
                data-bs-toggle="tooltip">
            <i class="fa-solid fa-circle-check me-1"></i> Verified
        </button>
    @else
        <button type="button"
                title="Mark Verified"
                class="badge bg-light-warning text-warning border-0 employer-email-status-toggle"
                data-id="{{ $row->id }}"
                data-action="verify"
                data-bs-toggle="tooltip">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> Unverified
        </button>
        <button type="button"
                title="{{ __('messages.common.resend_verification_mail') }}"
                class="btn btn-icon text-primary send-email-company-verification"
                data-id="{{ $row->id }}"
                data-bs-toggle="tooltip">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    @endif
</div>
