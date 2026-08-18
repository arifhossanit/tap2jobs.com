<div class="d-flex align-items-center justify-content-center gap-2">
    @php
        $canPreviewResume = $row->mime_type === 'application/pdf'
            || str_starts_with($row->mime_type, 'image/')
            || strtolower($row->extension) === 'docx';
        $resumeTitle = $row->getCustomProperty('title', $row->name);
    @endphp
    <button type="button"
            title="{{ __('messages.common.preview') }}"
            class="preview-resume candidate-resume-action-btn candidate-resume-action-btn--preview"
            data-url="{{ route('candidate.resumes.preview', $row->id) }}"
            data-title="{{ $resumeTitle }}"
            data-previewable="{{ $canPreviewResume ? '1' : '0' }}"
            data-bs-toggle="tooltip">
        <i class="fa-solid fa-eye"></i>
    </button>
    @if(! $row->getCustomProperty(\App\Services\ApplicationCvService::APPLICATION_CV_PROPERTY, false))
        <button type="button" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}"
                class="delete-resume candidate-resume-action-btn candidate-resume-action-btn--delete" data-bs-toggle="tooltip">
            <i class="fa-solid fa-trash"></i>
        </button>
    @endif
</div>
