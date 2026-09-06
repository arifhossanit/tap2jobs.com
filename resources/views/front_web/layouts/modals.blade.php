<script>
    window.closeTap2JobsModal = function () {
        var activeModal = document.querySelector('.tap2jobs-system-modal');
        if (!activeModal) {
            return;
        }
        if (window.tap2JobsModalEscapeHandler) {
            document.removeEventListener('keydown', window.tap2JobsModalEscapeHandler);
        }

        activeModal.classList.remove('is-visible');
        setTimeout(function () {
            if (activeModal && activeModal.parentNode) {
                activeModal.parentNode.removeChild(activeModal);
            }
        }, 160);
    };

    window.showTap2JobsModal = function (htmlContent, options) {
        options = options || {};
        window.closeTap2JobsModal();
        if (window.tap2JobsModalEscapeHandler) {
            document.removeEventListener('keydown', window.tap2JobsModalEscapeHandler);
        }

        var modal = document.createElement('div');
        modal.className = 'tap2jobs-system-modal';
        modal.innerHTML = `
            <div class="tap2jobs-system-modal__backdrop"></div>
            <div class="tap2jobs-system-modal__dialog" role="dialog" aria-modal="true">
                <div class="tap2jobs-system-modal__content"></div>
                <div class="tap2jobs-system-modal__actions"></div>
            </div>
        `;

        modal.querySelector('.tap2jobs-system-modal__content').innerHTML = htmlContent;
        var actions = modal.querySelector('.tap2jobs-system-modal__actions');

        if (options.confirmText !== false) {
            var confirmButton = document.createElement('button');
            confirmButton.type = 'button';
            confirmButton.className = 'tap2jobs-system-modal__button';
            if (options.buttonClass) {
                confirmButton.className += ' ' + options.buttonClass;
            }
            confirmButton.textContent = options.confirmText || 'OK';
            confirmButton.addEventListener('click', function () {
                window.closeTap2JobsModal();
                if (typeof options.onConfirm === 'function') {
                    options.onConfirm();
                }
            });
            actions.appendChild(confirmButton);
        } else {
            actions.remove();
        }

        if (options.allowBackdropClose !== false) {
            modal.querySelector('.tap2jobs-system-modal__backdrop').addEventListener('click', window.closeTap2JobsModal);
        }

        window.tap2JobsModalEscapeHandler = function (event) {
            if (event.key === 'Escape') {
                window.closeTap2JobsModal();
                document.removeEventListener('keydown', window.tap2JobsModalEscapeHandler);
            }
        };
        document.addEventListener('keydown', window.tap2JobsModalEscapeHandler);

        document.body.appendChild(modal);
        setTimeout(function () {
            modal.classList.add('is-visible');
        }, 10);
    };

    window.showProfileIncompleteModal = function (percentage, profileUrl, retryCount) {
        percentage = parseInt(percentage) || 0;
        var isBn = (typeof lancode !== 'undefined' && lancode === 'bn');
        var minimumApplicationPercentage = {{ \App\Services\CandidateProfileCompletionService::MINIMUM_APPLICATION_PERCENTAGE }};
        var isComplete = percentage >= minimumApplicationPercentage;

        var titleText = isComplete 
            ? (isBn ? "প্রোফাইল সম্পূর্ণ!" : "Profile Complete!")
            : (isBn ? "প্রোফাইল অসম্পূর্ণ!" : "Profile Incomplete!");

        var descText = isComplete
            ? (isBn 
                ? "আপনার প্রোফাইল <b>" + percentage + "%</b> সম্পূর্ণ হয়েছে। এখন আপনি চাকরিতে আবেদন করতে পারবেন।" 
                : "Your profile is <b>" + percentage + "%</b> complete. Now you can apply for job.")
            : (isBn 
                ? "চাকরিতে আবেদন করতে আপনার প্রোফাইল অন্তত <b>" + minimumApplicationPercentage + "%</b> সম্পূর্ণ করতে হবে।"
                : "Your profile must be at least <b>" + minimumApplicationPercentage + "%</b> complete to apply for jobs.");

        var confirmBtnText = isComplete
            ? (isBn ? "চাকরি খুঁজুন" : "Browse Jobs")
            : "OK";

        var radius = 40;
        var circumference = 2 * Math.PI * radius;
        var dashoffset = circumference - (circumference * Math.min(percentage, 100) / 100);

        var gradientId = isComplete ? "progressGradientGreen" : "progressGradientPink";
        var strokeColor1 = isComplete ? "#10b981" : "#ec4899";
        var strokeColor2 = isComplete ? "#059669" : "#be185d";
        var bgCircleColor = isComplete ? "#d1fae5" : "#fce7f3";
        var shadowColor = isComplete ? "rgba(16, 185, 129, 0.25)" : "rgba(217, 70, 239, 0.25)";

        var htmlContent = `
            <div style="font-family: inherit; padding: 10px 0 5px 0; text-align: center;">
                <div style="position: relative; width: 110px; height: 110px; margin: 0 auto 20px auto; display: flex; align-items: center; justify-content: center;">
                    <svg width="110" height="110" viewBox="0 0 100 100" style="transform: rotate(-90deg); filter: drop-shadow(0px 4px 12px ${shadowColor});">
                        <circle cx="50" cy="50" r="40" stroke="${bgCircleColor}" stroke-width="9" fill="transparent" />
                        <circle cx="50" cy="50" r="40" stroke="url(#${gradientId})" stroke-width="9" stroke-linecap="round" fill="transparent"
                            stroke-dasharray="${circumference}" stroke-dashoffset="${dashoffset}"
                            style="transition: stroke-dashoffset 0.8s ease-in-out;" />
                        <defs>
                            <linearGradient id="${gradientId}" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="${strokeColor1}" />
                                <stop offset="100%" stop-color="${strokeColor2}" />
                            </linearGradient>
                        </defs>
                    </svg>
                    <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;">
                        <span style="font-size: 24px; font-weight: 800; color: #0f172a; font-family: inherit;">${percentage}%</span>
                    </div>
                </div>
                <h3 style="font-size: 22px; font-weight: 700; color: #1e293b; margin: 0 0 12px 0;">${titleText}</h3>
                <p style="font-size: 15px; color: #475569; line-height: 1.5; margin: 0 0 8px 0;">${descText}</p>
            </div>
        `;

        var jobsUrl = "{{ route('front.search.jobs') }}";

        window.showTap2JobsModal(htmlContent, {
            confirmText: confirmBtnText,
            buttonClass: isComplete ? 'tap2jobs-system-modal__button--success' : '',
            onConfirm: function () {
                if (isComplete) {
                    window.location.href = jobsUrl;
                }
            }
        });
    };

    window.handleApplyClick = function (e, applyUrl, percentage, profileUrl) {
        if (percentage < {{ \App\Services\CandidateProfileCompletionService::MINIMUM_APPLICATION_PERCENTAGE }}) {
            if (e && e.preventDefault) {
                e.preventDefault();
            }
            window.showProfileIncompleteModal(percentage, profileUrl);
            return false;
        }
        window.location.href = applyUrl;
        return true;
    };

    window.showCandidateRegistrationSuccessModal = function (retryCount) {
        var titleText = "অভিনন্দন! Registration সফল হয়েছে।";
        var descText = "আপনার Profile- যত বেশি তথ্য পূরণ করবেন, আপনার জন্য তত বেশি ও নির্ভুল Job Matching পাওয়ার সম্ভাবনা বাড়বে। আপনার Profile সম্পূর্ণ করুন এবং আরও বেশি চাকরির সুযোগ পান।";

        var htmlContent = `
            <div style="font-family: inherit; padding: 14px 0 8px 0; text-align: center;">
                <div style="width: 74px; height: 74px; margin: 0 auto 18px auto; border-radius: 50%; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 32px;">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h3 style="font-size: 22px; font-weight: 700; color: #1e293b; margin: 0 0 14px 0;">${titleText}</h3>
                <p style="font-size: 15px; color: #475569; line-height: 1.7; margin: 0;">${descText}</p>
            </div>
        `;

        window.showTap2JobsModal(htmlContent, {
            confirmText: false
        });
    };

    window.showNotEligibleModal = function () {
        var isBn = (typeof lancode !== 'undefined' && lancode === 'bn');
        var titleText = isBn ? "আপনি জব পোস্ট করতে পারবেন না" : "You Are Not Eligible for this Service";
        var descText = isBn 
            ? "জব পোস্টিং ফিচারটি শুধুমাত্র <b style=\"font-weight: 700; color: #1e293b;\">Employer (এমপ্লয়ার)</b> অ্যাকাউন্টের জন্য নির্ধারিত।" 
            : "Job posting is available for <b style=\"font-weight: 700; color: #1e293b;\">Employer Accounts</b> only.";
        var confirmBtnText = isBn ? "ঠিক আছে" : "OK";

        var htmlContent = `
            <div style="font-family: inherit; text-align: center; padding: 12px 8px 4px 8px;">
                <div style="width: 64px; height: 64px; margin: 0 auto 16px auto; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 26px;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h4 style="font-family: inherit; font-size: 20px; font-weight: 700; color: #1e293b; margin: 0 0 10px 0;">${titleText}</h4>
                <p style="font-family: inherit; font-size: 14.5px; color: #64748b; line-height: 1.6; margin: 0;">${descText}</p>
            </div>
        `;
        var modalContent = document.createElement('div');
        modalContent.innerHTML = htmlContent;

        if (typeof Swal !== 'undefined' && typeof Swal.fire === 'function') {
            Swal.fire({
                html: htmlContent,
                showCancelButton: false,
                confirmButtonText: confirmBtnText,
                confirmButtonColor: '#209776',
                customClass: {
                    popup: 'not-eligible-swal-popup',
                    confirmButton: 'not-eligible-confirm-btn'
                }
            });
        } else if (typeof swal === 'function') {
            swal({
                title: "",
                content: modalContent,
                buttons: {
                    confirm: confirmBtnText
                },
                icon: false
            });
        } else {
            alert(titleText + "\n\n" + descText.replace(/<\/?[^>]+(>|$)/g, ""));
        }
    };
