@extends('front_web.layouts.app')
@section('title')
    {{ __('web.job_menu.search_job') }}
@endsection
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
                    <div class="col-lg-6  text-center mb-lg-0 mb-md-5 mb-sm-4 ">
                        <div class="hero-content">
                            <h1 class=" text-secondary mb-3">
                                @lang('web.web_jobs.find_jobs')
                            </h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb  justify-content-center mb-0">
                                    <li class="breadcrumb-item "><a href="{{ route('front.home') }}"
                                            class="fs-18 text-gray">@lang('web.home') </a>
                                    </li>
                                    <li class="breadcrumb-item text-primary fs-18" aria-current="page">@lang('web.jobs')
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="latest-job-section py-60">
            <div class="container">
                @php
                    $rightAds = getActiveAdsByPosition(\App\Models\Ad::POSITION_REGISTER_RIGHT, \App\Models\Ad::PAGE_JOBS);
                    if ($rightAds->isEmpty()) {
                        $rightAds = getActiveAdsByPosition(\App\Models\Ad::POSITION_REGISTER_LEFT, \App\Models\Ad::PAGE_JOBS);
                    }
                @endphp

                <div class="row g-4 align-items-start">
                    <div class="{{ $rightAds->isNotEmpty() ? 'col-lg-3 col-md-4' : 'col-lg-4' }} find-jobs-filter-column">
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
                    <div class="{{ $rightAds->isNotEmpty() ? 'col-lg-7 col-md-8' : 'col-lg-8' }} px-lg-3">
                        <div class="job-card">
                            @livewire('job-search')
                        </div>
                    </div>
                    @if ($rightAds->isNotEmpty())
                        <div class="col-lg-2 col-12 find-jobs-right-ads-column">
                            <div class="find-jobs-right-ads sticky-top" style="top: 20px;">
                                @include('front_web.common.register_side_ad', ['ads' => $rightAds])
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
    {{ Form::hidden('jobType', json_encode($input), ['id' => 'input']) }}
@endsection
{{-- @section('page_scripts') --}}
{{--    <script> --}}
{{--        let input = JSON.parse('@json($input)'); --}}
{{--    </script> --}}
{{-- @endsection --}}
