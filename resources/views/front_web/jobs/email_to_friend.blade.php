<div class="modal fade job-action-modal job-share-modal" id="emailJobToFriendModal" tabindex="-1"
     aria-labelledby="jobShareModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jobShareModalTitle">@lang('messages.front_job_details.share_this_job')</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="job-share-modal__icons">
                    <a href="{{ $url['facebook'] }}" target="_blank" rel="noopener noreferrer"
                       onclick="if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return true; var popup = window.open('about:blank', '_blank', 'width=800,height=600'); if (!popup) return true; popup.opener = null; popup.location.replace(this.href); return false;"
                       class="social-icon facebook" title="@lang('messages.front_job_details.facebook')" aria-label="@lang('messages.front_job_details.facebook')">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="{{ $url['linkedin'] }}" target="_blank" rel="noopener noreferrer"
                       class="social-icon linkedin" title="@lang('messages.front_job_details.linkedin')" aria-label="@lang('messages.front_job_details.linkedin')">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>
                    <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer"
                       data-share-title="{{ $share['title'] }}" data-share-description="{{ $share['description'] }}" data-share-url="{{ $share['url'] }}"
                       onclick="if (navigator.clipboard) navigator.clipboard.writeText(this.dataset.shareTitle + String.fromCharCode(10) + this.dataset.shareDescription + String.fromCharCode(10) + this.dataset.shareUrl);"
                       class="social-icon instagram" title="Share via Instagram" aria-label="Share via Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="{{ $url['whatsapp'] }}" target="_blank" rel="noopener noreferrer"
                       class="social-icon whatsapp" title="WhatsApp" aria-label="WhatsApp">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                    <a href="{{ $url['gmail'] }}" target="_blank" rel="noopener noreferrer"
                       class="social-icon gmail" title="Share via Gmail" aria-label="Share via Gmail">
                        <i class="fa-brands fa-google"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