</script>

<style>
    .tap2jobs-system-modal {
        align-items: center;
        display: flex;
        inset: 0;
        justify-content: center;
        opacity: 0;
        padding: 20px;
        pointer-events: none;
        position: fixed;
        transition: opacity 160ms ease;
        z-index: 200000;
    }
    .tap2jobs-system-modal.is-visible {
        opacity: 1;
        pointer-events: auto;
    }
    .tap2jobs-system-modal__backdrop {
        background: rgba(15, 23, 42, 0.45);
        inset: 0;
        position: absolute;
    }
    .tap2jobs-system-modal__dialog {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.24);
        max-width: 430px;
        padding: 28px 28px 24px;
        position: relative;
        transform: translateY(10px) scale(0.98);
        transition: transform 160ms ease;
        width: min(100%, 430px);
    }
    .tap2jobs-system-modal.is-visible .tap2jobs-system-modal__dialog {
        transform: translateY(0) scale(1);
    }
    .tap2jobs-system-modal__content h3,
    .tap2jobs-system-modal__content p {
        font-family: inherit;
    }
    .tap2jobs-system-modal__actions {
        display: flex;
        justify-content: center;
        margin-top: 22px;
    }
    .tap2jobs-system-modal__button {
        background: #7cccf0;
        border: 0;
        border-radius: 4px;
        color: #ffffff;
        cursor: pointer;
        font-family: inherit;
        font-size: 16px;
        font-weight: 500;
        line-height: 1;
        min-width: 88px;
        padding: 15px 30px;
        transition: background-color 160ms ease, transform 160ms ease;
    }
    .tap2jobs-system-modal__button:hover,
    .tap2jobs-system-modal__button:focus {
        background: #5bbce8;
        color: #ffffff;
        outline: none;
        transform: translateY(-1px);
    }
    .tap2jobs-system-modal__button--success {
        background: #209776;
    }
    .tap2jobs-system-modal__button--success:hover,
    .tap2jobs-system-modal__button--success:focus {
        background: #187f63;
    }
    @media (max-width: 575.98px) {
        .tap2jobs-system-modal {
            padding: 14px;
        }
        .tap2jobs-system-modal__dialog {
            padding: 24px 20px 22px;
        }
        .tap2jobs-system-modal__button {
            min-width: 82px;
            padding: 14px 24px;
        }
    }

    .not-eligible-swal-popup {
        font-family: inherit !important;
        border-radius: 16px !important;
        padding: 24px 20px !important;
        max-width: 400px !important;
    }
    .not-eligible-confirm-btn {
        font-family: inherit !important;
        border-radius: 10px !important;
        padding: 10px 32px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
    }
