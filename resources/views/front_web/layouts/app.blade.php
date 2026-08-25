@php
    $settings = settings();
    $lang = session()->get('languageName');
@endphp
<!DOCTYPE html>
<html lang="en" {{ getFrontSelectLanguage() == 'ar' ? 'dir=rtl' : '' }}>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ getAppName() }}</title>
    <link rel="shortcut icon" href="{{ getSettingValue('favicon') }}" type="image/x-icon">
    <link rel="icon" href="{{ getSettingValue('favicon') }}" type="image/x-icon">
    <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('front_web/scss/bootstrap.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ asset('front_web/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/iziToast.min.css') }}">

    <link href="{{ asset('assets/css/front-third-party.css') }}" rel="stylesheet" type="text/css">
    @if (getFrontSelectLanguage() == 'ar')
        <style>
            .notice-section .notice-content span {
                border-radius: 0px 10px 0px 10px !important;
                left: 12px;
                right: auto !important;
            }
            footer .email input {
                border-radius: 0px 10px 10px 0px !important;
            }
            footer .email .icon {
                border-radius: 10px 0px 0px 10px !important;
            }
            header .navbar .navbar-nav .nav-item .submenu {
                right: 0;
            }
            .hero-content-row {
                left: 0% !important;
            }
            .how-it-works-section .work-process .arrow1 {
                right: 24%;
            }
            .how-it-works-section .work-process .arrow2 {
                right: 57%;
            }
            .iti--allow-dropdown .iti__flag-container, .iti--separate-dial-code .iti__flag-container {
                left: auto !important;
                right: 0 !important;
            }
            .mani-blog .blog-card .card-img-top {
                border-radius: 0px 10px 10px 0px !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                left: 10px !important;
            }
            .change-type {
                top: 15%;
                right: 91% !important;
            }
            .change-type-register {
                top: 15% !important;
                right: 83% !important;
            }
            .iti__country-list {
                text-align: right;
            }
            .iti__flag-box, .iti__country-name {
                margin-left: 6px;
            }
            #phoneNumber, #defaultCountryData {
                text-align: end;
                padding-right: 85px;
            }
            .iti--separate-dial-code .iti__selected-dial-code {
                margin-right: 6px;
                margin-left: 0px;
            }
            .iti__arrow {
                margin-right: 6px !important;
                margin-left: 0px;
            }
            .toast-title, .toast-message {
                margin-right: 20px;
            }
            .breadcrumb-item + .breadcrumb-item::before {
                float: right !important;
                padding-left: 0.5rem !important;
                color: #6c757d;
                content: var(--bs-breadcrumb-divider, "/");
            }
        </style>
    @else
        <style>
            .change-type {
                top: 15%;
                left: 91%;
            }
            .change-type-register {
                top: 15% !important;
                left: 83%;
            }
        </style>
    @endif
    <link href="{{ mix('css/front-pages.css') }}" rel="stylesheet" type="text/css">

    <style>
        /* When ANY modal is open, dim & blur ALL background elements (top promo banner, header, nav, page content, footer) */
        body.modal-open #siteTopBanner,
        body.modal-open header,
        body.modal-open .header-padding,
        body.modal-open main,
        body.modal-open footer,
        body.modal-open > *:not(.modal):not(.modal-backdrop):not(script):not(style) {
            filter: blur(6px) brightness(0.55) !important;
            transition: filter 0.25s ease-in-out !important;
            pointer-events: none !important;
        }

        .modal-backdrop {
            z-index: 100000 !important;
            background-color: rgba(15, 23, 42, 0.6) !important;
        }
        .modal {
            z-index: 100005 !important;
        }
        .modal-content {
            border: 0 !important;
            border-radius: 16px !important;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.4) !important;
        }

        .profile-incomplete-swal-popup {
            border-radius: 20px !important;
            padding: 24px !important;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25) !important;
        }
        .profile-incomplete-confirm-btn {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 10px 24px !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35) !important;
            margin: 4px !important;
        }
        .profile-incomplete-cancel-btn {
            background-color: #cbd5e1 !important;
            color: #475569 !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 10px 24px !important;
            font-weight: 600 !important;
            margin: 4px !important;
        }
    </style>

    @yield('page_css')
    @livewireStyles
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/rappasoft/livewire-tables/css/laravel-livewire-tables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/rappasoft/livewire-tables/css/laravel-livewire-tables-thirdparty.min.css') }}">
    @routes

    @livewireScripts
    <script src="{{ asset('vendor/rappasoft/livewire-tables/js/laravel-livewire-tables.min.js') }}"></script>
	<script src="{{ asset('vendor/rappasoft/livewire-tables/js/laravel-livewire-tables-thirdparty.min.js') }}"></script>

    <script src="{{ mix('js/auth-third-party.js') }}"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ mix('js/front-third-party.js') }}"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script>
        let siteKey = "{{ config('app.google_recaptcha_site_key') }}"
    </script>
    {{-- <script src="{{ mix('js/front_pages.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/custom/custom.js') }}"></script> --}}

    @yield('page_scripts')
    @foreach (googleJobSchema() as $jobSchema)
        {!! nl2br($jobSchema) !!}
    @endforeach
    <script src="{{ mix('js/front_pages.js') }}"></script>
