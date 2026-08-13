@php
    $settings = settings();
@endphp
<!DOCTYPE html>
<html lang="{{ checkLanguageSession() }}" {{ checkLanguageSession() == 'ar' ? 'dir=rtl' : '' }}>

<head>
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
    @livewireStyles
    @routes
    @livewireScripts
    <style>
        .dashboard-faq-main {
            background: #eef3f9;
            min-height: calc(100vh - 70px);
            padding-top: 70px;
        }

        .dashboard-faq-main > .container-fluid {
            padding-bottom: 30px;
            padding-top: 30px;
        }

        .dashboard-faq-main .candidate-faq-page {
            box-shadow: none;
        }
    </style>
    @yield('page_css')
    @yield('css')
</head>

<script data-turbo-eval="false">
    let lancode = "{{ checkLanguageSession() }}";
</script>
<script src="{{ mix('js/third-party.js') }}"></script>
<script src="{{ mix('js/pages.js') }}"></script>

<body class="overflow-x-hidden">
    <div class="header fixed-header">
        @include($dashboardFaqHeader ?? 'candidate.layouts.header')
    </div>

    <main class="dashboard-faq-main">
        <div class="container-fluid container-xxl">
            @yield('content')
        </div>
    </main>

    @include('front_web.layouts.footer')

    <script data-turbo-eval="false">
        var hostUrl = 'assets/';
        let getLoggedInUserLang = '{{ getCurrentLanguageCode() }}';
        let defaultCountryCodeValue = "{{ getSettingValue('default_country_code') }}";
        Lang.setLocale(getLoggedInUserLang);
    </script>
    @yield('page_scripts')
    @yield('scripts')
    @stack('scripts')
</body>

</html>
