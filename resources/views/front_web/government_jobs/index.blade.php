@extends('front_web.layouts.app')
@section('title', 'Government Jobs')

@section('page_css')
    <style>
        .government-job-filter .btn-primary { width: 100%; }
        .government-job-card { background: #fff; border: 1px solid #e4e9ee; border-radius: 10px; box-shadow: 0 4px 14px rgba(24, 38, 60, .06); padding: 24px; transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease; }
        .government-job-card:hover { border-color: rgba(22, 129, 24, .35); box-shadow: 0 8px 22px rgba(24, 38, 60, .1); transform: translateY(-2px); }
        .government-job-card__title { color: #dc3545; font-size: 20px; line-height: 1.4; }
        .government-job-card__organization { color: #343a40; font-size: 16px; font-weight: 600; }
        .government-job-card__meta { color: #6c757d; display: flex; flex-wrap: wrap; gap: 12px 24px; font-size: 14px; }
        .government-job-card__meta i { color: #168118; margin-right: 7px; width: 16px; }
        .government-job-card__deadline { margin-left: auto; }
        @media (max-width: 575.98px) { .government-job-card { padding: 18px; } .government-job-card__title { font-size: 18px; } .government-job-card__deadline { margin-left: 0; width: 100%; } }
    </style>
@endsection

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
                            <div class="d-flex justify-content-between align-items-center mb-4 gap-3">
                                <h2 class="fs-4 text-secondary mb-0">Latest Government Jobs</h2>
                                <span class="text-muted fs-14 flex-shrink-0">{{ $governmentJobs->total() }} jobs found</span>
                            </div>
                            <div class="d-grid gap-4">
                                @forelse ($governmentJobs as $job)
                                    <article class="government-job-card">
                                        <a href="{{ route('front.government-jobs.show', $job) }}" class="text-decoration-none">
                                            <h3 class="government-job-card__title mb-2">{{ $job->title }}</h3>
                                        </a>
                                        <div class="government-job-card__organization mb-3">{{ $job->organization_name }}</div>
                                        <div class="government-job-card__meta">
                                            @if ($job->source_name)
                                                <span><i class="fas fa-newspaper" aria-hidden="true"></i>{{ $job->source_name }}</span>
                                            @endif
                                            @if ($job->published_at)
                                                <span><i class="fas fa-calendar" aria-hidden="true"></i>Published: {{ $job->published_at->format('d M Y') }}</span>
                                            @endif
                                            @if ($job->application_deadline)
                                                <span class="government-job-card__deadline"><i class="fas fa-calendar-check" aria-hidden="true"></i><strong>Deadline:</strong> {{ $job->application_deadline->format('d M Y') }}</span>
                                            @endif
                                        </div>
                                    </article>
                                @empty
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