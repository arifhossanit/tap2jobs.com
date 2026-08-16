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
        <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
        @endif
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}?v={{ filemtime(public_path('assets/css/custom.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/footer.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/front-pages.css') }}">
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
            document.addEventListener('turbo:load', function () {
                closeCandidateHeaderDropdowns();
                restoreCandidateHeaderZIndex();
            });
        })();
    </script>
</body>

</html>
