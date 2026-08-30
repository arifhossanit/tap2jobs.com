<script>
    window.showProfileIncompleteModal = function (percentage, profileUrl, retryCount) {
        retryCount = retryCount || 0;
        if ((typeof Swal === 'undefined' || typeof Swal.fire !== 'function') && typeof swal !== 'function' && retryCount < 5) {
            setTimeout(function() {
                window.showProfileIncompleteModal(percentage, profileUrl, retryCount + 1);
            }, 150);
            return;
        }

        percentage = parseInt(percentage) || 0;
        var isBn = (typeof lancode !== 'undefined' && lancode === 'bn');
        var isComplete = percentage >= 80;

        var titleText = isComplete 
            ? (isBn ? "প্রোফাইল সম্পূর্ণ!" : "Profile Complete!")
            : (isBn ? "প্রোফাইল অসম্পূর্ণ!" : "Profile Incomplete!");

        var descText = isComplete
            ? (isBn 
                ? "আপনার প্রোফাইল <b>" + percentage + "%</b> সম্পূর্ণ হয়েছে। এখন আপনি চাকরিতে আবেদন করতে পারবেন।" 
                : "Your profile is <b>" + percentage + "%</b> complete. Now you can apply for job.")
            : (isBn 
                ? "চাকরিতে আবেদন করতে আপনার প্রোফাইল অন্তত <b>৮০%</b> সম্পূর্ণ করতে হবে।" 
                : "Your profile must be at least <b>80%</b> complete to apply for jobs.");

        var confirmBtnText = isComplete
            ? (isBn ? "চাকরি খুঁজুন" : "Browse Jobs")
            : (isBn ? "প্রোফাইলে যান" : "Go to Profile");

        var cancelBtnText = isBn ? "বাতিল" : "Cancel";

        var radius = 40;
        var circumference = 2 * Math.PI * radius;
        var dashoffset = circumference - (circumference * Math.min(percentage, 100) / 100);

        var gradientId = isComplete ? "progressGradientGreen" : "progressGradientPink";
        var strokeColor1 = isComplete ? "#10b981" : "#ec4899";
        var strokeColor2 = isComplete ? "#059669" : "#be185d";
        var bgCircleColor = isComplete ? "#d1fae5" : "#fce7f3";
        var shadowColor = isComplete ? "rgba(16, 185, 129, 0.25)" : "rgba(217, 70, 239, 0.25)";
        var confirmBtnColor = isComplete ? "#209776" : "#6366f1";

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

        if (typeof Swal !== 'undefined' && typeof Swal.fire === 'function') {
            Swal.fire({
                html: htmlContent,
                showCancelButton: !isComplete,
                confirmButtonText: confirmBtnText,
                cancelButtonText: cancelBtnText,
                confirmButtonColor: confirmBtnColor,
                cancelButtonColor: '#94a3b8',
                customClass: {
                    popup: 'profile-incomplete-swal-popup',
                    confirmButton: 'profile-incomplete-confirm-btn',
                    cancelButton: 'profile-incomplete-cancel-btn'
                }
            }).then(function (result) {
                if (result.isConfirmed || result.value) {
                    if (isComplete) {
                        window.location.href = jobsUrl;
                    } else if (profileUrl) {
                        window.location.href = profileUrl;
                    }
                }
            });
        } else if (typeof swal === 'function') {
            swal({
                title: "",
                text: htmlContent,
                html: true,
                showCancelButton: !isComplete,
                confirmButtonColor: confirmBtnColor,
                confirmButtonText: confirmBtnText,
                cancelButtonText: cancelBtnText,
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm || isConfirm === true) {
                    if (isComplete) {
                        window.location.href = jobsUrl;
                    } else if (profileUrl) {
                        window.location.href = profileUrl;
                    }
                }
            });
        } else {
            if (confirm(titleText + "\n\n" + descText.replace(/<\/?[^>]+(>|$)/g, "") + "\n\n" + confirmBtnText)) {
                if (isComplete) {
                    window.location.href = jobsUrl;
                } else if (profileUrl) {
                    window.location.href = profileUrl;
                }
            }
        }
    };

    window.handleApplyClick = function (e, applyUrl, percentage, profileUrl) {
        if (percentage < 80) {
            if (e && e.preventDefault) {
                e.preventDefault();
            }
            window.showProfileIncompleteModal(percentage, profileUrl);
            return false;
        }
        window.location.href = applyUrl;
        return true;
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
                text: htmlContent,
                html: true,
                confirmButtonColor: '#209776',
                confirmButtonText: confirmBtnText,
                closeOnConfirm: true
            });
        } else {
            alert(titleText + "\n\n" + descText.replace(/<\/?[^>]+(>|$)/g, ""));
        }
    };
</script>

<style>
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

@if (session()->has('not_eligible_error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof window.showNotEligibleModal === 'function') {
                window.showNotEligibleModal();
            }
        });
    </script>
@endif
