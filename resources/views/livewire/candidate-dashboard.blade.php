@php
    $profileImage = $user->avatar ?: asset('assets/img/infyom-logo.png');
    $candidateLocation = __('messages.candidate_dashboard.location_information');

    if ($candidate) {
        if (! empty($candidate->city_name)) {
            $candidateLocation = collect([
                $candidate->city_name,
                $candidate->state_name,
                $candidate->country_name,
            ])->filter()->implode(', ');
        } elseif (! empty($candidate->country_id)) {
            $candidateLocation = $candidate->country_name ?: $candidateLocation;
        }
    }
@endphp

<div class="content flex-column-fluid">
    <div class="candidate-dashboard">
        <div class="card candidate-dashboard-profile-card">
            <div class="card-body py-7">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                    <div class="me-7 mb-4">
                        <div class="candidate-dashboard-avatar">
                            <img height="150" width="150" src="{{ $profileImage }}" alt="{{ html_entity_decode($user->full_name) }}"
                                style="object-fit: cover">
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-4 mb-2">
                            <div class="d-flex flex-column">
                                <div class="align-items-center mb-2">
                                    <a
                                        class="text-gray-900 text-hover-primary fs-2 me-1 text-decoration-none">{{ html_entity_decode($user->full_name) }}</a>
                                </div>
                                <div class=" flex-wrap fs-6 mb-4 pe-2">
                                    <a
                                        class="d-flex align-items-center text-gray-600 text-hover-primary {{ checkLanguageSession() == 'ar' ? 'ms-5' : 'me-5' }} mb-2 text-decoration-none">
                                        <i class="fa fa-phone"></i>&nbsp;
                                        {{ !empty($user->phone) ? $user->phone : __('messages.candidate_dashboard.no_not_available') }}
                                    </a>
                                    <a
                                        class="d-flex align-items-center text-gray-600 text-hover-primary {{ checkLanguageSession() == 'ar' ? 'ms-5' : 'me-5' }} mb-2 text-decoration-none">
                                        <i class="fa-solid fa-location-dot fs-3 {{ checkLanguageSession() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                        {{ $candidateLocation }}
                                    </a>
                                    <a
                                        class="d-flex align-items-center text-gray-600 text-hover-primary {{ checkLanguageSession() == 'ar' ? 'ms-5' : 'me-5' }} mb-2 text-decoration-none">
                                        <i class="fa-solid fa-envelope  {{ checkLanguageSession() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                        {{ $user->email }}</a>
                                </div>
                            </div>
                            <div class="candidate-profile-completion my-2">
                                <div class="candidate-profile-completion__ring"
                                     style="--completion: {{ $profileCompletion['percentage'] ?? 0 }}%; --completion-color: {{ $profileCompletion['color'] ?? '#1967d2' }};">
                                    <span>{{ $profileCompletion['percentage'] ?? 0 }}%</span>
                                </div>
                                <div class="candidate-profile-completion__content">
                                    <span>Profile Filled</span>
                                    <strong>{{ $profileCompletion['completed'] ?? 0 }}/{{ $profileCompletion['total'] ?? 10 }} Completed</strong>
                                    <a href="{{ route('candidate.profile') }}">{{ __('messages.user.edit_profile') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-5 g-xl-8">
            <div class="col-xxl-4 col-xl-4 col-sm-6 widget">
                <div
                    class="bg-success shadow-md rounded-10 p-xxl-10 px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                    <div
                        class="bg-green-300 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-eye text-white fs-1-xl"></i>
                    </div>
                    <div class="text-end text-white">
                        <h2 class="fs-1-xxl text-white {{ checkLanguageSession() == 'ar' ? 'text-start' : 'text-end' }}">{{ numberFormatShort($user->profile_views) }}</h2>
                        <h3 class="mb-0 fs-4 fw-light fs-1-xl">{{ __('messages.candidate_dashboard.profile_views') }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-4 col-sm-6 widget">
                <a href="{{ route('favourite.companies') }}" class=" text-decoration-none">
                    <div
                        class="bg-dark shadow-md rounded-10 p-xxl-10 px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                        <div
                            class="bg-gray-700 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                            <i
                                class="fas fa-users  fs-1-xl {{ getLoggedInUser()->theme_mode ? 'text-muted' : 'text-white' }}"></i>
                        </div>
                        <div class="text-end text-light">
                            <h2 class="fs-1-xxl text-light {{ checkLanguageSession() == 'ar' ? 'text-start' : 'text-end' }}">{{ numberFormatShort($followings) }}</h2>
                            <h3 class="mb-0 fs-4 fw-light fs-1-xl">{{ __('messages.candidate_dashboard.followings') }}
                            </h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xxl-4 col-xl-4 col-sm-6 widget">
                <div
                    class="bg-warning shadow-md rounded-10 p-xxl-10 px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                    <div
                        class="bg-yellow-300 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-briefcase fs-1-xl text-white"></i>
                    </div>
                    <div class="text-end text-white">
                        <h2 class="fs-1-xxl text-white {{ checkLanguageSession() == 'ar' ? 'text-start' : 'text-end' }}">{{ numberFormatShort($resumes) }}</h2>
                        <h3 class="mb-0 fs-4 fw-light">{{ __('messages.apply_job.resume') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="card candidate-matching-jobs-card mt-7">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-5">
                    <div>
                        <h3 class="candidate-section-title mb-1">Matching Jobs</h3>
                        {{-- <p class="candidate-section-subtitle mb-0">Recommended jobs based on your candidate profile.</p> --}}
                    </div>
                    <a href="{{ route('front.search.jobs') }}" target="_blank" class="btn btn-sm btn-primary">
                        View More Jobs
                    </a>
                </div>

                @if($matchingJobs->isNotEmpty())
                    <div class="candidate-match-grid">
                        @foreach($matchingJobs as $job)
                            <article class="candidate-match-card">
                                <div class="candidate-match-card__top candidate-match-card__top--simple">
                                    <a href="{{ route('front.job.details', $job->job_id) }}" target="_blank"
                                       class="candidate-match-logo" aria-label="{{ html_entity_decode($job->company->user->full_name ?? $job->company->ceo ?? $job->job_title) }}">
                                        <img src="{{ $job->company->company_url }}" alt="{{ html_entity_decode($job->company->user->full_name ?? $job->job_title) }}">
                                    </a>
                                    <div class="candidate-match-heading">
                                        <a href="{{ route('front.job.details', $job->job_id) }}" target="_blank"
                                           class="candidate-match-title">
                                            {{ html_entity_decode(\Illuminate\Support\Str::limit($job->job_title, 54)) }}
                                        </a>
                                        <div class="candidate-match-company">
                                            {{ html_entity_decode($job->company->user->full_name ?? $job->company->ceo ?? __('messages.company.company')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="candidate-match-card__body">
                                    <div class="candidate-match-meta">
                                        <span><i class="fa-solid fa-location-dot"></i>{{ $job->full_location ?: __('messages.candidate_dashboard.location_information') }}</span>
                                        <span><i class="fa-solid fa-briefcase"></i>{{ $job->functionalArea->name ?? $job->jobCategory->name ?? __('messages.common.n/a') }}</span>
                                    </div>
                                </div>
                                <div class="candidate-match-card__footer">
                                    <span>{{ optional($job->job_expiry_date)->format('M d, Y') }}</span>
                                    <a href="{{ route('front.job.details', $job->job_id) }}" target="_blank">View Details</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="candidate-match-empty">
                        <i class="fa-solid fa-briefcase"></i>
                        <h4>No matching jobs found</h4>
                        <p>Complete your skills, preferred area and location to get better recommendations.</p>
                        <a href="{{ route('candidate.profile') }}" class="btn btn-sm btn-primary">{{ __('messages.user.edit_profile') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
