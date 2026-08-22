<div class="modal fade" id="candidateResumePreviewModal" tabindex="-1"
     aria-labelledby="candidateResumePreviewTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content candidate-resume-preview-modal">
            {{-- <div class="modal-header">
                <h3 class="modal-title" id="candidateResumePreviewTitle">
                    {{ __('messages.common.preview') }}
                </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('messages.common.close') }}"></button>
            </div> --}}
            <div class="modal-body p-0 position-relative">
                <div class="candidate-resume-preview-loading d-flex align-items-center justify-content-center">
                    <span class="spinner-border text-primary" role="status" aria-hidden="true"></span>
                </div>
                <iframe id="candidateResumePreviewFrame"
                        class="candidate-resume-preview-frame d-none"
                        title="{{ __('messages.common.preview') }}"></iframe>
                <div class="candidate-resume-preview-unavailable d-none text-center p-10">
                    <i class="fa-regular fa-file-lines text-muted fs-3x mb-4"></i>
                    <p class="mb-0 text-muted">{{ __('messages.candidate_profile.resume_preview_unavailable') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
