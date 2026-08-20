@php
    $settings = settings();
    $lang = session()->get('languageName');
@endphp
<!DOCTYPE html>
<html lang="{{ checkLanguageSession() }}" {{ checkLanguageSession() == 'ar' ? 'dir=rtl' : '' }}>

<head>
    <base href="../">
    <title>@yield('title') | {{ getAppName() }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ getSettingValue('favicon') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    @if (getLoggedInUser()->theme_mode)
        <link rel="stylesheet" type="text/css" href="{{ mix('assets/css/custom-dark.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.dark.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins.dark.css') }}">
    @else
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins.css') }}">
    @endif
    <link rel="stylesheet" type="text/css" href="{{ mix('css/footer.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/front-pages.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}?v={{ filemtime(public_path('assets/css/custom.css')) }}">
    @livewireStyles
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/rappasoft/livewire-tables/css/laravel-livewire-tables.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/rappasoft/livewire-tables/css/laravel-livewire-tables-thirdparty.min.css') }}">

    @routes
    @livewireScripts

	<script src="{{ asset('vendor/rappasoft/livewire-tables/js/laravel-livewire-tables.min.js') }}"></script>
	<script src="{{ asset('vendor/rappasoft/livewire-tables/js/laravel-livewire-tables-thirdparty.min.js') }}"></script>


        @if (checkLanguageSession() == 'ar')
            <style>
                .horizontal-sidebar .horizontal-menu .nav-item .nav-link .horizontal-menu-icon {
                    padding-left: .625rem;
                }
                .modal-header .btn-close {
                    margin: -0.9375rem -0.9375rem -0.9375rem -0.9375rem !important;
                }
                .table.table-striped > :not(caption) > * > * , .table.table-striped > thead > tr > th{
                    padding: 0.625rem 1.875rem 0.625rem 0.25rem !important;
                    vertical-align: middle;
                }
                .input-group.has-validation>.dropdown-toggle:nth-last-child(n+4), .input-group.has-validation>:nth-last-child(n+3):not(.dropdown-toggle):not(.dropdown-menu), .input-group:not(.has-validation)>.dropdown-toggle:nth-last-child(n+3), .input-group:not(.has-validation)>:not(:last-child):not(.dropdown-toggle):not(.dropdown-menu) {
                    border-bottom-left-radius: 0;
                    border-top-left-radius: 0;
                    border-bottom-right-radius: 5px;
                    border-top-right-radius: 5px;
                }
                .input-group>:not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
                    border-bottom-right-radius: 0;
                    border-top-right-radius: 0;
                    border-bottom-left-radius: 5px;
                    border-top-left-radius: 5px;
                }
                #phoneNumber, #defaultCountryData {
                    text-align: end;
                    padding-right: 85px
                }
                .iti--allow-dropdown .iti__flag-container, .iti--separate-dial-code .iti__flag-container {
                    right: 0;
                    left: auto;
                }
                #phone{
                    text-align: end;
                }
                .ql-editor {
                    direction: rtl;
                    text-align: right;
                }
                .iti__country-list {
                    text-align: right;
                }
                .iti__flag-box, .iti__country-name {
                    margin-left: 6px;
                }
                .toast-title, .toast-message {
                    margin-right: 20px;
                }
            </style>
        @endif
    @yield('page_css')
    @yield('css')
    @stack('css')
    <style>
        body.candidate-front-shell {
            display: block !important;
            min-height: 100%;
            background: #eff3f7;
        }

        body.candidate-front-shell > header.bg-gradient,
        body.candidate-front-shell > footer {
            flex: 0 0 auto !important;
            height: auto !important;
            min-height: 0 !important;
        }

        body.candidate-front-shell > header.bg-gradient {
            display: block !important;
            margin: 0 !important;
            position: relative !important;
            top: auto !important;
            z-index: 1200 !important;
        }

        body.candidate-front-shell > header.bg-gradient .navbar {
            height: auto !important;
            min-height: 0 !important;
            padding: 20px 0 !important;
        }

        body.candidate-front-shell > header.bg-gradient .navbar > .container,
        body.candidate-front-shell > footer .container {
            height: auto !important;
            min-height: 0 !important;
        }

        body.candidate-front-shell > header.bg-gradient .navbar > .container {
            align-items: center !important;
            justify-content: space-between !important;
        }

        body.candidate-front-shell > header.bg-gradient .front-user-dropdown:not(.is-open) .front-user-dropdown-menu {
            display: none !important;
        }

        body.candidate-front-shell > header.bg-gradient .front-user-dropdown .front-user-dropdown-menu {
            inset: calc(100% + 8px) 0 auto auto !important;
            top: calc(100% + 8px) !important;
            right: 0 !important;
            left: auto !important;
            margin-top: 0 !important;
            transform: none !important;
        }

        body.candidate-front-shell > header.bg-gradient .front-user-dropdown.is-open .front-user-dropdown-menu {
            display: block !important;
            z-index: 1210 !important;
            max-height: calc(100vh - 180px);
            overflow-y: auto;
        }

        body.candidate-front-shell .candidate-front-layout-main {
            display: block !important;
            flex: none !important;
            min-height: auto !important;
            background: #eff3f7;
        }

        body.candidate-front-shell .candidate-profile-menu-shell {
            top: 0 !important;
            z-index: 1100 !important;
            margin-top: 0 !important;
        }

        body.candidate-front-shell .candidate-profile-menu {
            border: 2px solid #cfd8e6 !important;
            box-shadow: 0 8px 18px rgba(16, 24, 40, 0.08) !important;
        }

        body.candidate-front-shell .candidate-profile-menu__sub {
            border-right: 0 !important;
            border-bottom: 0 !important;
            border-left: 0 !important;
        }

        body.candidate-front-shell .header-padding {
            display: none !important;
        }

        /* Modal Z-Index and Background Blur/Dim when modal is open */
        body.modal-open #siteTopBanner,
        body.modal-open header,
        body.modal-open .candidate-front-layout-main,
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
            border-radius: 4px !important;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.4) !important;
        }

        /* Professional Slot Modal Custom CSS */
        #scheduleSlotBookModal .modal-content {
            background: #ffffff !important;
            overflow: hidden !important;
        }

        #scheduleSlotBookModal .modal-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 1.25rem 1.5rem !important;
        }

        #scheduleSlotBookModal .modal-title {
            font-size: 1.15rem !important;
            font-weight: 700 !important;
            color: #0f172a !important;
        }

        #scheduleSlotBookModal .slot-card-item {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.25rem;
        }

        #scheduleSlotBookModal .slot-card-item {
            cursor: pointer !important;
            transition: all 0.2s ease-in-out;
            user-select: none;
        }

        #scheduleSlotBookModal .slot-card-item:hover {
            border-color: #2563eb !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1) !important;
        }

        #scheduleSlotBookModal .slot-card-item:has(input[type="radio"]:checked),
        #scheduleSlotBookModal .slot-card-item.active-slot-card {
            border-color: #2563eb !important;
            background-color: #eff6ff !important;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.15) !important;
        }

        #scheduleSlotBookModal .slot-card-item.selected {
            border-color: #10b981 !important;
            background: #f0fdf4 !important;
        }

        .slot-radio-wrap {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        #scheduleSlotBookModal {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        #scheduleSlotBookModal .slot-radio-wrap:hover {
            background: #eff6ff;
            border-color: #3b82f6;
        }

        #scheduleSlotBookModal .history-card-item {
            background: #f8fafc;
            border-left: 4px solid #6366f1;
            padding: 1rem;
            margin-bottom: 0.75rem;
        }

        #scheduleSlotBookModal .modal-footer {
            background: #f8fafc !important;
            border-top: 1px solid #e2e8f0 !important;
            padding: 1rem 1.5rem !important;
        }

        #scheduleSlotBookModal .modal-footer .btn {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            font-size: 0.9rem !important;
            letter-spacing: 0.2px !important;
        }

        #scheduleSlotBookModal #scheduleInterviewBtnSave {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25) !important;
        }

        #scheduleSlotBookModal #scheduleInterviewBtnSave:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.4) !important;
            transform: translateY(-2px) !important;
        }

        #scheduleSlotBookModal #scheduleInterviewBtnSave:active {
            transform: translateY(0) !important;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3) !important;
        }

        #scheduleSlotBookModal #rejectSlotBtnSave {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2) !important;
        }

        #scheduleSlotBookModal #rejectSlotBtnSave:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 6px 18px rgba(239, 68, 68, 0.35) !important;
            transform: translateY(-2px) !important;
        }

        #scheduleSlotBookModal #rejectSlotBtnSave:active {
            transform: translateY(0) !important;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25) !important;
        }

        #scheduleSlotBookModal #scheduleInterviewBtnCancel {
            background: #ffffff !important;
            color: #475569 !important;
            border: 1px solid #cbd5e1 !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04) !important;
        }

        #scheduleSlotBookModal #scheduleInterviewBtnCancel:hover {
            background: #f8fafc !important;
            color: #0f172a !important;
            border-color: #94a3b8 !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
            transform: translateY(-1px) !important;
        }

        #scheduleSlotBookModal #scheduleInterviewBtnCancel:active {
            transform: translateY(0) !important;
        }

        #scheduleSlotBookModal .modal-dialog {
            max-height: calc(100vh - 40px) !important;
            margin-top: 20px !important;
            margin-bottom: 20px !important;
        }

        #scheduleSlotBookModal .modal-content {
            max-height: calc(100vh - 40px) !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
        }

        #scheduleSlotBookModal .modal-body {
            max-height: 65vh !important;
            overflow-y: auto !important;
            scrollbar-width: thin !important;
            scrollbar-color: #3b82f6 #f1f5f9 !important;
        }

        #scheduleSlotBookModal .modal-body::-webkit-scrollbar {
            width: 8px !important;
            display: block !important;
        }

        #scheduleSlotBookModal .modal-body::-webkit-scrollbar-track {
            background: #f1f5f9 !important;
            border-radius: 6px !important;
        }

        #scheduleSlotBookModal .modal-body::-webkit-scrollbar-thumb {
            background: #3b82f6 !important;
            border-radius: 6px !important;
        }

        #scheduleSlotBookModal .modal-body::-webkit-scrollbar-thumb:hover {
            background: #2563eb !important;
        }
    </style>
