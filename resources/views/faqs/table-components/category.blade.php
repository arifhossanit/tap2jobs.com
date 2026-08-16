<div class="d-flex align-items-center justify-content-center">
    @if(isset($row->category))
        <span class="fw-bold me-2">{{ $row->category->localizedName() }}</span>
        @if($row->category->audience == 'employer')
            <span class="badge bg-light-primary text-primary">{{ __('messages.employer.employer') ?? 'Employer' }}</span>
        @else
            <span class="badge bg-light-info text-info">{{ __('messages.candidate.candidate') ?? 'Candidate' }}</span>
        @endif
    @else
        <span class="text-muted">N/A</span>
    @endif
</div>
