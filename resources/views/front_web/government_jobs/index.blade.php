@extends('front_web.layouts.app')
@section('title', 'Government Jobs')


@section('content')
    <div class="Find Jobs-page">
        <section class="hero-section position-relative bg-gradient pt-15 pb-40">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 text-center mb-lg-0 mb-md-5 mb-sm-4">
                        <div class="hero-content">
                            <h1 class="text-secondary mb-3">Government Jobs</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('front.home') }}" class="fs-18 text-gray">@lang('web.home')</a></li>
                                    <li class="breadcrumb-item text-primary fs-18" aria-current="page">Government Jobs</li>
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
                                        <a href="{{ route('front.government-jobs.index') }}" class="btn reset-filter">{{ __('web.reset_filter') }}</a>
                                    </div>
                                    <div class="form-group find-jobs-filter__group">
                                        <label for="governmentJobSearch">Keyword</label>
                                        <input id="governmentJobSearch" type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Job title or organization">
                                    </div>
                                    <div class="form-group find-jobs-filter__group">
                                        <label for="governmentJobOrganization">Organization</label>
                                        <select id="governmentJobOrganization" name="organization" class="form-select">
                                            <option value="">All Organizations</option>
                                            @foreach ($organizations as $organization)
                                                <option value="{{ $organization }}" @selected(request('organization') === $organization)>{{ $organization }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group find-jobs-filter__group">
                                        <label for="governmentJobSource">Source</label>
                                        <select id="governmentJobSource" name="source" class="form-select">
                                            <option value="">All Sources</option>
                                            @foreach ($sources as $source)
                                                <option value="{{ $source }}" @selected(request('source') === $source)>{{ $source }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group find-jobs-filter__group">
                                        <label for="governmentJobDeadline">Deadline</label>
                                        <select id="governmentJobDeadline" name="deadline" class="form-select">
                                            <option value="">All Jobs</option>
                                            <option value="active" @selected(request('deadline') === 'active')>Active Jobs</option>
                                            <option value="expired" @selected(request('deadline') === 'expired')>Expired Jobs</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                                </form>
                            </aside>
                        </div>

                        <div class="col-lg-8 col-12">
                            <div>
                                @forelse ($governmentJobs as $job)
                                    <a href="{{ route('front.government-jobs.show', $job) }}" class="job-search-result-card text-decoration-none">
                                        <h2 class="job-search-result-card__title">{{ $job->title }}</h2>
                                        <p class="job-search-result-card__company">{{ $job->organization_name }}</p>

                                        <div class="job-search-result-card__details">
                                            <div class="job-search-result-card__detail">
                                                <i class="fa-solid fa-newspaper" aria-hidden="true"></i>
                                                <span>{{ $job->source_name ?: 'Government Circular' }}</span>
                                            </div>
                                            <div class="job-search-result-card__detail">
                                                <i class="fa-solid fa-calendar" aria-hidden="true"></i>
                                                <span>Published: {{ $job->published_at ? $job->published_at->format('d M Y') : __('messages.n/a') }}</span>
                                            </div>
                                        </div>

                                        <div class="job-search-result-card__footer">
                                            <div class="job-search-result-card__detail">
                                                <i class="fa-solid {{ $job->is_pdf ? 'fa-file-pdf' : 'fa-image' }}" aria-hidden="true"></i>
                                                <span>{{ $job->is_pdf ? 'PDF Circular' : 'Image Circular' }}</span>
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
                                    <div class="bg-white border rounded p-5 text-center text-muted">No government job circulars found.</div>
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