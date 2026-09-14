@extends('front_web.layouts.app')
@section('title', $governmentJob->title)
@section('content')
    <section class="py-5 bg-light">
        <div class="container-fluid">
            @php
                $leftAds = getActiveAdsByPosition(
                    \App\Models\Ad::POSITION_REGISTER_LEFT,
                    \App\Models\Ad::PAGE_GOVERNMENT_JOB_DETAILS
                );
                $rightAds = getActiveAdsByPosition(
                    \App\Models\Ad::POSITION_REGISTER_RIGHT,
                    \App\Models\Ad::PAGE_GOVERNMENT_JOB_DETAILS
                );
                $hasSideAds = $leftAds->isNotEmpty() || $rightAds->isNotEmpty();
            @endphp

            <div class="row g-4 align-items-start justify-content-center front-auth-ad-layout">
                @if ($hasSideAds)
                    <aside class="col-xl-3 col-lg-3 d-none d-lg-block mb-4 text-start front-auth-side-ads front-auth-side-ads--left">
                        <div>
                            @include('front_web.common.register_side_ad', ['ads' => $leftAds])
                        </div>
                    </aside>
                    <div class="col-xl-6 col-lg-6">
                @else
                    <div class="col-xl-10 col-lg-10 mx-auto">
                @endif
                    <article class="bg-white border rounded shadow-sm p-3 p-md-4">
                        <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center border-bottom pb-3 mb-4">
                            <a href="{{ route('front.government-jobs.index') }}" class="text-decoration-none">
                                <i class="fas fa-chevron-left me-2"></i>Government Job List
                            </a>
                            <div class="text-md-end fs-14">
                                <strong>Source:</strong> {{ $governmentJob->source_name ?: 'Government Circular' }}
                                @if ($governmentJob->published_at)
                                    ({{ $governmentJob->published_at->format('l, F d, Y') }})
                                @endif
                            </div>
                        </div>

                        <header class="mb-4">
                            <h1 class="fs-3 text-success mb-2">{{ $governmentJob->title }}</h1>
                            <div class="fs-5 fw-semibold text-gray-700">{{ $governmentJob->organization_name }}</div>
                        </header>

                        <div class="text-center">
                            @if ($governmentJob->is_pdf)
                                <iframe src="{{ $governmentJob->circular_url }}" title="{{ $governmentJob->title }}"
                                        style="width:100%;height:80vh;min-height:650px;border:1px solid #d9dee3"></iframe>
                            @else
                                <img src="{{ $governmentJob->circular_url }}" alt="{{ $governmentJob->title }}"
                                     class="img-fluid border" style="width:100%;height:auto;object-fit:contain">
                            @endif
                        </div>

                        <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center border-top pt-4 mt-4">
                            <div>
                                @if ($governmentJob->application_deadline)
                                    <strong>Application Deadline:</strong>
                                    {{ $governmentJob->application_deadline->format('d M Y') }}
                                @endif
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ $governmentJob->circular_url }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="fas fa-download me-2"></i>Open Circular
                                </a>
                                {{-- @if ($governmentJob->application_url)
                                    <a href="{{ $governmentJob->application_url }}" target="_blank" rel="noopener"
                                       class="btn btn-primary">
                                        Apply Online <i class="fas fa-arrow-up-right-from-square ms-2"></i>
                                    </a>
                                @endif --}}
                            </div>
                        </div>
                    </article>
                </div>

                @if ($hasSideAds)
                    <aside class="col-xl-3 col-lg-3 d-none d-lg-block mb-4 text-end front-auth-side-ads front-auth-side-ads--right">
                        <div>
                            @include('front_web.common.register_side_ad', ['ads' => $rightAds])
                        </div>
                    </aside>
                    <div class="col-12 d-lg-none mt-4">
                        <div class="row">
                            @if ($leftAds->isNotEmpty())
                                <div class="col-sm-6 mb-3">
                                    @include('front_web.common.register_side_ad', ['ads' => $leftAds])
                                </div>
                            @endif
                            @if ($rightAds->isNotEmpty())
                                <div class="col-sm-6 mb-3">
                                    @include('front_web.common.register_side_ad', ['ads' => $rightAds])
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
