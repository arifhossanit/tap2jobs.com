@php
    $settings = settings();
    $lang = session()->get('languageName');

    $seoTitle = trim($__env->yieldContent('title')) ?: getAppName();
    $seoFullTitle = $seoTitle === getAppName() ? $seoTitle : $seoTitle.' | '.getAppName();
    $seoDescription = trim($__env->yieldContent('meta_description'));
    if ($seoDescription === '') {
        $seoDescription = config('seo.default_description');
    }
    $seoDescription = \Illuminate\Support\Str::limit(
        preg_replace('/\s+/u', ' ', trim(strip_tags(html_entity_decode($seoDescription, ENT_QUOTES | ENT_HTML5, 'UTF-8')))),
        160,
        ''
    );

    $routeName = request()->route()?->getName();
    $queryKeys = array_keys(request()->query());
    $indexableQueryParameters = config('seo.indexable_query_parameters', ['page']);
    $trackingQueryParameters = config('seo.tracking_query_parameters', []);
    $facetedQueryParameters = array_diff($queryKeys, $indexableQueryParameters, $trackingQueryParameters);
    $isFacetedRoute = in_array($routeName, config('seo.faceted_routes', []), true);
    $hasFacetedQuery = $isFacetedRoute && count($facetedQueryParameters) > 0;
    $hasNoindexQuery = collect(config('seo.noindex_query_parameters', []))
        ->contains(fn ($parameter) => request()->has($parameter));

    $explicitCanonical = trim($__env->yieldContent('canonical_url'));
    $seoCanonical = $explicitCanonical ?: url()->current();
    $pageNumber = max(1, (int) request()->query('page', 1));
    if ($explicitCanonical === '' && !$hasFacetedQuery && !$hasNoindexQuery && $pageNumber > 1) {
        $seoCanonical .= '?page='.$pageNumber;
    }

    $seoRobots = trim($__env->yieldContent('robots'));
    if ($seoRobots === '') {
        $isNoindexRoute = collect(config('seo.noindex_routes', []))
            ->contains(fn ($pattern) => $routeName && \Illuminate\Support\Str::is($pattern, $routeName));
        $seoRobots = ($isNoindexRoute || $hasNoindexQuery || $hasFacetedQuery)
            ? 'noindex,follow'
            : 'index,follow';
    }

    $seoOgTitle = trim($__env->yieldContent('og_title')) ?: $seoTitle;
    $seoOgDescription = trim($__env->yieldContent('og_description')) ?: $seoDescription;
    $seoOgType = trim($__env->yieldContent('og_type')) ?: 'website';
    $seoOgImage = trim($__env->yieldContent('og_image')) ?: getSettingValue('logo');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{ getFrontSelectLanguage() == 'ar' ? 'dir=rtl' : '' }}>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $seoFullTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    @hasSection('meta_keywords')
        <meta name="keywords" content="{{ trim($__env->yieldContent('meta_keywords')) }}">
    @endif
    <meta name="robots" content="{{ $seoRobots }}">
    <link rel="canonical" href="{{ $seoCanonical }}">

    <meta property="og:type" content="{{ $seoOgType }}">
    <meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}">
    <meta property="og:site_name" content="{{ getAppName() }}">
    <meta property="og:title" content="{{ $seoOgTitle }}">
    <meta property="og:description" content="{{ $seoOgDescription }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    @if ($seoOgImage)
        <meta property="og:image" content="{{ $seoOgImage }}">
    @endif

    <meta name="twitter:card" content="{{ $seoOgImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $seoOgTitle }}">
    <meta name="twitter:description" content="{{ $seoOgDescription }}">
    @if ($seoOgImage)
        <meta name="twitter:image" content="{{ $seoOgImage }}">
    @endif
    @yield('meta_tags')
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
    <script src="{{ mix('js/front_pages.js') }}"></script>
</head>

<body {{ $lang == 'pt' || $lang == 'fr' || $lang == 'es' ? 'languages' : '' }}>
    @include('front_web.layouts.header_ad')
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
    @include('front_web.layouts.modals')
</body>

</html>