</head>
<script data-turbo-eval="false">
    let lancode = "{{ checkLanguageSession() }}";
</script>
<script src="{{ mix('js/third-party.js') }}"></script>
<script src="{{ mix('js/pages.js') }}"></script>

<body class="candidate-front-shell overflow-x-hidden {{ $lang == 'pt' || $lang == 'fr' || $lang == 'es' ? 'languages' : '' }}">
    @include('front_web.layouts.header_ad')
    <span class="header-padding"></span>
    @include('front_web.layouts.header')

    <main class="candidate-front-layout-main py-7">
        <div class="content">
            <div class="container-fluid container-xxl">
                @yield('content')
            </div>
        </div>
    </main>

    @include('front_web.layouts.footer')
    {{ Form::hidden('createNewLetterUrl', route('news-letter.create'), ['id' => 'createNewLetterUrl']) }}
    @include('candidate_profile.edit_profile_modal')
    @include('candidate_profile.change_password_modal')
    @include('jobs.modals.cities')
    <script data-turbo-eval="false">
        var hostUrl = 'assets/';
        let getLoggedInUserLang = '{{ getCurrentLanguageCode() }}';
        let defaultCountryCodeValue = "{{ getSettingValue('default_country_code') }}"
        Lang.setLocale(getLoggedInUserLang)
    </script>
    @yield('page_scripts')
    @yield('scripts')
    @stack('scripts')
    <script src="{{ mix('js/front_pages.js') }}"></script>
    <script>
        (function () {
            function closeCandidateHeaderDropdowns() {
                document.querySelectorAll('body.candidate-front-shell header .front-user-dropdown.is-open')
                    .forEach(function (dropdown) {
                        dropdown.classList.remove('is-open');
                        var toggle = dropdown.querySelector('.front-user-dropdown-toggle');
                        var menu = dropdown.querySelector('.front-user-dropdown-menu');

                        if (toggle) {
                            toggle.setAttribute('aria-expanded', 'false');
                        }

                        if (menu) {
                            menu.classList.remove('show');
                            menu.style.zIndex = '';
                        }
                    });
            }

            function restoreCandidateHeaderZIndex() {
                document.querySelectorAll('body.candidate-front-shell header .front-user-dropdown-menu')
                    .forEach(function (menu) {
                        menu.style.zIndex = '1210';
                    });
            }

            document.addEventListener('DOMContentLoaded', closeCandidateHeaderDropdowns);
            document.addEventListener('DOMContentLoaded', restoreCandidateHeaderZIndex);
            $(document).on('click', '.read-notification-item', function (e) {
                let id = $(this).attr('data-id');
                let targetUrl = $(this).attr('data-url');
                let element = $(this);
                
                // Visual effect: change style to indicate item is being processed & read
                element.css({ 'opacity': '0.5', 'pointer-events': 'none', 'background-color': '#f8fafc' });

                $.ajax({
                    url: route('read-notification', id),
                    type: 'POST',
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (result) {
                        let count = parseInt($('#candidateNotificationCount').text());
                        count = count > 0 ? count - 1 : 0;
                        if (count === 0) {
                            $('#candidateNotificationCount').remove();
                            $('.read-all-notification-btn').remove();
                        } else {
                            $('#candidateNotificationCount').text(count);
                        }
                        if (targetUrl) {
                            window.location.href = targetUrl;
                        } else {
                            element.fadeOut(300, function() { $(this).remove(); });
                        }
                    },
                    error: function() {
                        if (targetUrl) {
                            window.location.href = targetUrl;
                        }
                    }
                });
            });

            $(document).on('click', '.read-all-notification-btn', function (e) {
                $.ajax({
                    url: route('read-all-notification'),
                    type: 'POST',
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (result) {
                        if (result.success) {
                            $('.notification-scroll-body').html('<div class="p-4 text-center text-muted"><i class="fa-regular fa-bell-slash fs-3 mb-2 d-block text-secondary"></i><span class="fs-7">No notifications found</span></div>');
                            $('#candidateNotificationCount').remove();
                            $('.read-all-notification-btn').remove();
                        }
                    }
                });
            });
        })();
    </script>
</body>

</html>
