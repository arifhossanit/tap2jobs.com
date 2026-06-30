@extends('front_web.layouts.app')
@section('title')
    {{ __('web.home') }}
@endsection
@section('page_css')
@endsection
@section('content')
    <div class="home-page">
        <!-- start hero section -->
        @if ($settings->value != 0)
            <section class="hero-section bg-gradient position-relative">
                <div id="carouselExampleControlshero" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" style="height:650px">
                        @if (count($headerSliders) > 0)
                            @foreach ($headerSliders as $key => $headerSlider)
                                <div class="bg-image carousel-item {{ $key == 0 ? 'active' : '' }} position-relative pt-100 pb-150"
                                    style="position: relative;background-image: url({{ $headerSlider->header_slider_url }});height:100%;color: white; text-align: center;">
                                </div>
                            @endforeach
                        @endif
                        <div class="hero-content-row row w-100">
                            <div class="col-lg-6 text-center">
                                <div class="hero-contentt mt-lg-0 mt-md-5 my-4">
                                    <p class="fw-normal text-dark fs-50 mb-0">@lang('web.easy_to_find_your')</p>
                                    <h1 class="fs-44 mb-md-4 mb-3 text-primary fw-bold">
                                        {{ $cmsServices['home_title'] }}
                                    </h1>
                                    <p class="mb-4 fs-18 text-gray">
                                        {{ $cmsServices['home_description'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (count($headerSliders) > 0)
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControlshero"
                            data-bs-slide="prev">
                            <i class="icon fa-solid fa-arrow-left text-white"></i>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControlshero"
                            data-bs-slide="next">
                            <i class="icon fa-solid fa-arrow-right text-white"></i>
                        </button>
                    @endif
                </div>
            </section>
        @else
            <section class="hero-section position-relative bg-gradient pt-100 pb-150">
                <div class="container">
                    <div class="row align-items-center flex-column-reverse flex-lg-row">
                        <div
                            class="col-lg-6 {{ getFrontSelectLanguage() == 'ar' ? 'text-lg-end' : 'text-lg-start' }} text-center">
                            <div class="hero-content mt-lg-0 mt-md-5 my-4">
                                <p class="fw-normal fs-50 mb-0">@lang('web.easy_to_find_your')</p>
                                <h1 class="fs-44 mb-md-4 mb-3 text-primary fw-bold">
                                    {{ $cmsServices['home_title'] }}
                                </h1>
                                <p class="mb-4 fs-18 text-gray pe-lg-5 me-xl-5">
                                    {{ $cmsServices['home_description'] }}
                                </p>
                            </div>

                        </div>
                        @if ($settings->value == 0 || count($headerSliders) <= 0)
                            <div
                                class="col-lg-6 {{ getFrontSelectLanguage() == 'ar' ? 'text-lg-start' : 'text-lg-end' }} text-center">
                                <img src="{{ $cmsServices['home_banner'] ? asset($cmsServices['home_banner']) : asset('front_web/images/hero-img.png') }}"
                                    alt="jobs-landing" class="hero-image img-fluid w-100" />
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        <!-- end hero section -->

        <!--start find-job section-->
        <section class="find-job-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-8 col-lg-10">
                        <div class="find-job position-relative bg-white">
                            <form action="{{ route('front.search.jobs') }}" id='searchForm' method="get">
                                <div class="row align-items-center justify-content-around m-0">
                                    <div class="col-lg-5 br-2 ps-lg-4 px-20">
                                        <h3 class="fs-16 text-secondary mb-0">@lang('web.home_menu.keywords')</h3>
                                        <input type="text" class="fs-14 text-gray mb-0" name="keywords"
                                            id="search-keywords" placeholder="@lang('web.web_home.job_title_keywords_company')" autocomplete="off" />
                                        <div id="jobsSearchResults" class="position-absolute w100 job-search"
                                            style="display: none;">
                                            <ul class="job-search-dropdown nav submenu">
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 br-2 ps-lg-4 px-20 pb-0 autocomplete-wrapper">
                                        <h3 class="fs-16 text-secondary mb-0">@lang('web.common.location')</h3>
                                        <input type="text" class="fs-14 text-gray mb-0 pb-4" name="location"
                                            id="search-location" placeholder="@lang('web.web_home.city_or_postcode')" />
                                    </div>
                                    <div class="col-lg-3 text-center p-lg-3 px-20">
                                        <button class="btn btn-primary  find-jobs-btn d-block p-0 px-2 pt-3 pb-3"
                                            style="width: 100%" type="submit">
                                            @lang('web.web_home.find_jobs')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--end find-job section-->

        <!-- start-companies-logo section -->
        @if (count($branding) > 0)
            <section class="comapnies-logo-section my-5">
                <div class="container">
                    <div class="slick-slider" id="brandSlider">
                        @foreach ($branding as $brand)
                            <div class="slide d-flex justify-content-center align-items-center m-4">
                                <img src="{{ $brand->branding_slider_url }}" alt="Branding Slider" class="img-fluid" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
        <!-- end-companies-logo section -->

        <!-- start-slider-test-img section -->
        @if (count($imageSliders) > 0 && $imageSliderActive->value)
            <section class="{{ $slider->value == 0 ? 'container' : ' ' }} slider-test-section position-relative mb-80">
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($imageSliders as $key => $imageSlider)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }} position-relative">
                                <img src="{{ $imageSlider->image_slider_url }}" class="d-block w-100 slider-img"
                                    alt="..." />
                                @if ($imageSlider->description)
                                    <div class="row justify-content-center">
                                        <div class="slider-img-desc col-10 text-center position-absolute">
                                            <p class="text-white fs-16">
                                                {!! Str::limit($imageSlider->description, 495, ' ...') !!}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                        data-bs-slide="prev">
                        <i class="icon fa-solid fa-arrow-left text-white"></i>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                        data-bs-slide="next">
                        <i class="icon fa-solid fa-arrow-right text-white"></i>
                    </button>
                </div>
            </section>
        @endif


        <!-- end-slider-test-img section -->

        <!-- start-popular-job-categories-section -->
        @if (count($jobCategories) > 0)
            <section class="popular-job-categories-section mb-80">
                <div class="container">
                    <div class="overflow-hidden pb-60">
                        <div class="section-heading text-center">
                            <h2 class="text-secondary mb-0 d-inline-block">
                                @lang('web.home_menu.popular_categories')
                            </h2>
                        </div>
                    </div>

                    <div class="job-categories-card">
                        <div class="row">
                            @foreach ($jobCategories as $jobCategory)
                                <div class="col-xl-3 col-lg-4 col-md-6 mb-40 px-xl-3">
                                    <div class="card px-20">
                                        <div class="card-img text-center mb-3">
                                            <img src="{{ $jobCategory->image_url }}" class="h-100" alt="..." />
                                        </div>
                                        <div class="card-body text-center">
                                            <a href="{{ route('front.search.jobs', ['categories' => $jobCategory->id]) }}"
                                                class="text-secondary primary-link-hover">
                                                <h5 class="card-title fs-18 text-secondary mb-0">
                                                    {{ html_entity_decode($jobCategory->name) }}</h5>
                                            </a>
                                            {{-- <p class="card-text fs-14 text-gray text-primary">
                                                {{ ($jobCategory->jobs_count ? $jobCategory->jobs_count : 0) . ' open positions' }}
                                            </p> --}}
                                            @if ($jobCategory->jobs_count <= 0)
                                                <p class="card-text fs-14 text-gray text-primary">
                                                    {{ 'No positions' }}
                                                </p>
                                            @else
                                                <a href="{{ route('front.search.jobs', ['categories' => $jobCategory->id]) }}"
                                                    class="card-text fs-14 text-gray text-primary">
                                                    {{ $jobCategory->jobs_count }} {{ 'open positions' }}
                                                </a>
                                            @endif
                                        </div>

                                    </div>

                                </div>
                            @endforeach
                            <div class="col-12 text-center">
                                <a href="{{ route('front.categories') }}" class="btn btn-primary  fs-14 mt-3">
                                    @lang('web.common.browse_all')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        <!-- start-popular-job-categories-section -->

        <!-- start latest-job-section -->
        @if (count($latestJobs) > 0)
            <section class="latest-job-section py-100 bg-light">
                <div class="container">
                    <div class="overflow-hidden pb-60">
                        <div class="section-heading text-center">
                            <h2 class="text-secondary mb-0 d-inline-block">@lang('web.home_menu.latest_jobs')</h2>
                        </div>
                    </div>
                    <div class="job-card">
                        <div class="row">
                            @if (
                                \Illuminate\Support\Facades\Auth::check() && isset(auth()->user()->country_name) && isset($latestJobsEnable)
                                    ? $latestJobsEnable->value
                                    : '')
                                @if (in_array(auth()->user()->country_name, array_column($latestJobs->toArray(), 'country_name')))
                                    @foreach ($latestJobs as $job)
                                        @if ($job->country_name == auth()->user()->country_name)
                                            @include('front_web.common.job_card')
                                        @endif
                                    @endforeach
                                    <div class="col-md-12 text-center">
                                        <a href="{{ route('front.search.jobs') }}"
                                            class="btn btn-primary  fs-14 mt-3">{{ __('web.common.browse_all') }}</a>
                                    </div>
                                @else
                                    <div class="col-md-12 text-center">
                                        <a href="{{ route('front.search.jobs') }}"
                                            class="btn btn-primary  fs-14 mt-3">{{ __('web.common.browse_all') }}</a>
                                    </div>
                                @endif
                            @else
                                @foreach ($latestJobs as $job)
                                    @include('front_web.common.job_card')
                                @endforeach
                                <div class="col-12 text-center">
                                    <a href="{{ route('front.search.jobs') }}" class="btn btn-primary  fs-14 mt-3">
                                        @lang('web.common.browse_all')
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        @endif
        <!-- end latest-job-section -->

        <!-- start featured-job-section -->
        @if (count($featuredJobs))
            <section class="featured-job-section py-100">
                <div class="container">
                    <div class="overflow-hidden pb-60">
                        <div class="section-heading text-center">
                            <h2 class="text-secondary mb-0 d-inline-block">@lang('web.home_menu.featured_jobs')</h2>
                        </div>
                    </div>
                    <div class="job-card">
                        <div class="row">
                            @foreach ($featuredJobs as $job)
                                @include('front_web.common.job_card')
                            @endforeach
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12 text-center">
                                <a class="btn btn-primary  fs-14 mt-3"
                                    href="{{ route('front.search.jobs', ['is_featured' => true]) }}">
                                    @lang('web.common.browse_all')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        <!-- end featured-job-section -->

        <!-- start featured-company-section -->
        @if (count($featuredCompanies))
            <section class="featured-job-section py-80">
                <div class="container">
                    <div class="overflow-hidden pb-60">
                        <div class="section-heading text-center">
                            <h2 class="text-secondary mb-0 d-inline-block">@lang('web.home_menu.featured_companies')</h2>
                        </div>
                    </div>
                    <div class="job-card">
                        <div class="row">

                            @foreach ($featuredCompanies->take(8) as $company)
                                @include('front_web.common.company_card')
                            @endforeach
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12 text-center">
                                <a class="btn btn-primary  fs-14 mt-3"
                                    href="{{ route('front.company.lists', ['is_featured' => true]) }}">
                                    @lang('web.common.browse_all')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        <!-- end featured-company-section -->

        <!-- start notice-section -->
        @if (count($notices) > 0)
            <section
                class="notice-section {{ count($latestJobs) > 0 ? (count($featuredJobs) > 0 ? 'mt-40' : 'mt-100 mb-100') : 'mb-100' }}">
                <div class="container">
                    <div class="notice-content bg-gradient">
                        <div class="overflow-hidden mx-3">
                            <div class="section-heading text-center py-60">
                                <h2 class="text-secondary mb-0 d-inline-block">@lang('web.home_menu.notices')</h2>
                            </div>
                        </div>
                        <div class="autoscroller">
                            <div class="marquee">
                                <div class="row justify-content-center">
                                    @foreach ($notices as $key => $notice)
                                        <div class="col-xl-10 col-11 position-relative mb-4">
                                            <div class="notice-desc bg-white py-20 px-md-5 px-4">
                                                <p class="text-secondary">
                                                    {!! nl2br(strip_tags($notice->description)) !!}
                                                </p>
                                                <p class="fs-14 text-gray mb-md-0 mb-5">
                                                    {{ html_entity_decode($notice->title) }}
                                                    | {{ $notice->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <span href="#"
                                                class="btn-primary  position-absolute">{{ \Carbon\Carbon::parse($notice->created_at)->translatedFormat('jS M, Y') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        <!-- end notice-section -->

        <!-- start testimonial-section -->
        @if (count($testimonials) > 0)
            <section
                class="testimonial-section overflow-hidden  {{ count($notices) > 0 ? 'mb-100' : 'mt-80 mb-100' }}  {{ count($latestJobs) > 0 ? 'mt-80 mb-100' : '' }}">
                <div class="container-fluid p-sm-0">
                    <div class="overflow-hidden pb-60">
                        <div class="section-heading text-center">
                            <h2 class="text-secondary mb-0 d-inline-block">@lang('web.home_menu.testimonials')</h2>
                        </div>
                    </div>
                    <div class="">
                        <div class="testimonial-slider">
                            @foreach ($testimonials as $testimonial)
                                <div>
                                    <div class="testimonial-card bg-light">
                                        <div class="desc">
                                            <p> {!! !empty(nl2br($testimonial->description)) ? nl2br($testimonial->description) : __('messages.n/a') !!} </p>
                                        </div>
                                        <div class="">
                                            <div class="d-flex justify-content-between align-items-end">
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="testimonial-profile {{ getFrontSelectLanguage() == 'ar' ? 'ms-3' : 'me-3' }}">
                                                        <img src="{{ isset($testimonial->customer_image_url) ? $testimonial->customer_image_url : asset('assets/img/infyom-logo.png') }}"
                                                            class="w-100 h-100 object-fit-cover" alt="profile">
                                                    </div>
                                                    <div class="profile-desc">
                                                        <h6 class="fs-18 mb-1">
                                                            {{ html_entity_decode($testimonial->customer_name) }} </h6>
                                                    </div>
                                                </div>
                                                <div class="text-end comma-img"> <img
                                                        src="{{ asset('img_template/comma.png') }}" class="w-100"
                                                        alt="comma"> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif
        <!-- end testimonial-section -->

        <!-- start blog-section -->
        @if (count($recentBlog) > 0)
            <section class="recent-blog-section pt-100 pb-60 bg-light">
                <div class="container">
                    <div class="overflow-hidden pb-60">
                        <div class="section-heading text-center">
                            <h2 class="text-secondary mb-0 d-inline-block">@lang('messages.recent_blog')</h2>
                        </div>
                    </div>
                    <div class="blog-card">
                        <div class="row">
                            @foreach ($recentBlog as $post)
                                <div class="col-lg-4 col-md-6 mb-40 px-xl-3">
                                    <div class="card">
                                        <div class="card-img-top position-relative">
                                            <img src="{{ empty($post->blog_image_url) ? asset('front_web/images/blog-1.png') : $post->blog_image_url }}"
                                                class="card-img-top" alt="Employee Motivation" />
                                            <div class="overlay position-absolute">
                                                <a href="{{ route('front.posts.details', $post->id) }}"
                                                    class="btn text-white fs-16"> {{ __('web.post_menu.read_more') }} </a>
                                            </div>
                                        </div>
                                        <div class="card-body px-20">
                                            <a href="{{ route('front.posts.details', $post->id) }}"
                                                class="text-secondary primary-link-hover">
                                                <h5 class="card-title fs-18 text-secondary">
                                                    {{ html_entity_decode($post->title) }}
                                                </h5>
                                            </a>
                                            <p class="card-text fs-14 text-gray">
                                                {!! !empty($post->description) ? Str::limit(strip_tags($post->description), 100, '...') : __('messages.n/a') !!}
                                            </p>
                                            <span class="fs-14 text-gray">
                                                @if ($post->comments_count == 0 || $post->comments_count == 1)
                                                    {{ \Carbon\Carbon::parse($post->created_at)->translatedFormat('M jS Y') }}
                                                    | {{ $post->comments_count . ' ' . __('messages.post.comment') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($post->created_at)->translatedFormat('M jS Y') }}
                                                    | {{ $post->comments_count . ' ' . __('messages.post.comment') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif
        <!-- end blog-section -->

        <!-- start-about-section -->
        <section class="about-section py-60 bg-primary">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-sm-3 col-6 text-center mb-sm-0 mb-4">
                        <div class="about-desc">
                            <h3 class="text-white count">
                                {{ $dataCounts['candidates'] }}
                            </h3>
                            <p class="text-white fs-18 mb-0">@lang('messages.front_home.candidates')</p>
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6 text-center mb-sm-0 mb-4">
                        <div class="about-desc">
                            <h3 class="text-white count">
                                {{ $dataCounts['jobs'] }}
                            </h3>
                            <p class="text-white fs-18 mb-0">@lang('messages.front_home.jobs')</p>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6 text-center">
                        <div class="about-desc">
                            <h3 class="text-white count">
                                {{ $dataCounts['resumes'] }}
                            </h3>
                            <p class="text-white fs-18 mb-0">@lang('messages.front_home.resumes')</p>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6 text-center">
                        <div class="about-desc">
                            <h3 class="text-white count">
                                {{ $dataCounts['companies'] }}
                            </h3>
                            <p class="text-white fs-18 mb-0">@lang('messages.front_home.companies')</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end-about-section -->

        <!-- start pricing-packages-section -->
        {{-- @if (count($plans) > 0)
            <section class="pricing-packages-section pt-100 pb-60">
                <div class="container">
                    <div class="overflow-hidden pb-60">
                        <div class="section-heading text-center">
                            <h2 class="text-secondary mb-0 d-inline-block">
                                @lang('web.web_home.pricing_packages')
                            </h2>
                        </div>
                    </div>
                    <section class="slider-test-section pricing-plans position-relative">
                        <div id="carouselExampleControlsabout" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($plansArray as $key => $plans)
                                @php
                                    $isshoebtn = 0;
                                    if ($key >= 1) { $isshoebtn = 1; }
                                @endphp
                                    <div class="carousel-item position-relative {{ $key == 0 ? 'active' : '' }}">
                                        <div class="row d-flex justify-content-center">
                                            @foreach ($plans as $plan)
                                                <div class="pricing-packages-section-card col-lg-4 col-sm-6 px-xl-3">
                                                    <div class="pricing-plan-card card bg-light shadow-none">
                                                        <div class="card-body text-center p-0">
                                                            <div class="card-body-top py-30">
                                                                <p class="mb-2 text-primary fs-18 fw-bold">
                                                                    {{ html_entity_decode(Str::limit($plan['name'], 50, '...')) }}
                                                                </p>
                                                                <div class="text-center d-flex justify-content-center">
                                                                    <h3 class="text-secondary mb-0">
                                                                        {{ empty($plan['salary_currency']['currency_icon']) ? '$' : $plan['salary_currency']['currency_icon'] }}{{ $plan['amount'] }}
                                                                    </h3>
                                                                    @if ($plan['unlimited_plan'] == 1)
                                                                    <span class="text-gray mt-xl-4 mt-sm-3 mt-2 ms-1">
                                                                        {{ getFrontSelectLanguage() == 'ar' ? '\\' : '/' }} {{ __('messages.plan.unlimited') }}</span>
                                                                    @else
                                                                    <span class="text-gray mt-xl-4 mt-sm-3 mt-2 ms-1">
                                                                        {{ getFrontSelectLanguage() == 'ar' ? '\\' : '/' }} {{ __('web.web_home.monthly') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="card-body-bottom py-30">
                                                                <div
                                                                    class="text d-flex align-items-center justify-content-center mb-0">
                                                                    <div class="check-box {{ getFrontSelectLanguage() == 'ar' ? 'ms-3' : 'me-3' }}">
                                                                        <i class="fa-solid fa-check text-white fs-14"></i>
                                                                    </div>
                                                                    <span
                                                                        class="">{{ $plan['allowed_jobs'] . ' ' . ($plan['allowed_jobs'] > 1 ? __('messages.plan.jobs_allowed') : __('messages.plan.job_allowed')) }}</span>
                                                                </div>
                                                                <div class="px-4 pt-2">
                                                                    @if (Auth::check() && Auth::user()->hasRole('Candidate'))
                                                                        <a href="#"
                                                                            class="btn btn-light w-100 fs-14 mt-3 py-2 px-4"
                                                                            >{{ __('messages.pricing_table.buy_plan') }}</a>
                                                                    @elseif(Auth::check() && Auth::user()->hasRole('Employer'))
                                                                        <a href="{{ route('manage-subscription.index') }}"
                                                                            class="btn btn-light w-100 fs-14 mt-3 py-2 px-4"
                                                                            >{{ __('messages.pricing_table.buy_plan') }}</a>
                                                                    @elseif(Auth::check() && Auth::user()->hasRole('Admin'))
                                                                        <a href="#"
                                                                            class="btn btn-light w-100 fs-14 mt-3 py-2 px-4 d-none"
                                                                            >{{ __('messages.pricing_table.buy_plan') }}</a>
                                                                    @else
                                                                        <a href="{{ route('employer.register') }}"
                                                                            class="btn btn-light w-100 fs-14 mt-3 py-2 px-4"
                                                                            >{{ __('messages.pricing_table.buy_plan') }}</a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($isshoebtn == 1)
                            <button class="carousel-control-prev about-control-prev" type="button"
                                data-bs-target="#carouselExampleControlsabout" data-bs-slide="prev">
                                <i class="icon fa-solid fa-arrow-left text-primary"></i>
                            </button>
                            <button class="carousel-control-next about-control-next" type="button"
                                data-bs-target="#carouselExampleControlsabout" data-bs-slide="next">
                                <i class="icon fa-solid fa-arrow-right text-primary"></i>
                            </button>
                            @endif
                        </div>
                    </section>
                </div>
            </section>
        @endif --}}
        <!-- end pricing-packages-section -->
        {{ Form::hidden('homeData', json_encode(getCountries()), ['id' => 'indexHomeData']) }}
    </div>
@endsection