</style>

@if (session()->has('profile_incomplete'))
    @php $profileData = session()->get('profile_incomplete'); @endphp
    <script>
        (function () {
            function triggerIncompleteModal() {
                var percentage = "{{ $profileData['percentage'] ?? 0 }}";
                var profileUrl = "{{ $profileData['profile_url'] ?? route('candidate.profile') }}";
                if (typeof window.showProfileIncompleteModal === 'function') {
                    window.profileIncompleteModalTriggered = true;
                    window.showProfileIncompleteModal(percentage, profileUrl);
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', triggerIncompleteModal);
            } else {
                triggerIncompleteModal();
            }
        })();
    </script>
@endif

<script>
    (function () {
        function triggerStoredProfileIncompleteModal() {
            if (!window.sessionStorage || typeof window.showProfileIncompleteModal !== 'function') {
                return;
            }

            var storedProfileIncomplete = window.sessionStorage.getItem('pendingProfileIncompleteModal');
            if (!storedProfileIncomplete) {
                return;
            }

            window.sessionStorage.removeItem('pendingProfileIncompleteModal');

            if (window.profileIncompleteModalTriggered) {
                return;
            }

            try {
                var profileData = JSON.parse(storedProfileIncomplete);
                window.showProfileIncompleteModal(profileData.percentage || 0, profileData.profile_url || "{{ route('candidate.profile') }}");
            } catch (error) {
                window.showProfileIncompleteModal(0, "{{ route('candidate.profile') }}");
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', triggerStoredProfileIncompleteModal);
        } else {
            triggerStoredProfileIncompleteModal();
        }
    })();
</script>

@if (session()->has('candidate_registration_success'))
    <script>
        (function () {
            function triggerCandidateRegistrationSuccessModal() {
                if (typeof window.showCandidateRegistrationSuccessModal === 'function') {
                    window.showCandidateRegistrationSuccessModal();
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', triggerCandidateRegistrationSuccessModal);
            } else {
                triggerCandidateRegistrationSuccessModal();
            }
        })();
    </script>
@endif

@if (session()->has('not_eligible_error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof window.showNotEligibleModal === 'function') {
                window.showNotEligibleModal();
            }
        });
    </script>
@endif