</head>

<body {{ $lang == 'pt' || $lang == 'fr' || $lang == 'es' ? 'languages' : '' }}>
    @if (Request::is('/') || Request::is('home') || request()->routeIs('front.home'))
        @include('front_web.layouts.header_ad')
    @endif
    <span class="header-padding"></span>
    @include('front_web.layouts.header')

    @yield('content')

    @include('front_web.layouts.footer')

    {{ Form::hidden('createNewLetterUrl', route('news-letter.create'), ['id' => 'createNewLetterUrl']) }}
    <script data-turbo-eval="false">
        let defaultCountryCodeValue = "{{ getSettingValue('default_country_code') }}";
        let currentFrontLang = "{{ session()->get('languageName') ?? 'en' }}";
        let lancode = "{{ getFrontSelectLanguage() }}";
        Lang.setLocale(lancode);
    </script>
     <script src="{{ mix('assets/js/custom/custom.js') }}"></script>
     <script>
         (function () {
             function frontNotificationEmptyState() {
                 return $('<div/>', {
                     class: 'p-4 text-center text-muted d-flex flex-column align-items-center justify-content-center'
                 }).append(
                     $('<i/>', { class: 'fa-regular fa-bell-slash fs-3 mb-2 text-secondary' }),
                     $('<span/>', { class: 'fs-7', text: 'No notification found' })
                 );
             }

             function buildFrontNotificationItem(notification) {
                 var isRead = !!notification.is_read;
                 return $('<div/>', {
                     class: 'dropdown-item border-bottom py-2 px-3 text-wrap d-flex align-items-start gap-2 read-notification-item',
                     'data-id': notification.id,
                     'data-url': notification.url || '',
                     'data-read': isRead ? '1' : '0'
                 }).css({
                     cursor: 'pointer',
                     transition: 'background 0.2s ease, opacity 0.2s ease',
                     background: isRead ? 'transparent' : 'rgba(101, 113, 255, 0.08)',
                     opacity: isRead ? '0.7' : '1'
                 }).append(
                     $('<div/>', { class: 'rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center mt-1' })
                         .css({ width: '28px', height: '28px', flexShrink: 0 })
                         .append($('<i/>', { class: notification.icon || 'fa fa-inbox' }).css('font-size', '0.75rem')),
                     $('<div/>', { class: 'w-100' }).append(
                         $('<p/>', { class: 'mb-1 fw-semibold text-dark lh-sm', text: notification.title || '' }).css('font-size', '0.8125rem'),
                         $('<span/>', { class: 'text-muted', text: notification.created_at || '' })
                             .css('font-size', '0.725rem')
                             .prepend($('<i/>', { class: 'fa-regular fa-clock me-1' }))
                     )
                 );
             }

             function renderFrontNotifications(data) {
                 var list = $('.notification-scroll-body');
                 var counter = $('#candidateNotificationCount');
                 var count = parseInt(data.count || 0);

                 counter.text(count).toggleClass('d-none', count === 0);
                 list.empty();

                 if (!data.notifications || data.notifications.length === 0) {
                     list.append(frontNotificationEmptyState());
                     return;
                 }

                 data.notifications.forEach(function (notification) {
                     list.append(buildFrontNotificationItem(notification));
                 });
             }

             function refreshFrontNotifications() {
                 if (!$('#frontNotificationDropdown').length) {
                     return;
                 }

                 $.ajax({
                     url: route('notifications.latest'),
                     type: 'GET',
                     success: function (result) {
                         if (result.success) {
                             renderFrontNotifications(result.data);
                         }
                     }
                 });
             }

             if ($('#frontNotificationDropdown').length) {
                 setInterval(refreshFrontNotifications, 30000);
             }

             $(document).on('click', '.read-notification-item', function (e) {
                 e.preventDefault();
                 e.stopImmediatePropagation();

                 var item = $(this);
                 var id = item.attr('data-id');
                 var targetUrl = item.attr('data-url');
                 var wasUnread = item.attr('data-read') !== '1';

                 item
                     .attr('data-read', '1')
                     .css({ opacity: '0.7', backgroundColor: 'transparent' });

                 if (wasUnread) {
                     var counter = $('#candidateNotificationCount');
                     var count = parseInt(counter.text() || '0');
                     count = count > 0 ? count - 1 : 0;
                     counter.text(count).toggleClass('d-none', count === 0);
                 }

                 $.ajax({
                     url: route('read-notification', id),
                     type: 'POST',
                     data: {
                         '_token': $('meta[name="csrf-token"]').attr('content'),
                     },
                     success: function (result) {
                         var responseUrl = result.data && result.data.url ? result.data.url : '';
                         var redirectUrl = targetUrl || responseUrl;

                         if (redirectUrl) {
                             setTimeout(function () {
                                 window.location.href = redirectUrl;
                             }, 140);
                         }
                     },
                     error: function () {
                         if (wasUnread) {
                             var counter = $('#candidateNotificationCount');
                             var count = parseInt(counter.text() || '0') + 1;
                             counter.text(count).toggleClass('d-none', count === 0);
                         }

                         item
                             .attr('data-read', wasUnread ? '0' : '1')
                             .css({
                                 opacity: wasUnread ? '1' : '0.7',
                                 backgroundColor: wasUnread ? 'rgba(101, 113, 255, 0.08)' : 'transparent'
                             });
                     }
                 });
             });
         })();

         window.showProfileIncompleteModal = function (percentage, profileUrl) {
             percentage = parseInt(percentage) || 0;
             var isBn = (typeof lancode !== 'undefined' && lancode === 'bn');

             var titleText = isBn ? "প্রোফাইল অসম্পূর্ণ!" : "Profile Incomplete!";
             var descText = isBn 
                 ? "চাকরিতে আবেদন করতে আপনার প্রোফাইল অন্তত <b>৮০%</b> সম্পূর্ণ করতে হবে।" 
                 : "Your profile must be at least <b>80%</b> complete to apply for jobs.";
             var currentText = isBn 
                 ? "আপনার বর্তমান প্রোফাইল: <b>" + percentage + "%</b> সম্পূর্ণ" 
                 : "Currently your profile is <b>" + percentage + "%</b> complete.";
             var confirmBtnText = isBn ? "প্রোফাইলে যান" : "Go to Profile";
             var cancelBtnText = isBn ? "বাতিল" : "Cancel";

             var radius = 40;
             var circumference = 2 * Math.PI * radius; // ~251.327
             var dashoffset = circumference - (circumference * Math.min(percentage, 100) / 100);

             var htmlContent = `
                 <div style="font-family: inherit; padding: 10px 0 5px 0; text-align: center;">
                     <!-- Circular Progress Ring matching Image 2 -->
                     <div style="position: relative; width: 110px; height: 110px; margin: 0 auto 20px auto; display: flex; align-items: center; justify-content: center;">
                         <svg width="110" height="110" viewBox="0 0 100 100" style="transform: rotate(-90deg); filter: drop-shadow(0px 4px 12px rgba(217, 70, 239, 0.25));">
                             <circle cx="50" cy="50" r="40" stroke="#fce7f3" stroke-width="9" fill="transparent" />
                             <circle cx="50" cy="50" r="40" stroke="url(#progressGradientPink)" stroke-width="9" stroke-linecap="round" fill="transparent"
                                 stroke-dasharray="${circumference}" stroke-dashoffset="${dashoffset}"
                                 style="transition: stroke-dashoffset 0.8s ease-in-out;" />
                             <defs>
                                 <linearGradient id="progressGradientPink" x1="0%" y1="0%" x2="100%" y2="100%">
                                     <stop offset="0%" stop-color="#ec4899" />
                                     <stop offset="100%" stop-color="#be185d" />
                                 </linearGradient>
                             </defs>
                         </svg>
                         <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;">
                             <span style="font-size: 24px; font-weight: 800; color: #0f172a; font-family: system-ui, -apple-system, sans-serif;">${percentage}%</span>
                         </div>
                     </div>

                     <h3 style="font-size: 22px; font-weight: 700; color: #1e293b; margin: 0 0 12px 0;">${titleText}</h3>
                     
                     <p style="font-size: 15px; color: #475569; line-height: 1.5; margin: 0 0 8px 0;">${descText}</p>
                    
                 </div>
             `;

             if (typeof Swal !== 'undefined' && typeof Swal.fire === 'function') {
                 Swal.fire({
                     html: htmlContent,
                     showCancelButton: true,
                     confirmButtonText: confirmBtnText,
                     cancelButtonText: cancelBtnText,
                     confirmButtonColor: '#6366f1',
                     cancelButtonColor: '#94a3b8',
                     customClass: {
                         popup: 'profile-incomplete-swal-popup',
                         confirmButton: 'profile-incomplete-confirm-btn',
                         cancelButton: 'profile-incomplete-cancel-btn'
                     }
                 }).then(function (result) {
                     if (result.isConfirmed || result.value) {
                         window.location.href = profileUrl;
                     }
                 });
             } else if (typeof swal === 'function') {
                 swal({
                     title: "",
                     text: htmlContent,
                     html: true,
                     showCancelButton: true,
                     confirmButtonColor: '#6366f1',
                     confirmButtonText: confirmBtnText,
                     cancelButtonText: cancelBtnText,
                     closeOnConfirm: true
                 }, function (isConfirm) {
                     if (isConfirm || isConfirm === true) {
                         window.location.href = profileUrl;
                     }
                 });
             } else {
                 if (confirm(titleText + "\n\n" + descText.replace(/<\/?[^>]+(>|$)/g, "") + "\n\n" + confirmBtnText)) {
                     window.location.href = profileUrl;
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
     </script>

    @if (session()->has('profile_incomplete'))
        @php $profileData = session()->get('profile_incomplete'); @endphp
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var percentage = "{{ $profileData['percentage'] ?? 0 }}";
                var profileUrl = "{{ $profileData['profile_url'] ?? route('candidate.profile') }}";
                if (typeof window.showProfileIncompleteModal === 'function') {
                    window.showProfileIncompleteModal(percentage, profileUrl);
                }
            });
        </script>
    @endif
    </body>

</html>
