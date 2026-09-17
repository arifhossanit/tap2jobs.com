@extends('front_web.layouts.app')
@php
    $isCategoryLandingPage = isset($seoCategory);
    $categoryName = $isCategoryLandingPage
        ? html_entity_decode(strip_tags($seoCategory->name), ENT_QUOTES | ENT_HTML5, 'UTF-8')
        : null;
    $pageNumber = max(1, (int) request('page', 1));
    $categoryFilterQuery = array_diff(
        array_keys(request()->query()),
        config('seo.indexable_query_parameters', ['page']),
        config('seo.tracking_query_parameters', [])
    );
    $hasCategoryFilterQuery = $isCategoryLandingPage && count($categoryFilterQuery) > 0;
    $baseSeoTitle = $isCategoryLandingPage
        ? ($seoCategory->seo_title ?: __('web.web_jobs.category_jobs_title', ['category' => $categoryName]))
        : __('web.job_menu.search_job');
    $seoPageTitle = $pageNumber > 1 ? $baseSeoTitle.' - Page '.$pageNumber : $baseSeoTitle;
    $categoryCanonical = $isCategoryLandingPage ? route('front.job-categories.show', $seoCategory) : null;
    if ($categoryCanonical && $pageNumber > 1 && !$hasCategoryFilterQuery) {
        $categoryCanonical .= '?page='.$pageNumber;
    }
    $categoryMetaDescription = $isCategoryLandingPage
        ? ($seoCategory->meta_description ?: __('web.web_jobs.category_meta_description', ['category' => $categoryName, 'app' => getAppName()]))
        : null;
    $breadcrumbSchema = $isCategoryLandingPage ? [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => __('web.home'), 'item' => route('front.home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => __('web.jobs'), 'item' => route('front.search.jobs')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $categoryName, 'item' => route('front.job-categories.show', $seoCategory)],
        ],
    ] : null;
@endphp
@section('title', $seoPageTitle)
@if ($isCategoryLandingPage)
    @section('meta_description', $categoryMetaDescription)
    @if (! empty($seoCategory->search_tags))
        @section('meta_keywords', implode(', ', $seoCategory->search_tags))
    @endif
    @section('canonical_url', $categoryCanonical)
    @section('og_title', $seoPageTitle)
    @section('og_description', $categoryMetaDescription)
    @section('og_image', $seoCategory->image_url)
    @section('meta_tags')
        <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endsection
@endif
@section('page_css')
    @if (\Illuminate\Support\Facades\App::getLocale() == 'ar')
        <style>
            .job-post-wrapper ul.pagination {
                direction: rtl;
            }
        </style>
    @endif
    {{--    <link href="{{asset('front_web/scss/jobs.css')}}" rel="stylesheet" type="text/css"> --}}
@endsection
@section('content')
    <div class="Find Jobs-page">
        <section class="hero-section position-relative bg-gradient pt-15 pb-40">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 text-center mb-lg-0 mb-md-5 mb-sm-4 ">
                        <div class="hero-content">
                            @if ($isCategoryLandingPage)
                                <h2 class="text-secondary mb-3">@lang('web.web_jobs.find_jobs')</h2>
                            @else
                                <h1 class="text-secondary mb-3">@lang('web.web_jobs.find_jobs')</h1>
                            @endif
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb  justify-content-center mb-0">
                                    <li class="breadcrumb-item "><a href="{{ route('front.home') }}"
                                            class="fs-18 text-gray">@lang('web.home') </a>
                                    </li>
                                    <li class="breadcrumb-item text-primary fs-18" aria-current="page">@lang('web.jobs')</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="latest-job-section py-60">
            @php
                $leftAds = getActiveAdsByPosition(\App\Models\Ad::POSITION_REGISTER_LEFT, \App\Models\Ad::PAGE_JOBS);
                $rightAds = getActiveAdsByPosition(\App\Models\Ad::POSITION_REGISTER_RIGHT, \App\Models\Ad::PAGE_JOBS);
            @endphp
            <x-front.side-ad-layout :left-ads="$leftAds" :right-ads="$rightAds">
                <div class="container px-0">
                @if ($isCategoryLandingPage)
                    <div class="row mb-3">
                        <div class="col-lg-8 offset-lg-4">
                            <h1 class="fs-4 text-secondary mb-0">{{ __('web.web_jobs.category_jobs_title', ['category' => $categoryName]) }}</h1>
                        </div>
                    </div>
                @endif
                <div class="row g-4 align-items-start">
                    <div class="col-lg-4 col-12 find-jobs-filter-column">
                        <button class="find-jobs-filter-mobile-toggle d-lg-none" type="button"
                                aria-expanded="false" aria-controls="findJobsFilter">
                            <span><i class="fa-solid fa-sliders" aria-hidden="true"></i>{{ __('messages.common.filters') }}</span>
                            <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                        </button>
                        <x-front.job-search-filter
                            :job-categories="$jobCategories"
                            :job-skills="$jobSkills"
                            :genders="$genders"
                            :career-levels="$careerLevels"
                            :functional-areas="$functionalAreas"
                            :job-types="$jobTypes"
                            :maximum-experience="$maximumExperience"
                            :input="$input"
                        />
                    </div>
                    <div class="col-lg-8 col-12">
                        <div class="job-card">
                            @livewire('job-search', ['initialCategory' => $isCategoryLandingPage ? (string) $seoCategory->id : ''])
                        </div>
                    </div>
                </div>
                </div>
            </x-front.side-ad-layout>
        </section>
    </div>
    {{ Form::hidden('jobType', json_encode($input), ['id' => 'input']) }}
@endsection
{{-- @section('page_scripts') --}}
{{--    <script> --}}
{{--        let input = JSON.parse('@json($input)'); --}}
{{--    </script> --}}
{{-- @endsection --}}
