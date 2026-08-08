<div class="d-flex justify-content-center">
    @php
        $canPreviewResume = $row->mime_type === 'application/pdf'
            || str_starts_with($row->mime_type, 'image/')
            || strtolower($row->extension) === 'docx';
        $resumeTitle = $row->getCustomProperty('title', $row->name);
    @endphp
    <button type="button"
            title="{{ __('messages.common.preview') }}"
            class="preview-resume btn px-2 text-primary fs-3 {{ checkLanguageSession() == 'ar' ? 'pe-0' : 'ps-0' }}"
            data-url="{{ route('candidate.resumes.preview', $row->id) }}"
            data-title="{{ $resumeTitle }}"
            data-previewable="{{ $canPreviewResume ? '1' : '0' }}"
            data-bs-toggle="tooltip">
        <i class="fa-solid fa-eye"></i>
    </button>
    @if(! $row->getCustomProperty(\App\Services\ApplicationCvService::APPLICATION_CV_PROPERTY, false))
        <button type="button" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}"
                class="delete-resume btn px-2 text-danger fs-3 pe-0" data-bs-toggle="tooltip">
            <i class="fa-solid fa-trash"></i>
        </button>
    @endif
</div>
