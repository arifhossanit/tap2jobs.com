@extends('front_web.layouts.app')
@section('title', __('web.government_jobs.title'))


@section('content')
    @php
        $frontRoutePrefix = request()->segment(1) === 'bn' ? 'bn.' : '';
    @endphp
    <div class="Find Jobs-page">
        <section class="hero-section position-relative bg-gradient pt-15 pb-40">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 text-center mb-lg-0 mb-md-5 mb-sm-4">
                        <div class="hero-content">
                            <h1 class="text-secondary mb-3">{{ __('web.government_jobs.title') }}</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route($frontRoutePrefix.'front.home') }}" class="fs-18 text-gray">@lang('web.home')</a></li>
                                    <li class="breadcrumb-item text-primary fs-18" aria-current="page">{{ __('web.government_jobs.title') }}</li>
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
                    <div class="row g-4 align-items-start">
                        <div class="col-lg-4 col-12 find-jobs-filter-column">
                            <button class="find-jobs-filter-mobile-toggle d-lg-none" type="button" aria-expanded="false" aria-controls="findJobsFilter">
                                <span><i class="fa-solid fa-sliders" aria-hidden="true"></i>{{ __('messages.common.filters') }}</span>
                                <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                            </button>
                            <aside class="latest-job-left find-jobs-filter government-job-filter" id="findJobsFilter">
                                <form method="GET" class="find-jobs-filter__form">
                                    <div class="find-jobs-filter__header">
                                        <h2><i class="fa-solid fa-sliders" aria-hidden="true"></i>{{ __('messages.common.filters') }}</h2>
                                        <a href="{{ route($frontRoutePrefix.'front.government-jobs.index') }}" class="btn reset-filter">{{ __('web.reset_filter') }}</a>
                                    </div>
                                    <div class="form-group find-jobs-filter__group">
                                        <label for="governmentJobSearch">{{ __('web.government_jobs.keyword') }}</label>
                                        <input id="governmentJobSearch" type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="{{ __('web.government_jobs.keyword_placeholder') }}">
                                    </div>
                                    <div class="form-group find-jobs-filter__group">
                                        <label for="governmentJobOrganization">{{ __('web.government_jobs.organization') }}</label>
                                        <select id="governmentJobOrganization" name="organization" class="form-select">
                                            <option value="">{{ __('web.government_jobs.all_organizations') }}</option>
                                            @foreach ($organizations as $organization)
                                                <option value="{{ $organization }}" @selected(request('organization') === $organization)>{{ $organization }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group find-jobs-filter__group">
                                        <label for="governmentJobSource">{{ __('web.government_jobs.source') }}</label>
                                        <select id="governmentJobSource" name="source" class="form-select">
                                            <option value="">{{ __('web.government_jobs.all_sources') }}</option>
                                            @foreach ($sources as $source)
                                                <option value="{{ $source }}" @selected(request('source') === $source)>{{ $source }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group find-jobs-filter__group">
                                        <label for="governmentJobDeadline">{{ __('messages.job.deadline') }}</label>
                                        <select id="governmentJobDeadline" name="deadline" class="form-select">
                                            <option value="">{{ __('web.government_jobs.all_jobs') }}</option>
                                            <option value="active" @selected(request('deadline') === 'active')>{{ __('web.government_jobs.active_jobs') }}</option>
                                            <option value="expired" @selected(request('deadline') === 'expired')>{{ __('web.government_jobs.expired_jobs') }}</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{ __('web.government_jobs.apply_filter') }}</button>
                                </form>
                            </aside>
                        </div>

                        <div class="col-lg-8 col-12">
                            <div>
                                @forelse ($governmentJobs as $job)
                                    <a href="{{ route($frontRoutePrefix.'front.government-jobs.show', $job) }}" class="job-search-result-card text-decoration-none">
                                        <h2 class="job-search-result-card__title">{{ $job->title }}</h2>
                                        <p class="job-search-result-card__company">{{ $job->organization_name }}</p>

                                        <div class="job-search-result-card__details">
                                            <div class="job-search-result-card__detail">
                                                <i class="fa-solid fa-newspaper" aria-hidden="true"></i>
                                                <span>{{ $job->source_name ?: __('web.government_jobs.circular') }}</span>
                                            </div>
                                            <div class="job-search-result-card__detail">
                                                <i class="fa-solid fa-calendar" aria-hidden="true"></i>
                                                <span>{{ __('web.government_jobs.published') }}: {{ $job->published_at ? $job->published_at->format('d M Y') : __('messages.n/a') }}</span>
                                            </div>
                                        </div>

                                        <div class="job-search-result-card__footer">
                                            <div class="job-search-result-card__detail">

                                            </div>
                                            <div class="job-search-result-card__deadline">
                                                <span>{{ __('messages.job.deadline') }}:</span>
                                                <i class="fa-solid fa-calendar-day" aria-hidden="true"></i>
                                                @if ($job->application_deadline)
                                                    <time datetime="{{ $job->application_deadline->toDateString() }}">{{ $job->application_deadline->format('d M Y') }}</time>
                                                @else
                                                    <span>{{ __('messages.n/a') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>                                @empty
                                    <div class="bg-white border rounded p-5 text-center text-muted">{{ __('web.government_jobs.empty') }}</div>
                                @endforelse
                            </div>
                            @if ($governmentJobs->hasPages())
                                <div class="mt-4">{{ $governmentJobs->onEachSide(1)->links() }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </x-front.side-ad-layout>
        </section>
    </div>
@endsection
