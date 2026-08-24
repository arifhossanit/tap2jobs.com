@php
    $profileImage = $user->avatar ?: asset('assets/img/default-user.png');
    $candidateLocation = __('messages.candidate_dashboard.location_information');
    $completionPercentage = $profileCompletion['percentage'] ?? 0;
    $completionColor = $profileCompletion['color'] ?? '#1967d2';

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

    $dashboardStats = [
        [
            'label' => __('messages.candidate_dashboard.profile_views'),
            'value' => numberFormatShort($user->profile_views),
            'icon' => 'fa-regular fa-eye',
            'tone' => 'blue',
        ],
        [
            'label' => __('messages.applied_job.applied_jobs'),
            'value' => numberFormatShort($applicationStats['applied'] ?? 0),
            'icon' => 'fa-solid fa-paper-plane',
            'tone' => 'green',
            'url' => route('candidate.applied.job'),
        ],
        [
            'label' => 'Ongoing',
            'value' => numberFormatShort($applicationStats['ongoing'] ?? 0),
            'icon' => 'fa-solid fa-list-check',
            'tone' => 'purple',
            'url' => route('candidate.applied.job'),
        ],
        [
            'label' => __('messages.apply_job.resume'),
            'value' => numberFormatShort($resumes),
            'icon' => 'fa-regular fa-file-lines',
            'tone' => 'amber',
            'url' => route('candidate.profile', ['section' => 'resume']),
        ],
    ];
@endphp

<div class="content flex-column-fluid">
    <div class="candidate-dashboard">
        <section class="candidate-dashboard-hero">
            <div class="candidate-dashboard-hero__profile">
                <div class="candidate-dashboard-avatar">
                    <img src="{{ $profileImage }}" alt="{{ html_entity_decode($user->full_name) }}">
                </div>
                <div class="candidate-dashboard-identity">
                    <span class="candidate-dashboard-kicker">Candidate Dashboard</span>
                    <h1>{{ html_entity_decode($user->full_name) }}</h1>
                    <div class="candidate-dashboard-meta">
                        <span><i class="fa-solid fa-phone"></i>{{ ! empty($user->phone) ? $user->phone : __('messages.candidate_dashboard.no_not_available') }}</span>
                        <span><i class="fa-solid fa-location-dot"></i>{{ $candidateLocation }}</span>
                        <span><i class="fa-solid fa-envelope"></i>{{ $user->email }}</span>
                    </div>
                    <div class="candidate-dashboard-actions">
                        <a href="{{ route('candidate.profile') }}" class="btn btn-primary">
                            <i class="fa-regular fa-pen-to-square"></i>{{ __('messages.user.edit_profile') }}
                        </a>
                        <a href="{{ route('front.search.jobs') }}" target="_blank" class="btn btn-outline-primary">
                            <i class="fa-solid fa-magnifying-glass"></i>Browse Jobs
                        </a>
                    </div>
                </div>
            </div>

            <div class="candidate-profile-completion">
                <div class="candidate-profile-completion__ring"
                     style="--completion: {{ $completionPercentage }}%; --completion-color: {{ $completionColor }};">
                    <span>{{ $completionPercentage }}%</span>
                </div>
                <div class="candidate-profile-completion__content">
                    <span>Profile Completed</span>
                    <strong>{{ $profileCompletion['completed'] ?? 0 }} of {{ $profileCompletion['total'] ?? 10 }} sections done</strong>
                    <a href="{{ route('candidate.profile') }}">Improve profile</a>
                </div>
            </div>
        </section>

        <section class="candidate-dashboard-stats">
            @foreach($dashboardStats as $stat)
                @if(isset($stat['url']))
                    <a href="{{ $stat['url'] }}" class="candidate-stat-card candidate-stat-card--{{ $stat['tone'] }}">
                        <span class="candidate-stat-card__icon"><i class="{{ $stat['icon'] }}"></i></span>
                        <span class="candidate-stat-card__content">
                            <strong>{{ $stat['value'] }}</strong>
                            <small>{{ $stat['label'] }}</small>
                        </span>
                    </a>
                @else
                    <div class="candidate-stat-card candidate-stat-card--{{ $stat['tone'] }}">
                        <span class="candidate-stat-card__icon"><i class="{{ $stat['icon'] }}"></i></span>
                        <span class="candidate-stat-card__content">
                            <strong>{{ $stat['value'] }}</strong>
                            <small>{{ $stat['label'] }}</small>
                        </span>
                    </div>
                @endif
            @endforeach
        </section>

        <section class="candidate-dashboard-main">
            <div class="candidate-dashboard-panel candidate-dashboard-panel--wide">
                <div class="candidate-dashboard-panel__header">
                    <div>
                        <h2>Matching Jobs</h2>                        
                    </div>
                    <a href="{{ route('front.search.jobs') }}" target="_blank" class="candidate-dashboard-link">
                        View More Jobs <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                @if($matchingJobs->isNotEmpty())
                    <div class="candidate-match-grid">
                        @foreach($matchingJobs as $job)
                            @php
                                $companyName = html_entity_decode($job->company->user->full_name ?? $job->company->ceo ?? __('messages.company.company'));
                                $jobUrl = route('front.job.details', $job->job_id);
                            @endphp
                            <article class="candidate-match-card">
                                <div class="candidate-match-card__top">
                                    <a href="{{ $jobUrl }}" target="_blank" class="candidate-match-logo" aria-label="{{ $companyName }}">
                                        <img src="{{ $job->company->company_url }}" alt="{{ $companyName }}">
                                    </a>
                                    <div class="candidate-match-heading">
                                        <a href="{{ $jobUrl }}" target="_blank" class="candidate-match-title">
                                            {{ html_entity_decode(\Illuminate\Support\Str::limit($job->job_title, 58)) }}
                                        </a>
                                        <span>{{ $companyName }}</span>
                                    </div>
                                </div>
                                <div class="candidate-match-card__body">
                                    <div class="candidate-match-meta">
                                        <span><i class="fa-solid fa-location-dot"></i>{{ $job->full_location ?: __('messages.candidate_dashboard.location_information') }}</span>
                                        <span><i class="fa-solid fa-briefcase"></i>{{ $job->functionalArea->name ?? $job->jobCategory->name ?? __('messages.common.n/a') }}</span>
                                    </div>
                                </div>
                                <div class="candidate-match-card__footer">
                                    <span><i class="fa-regular fa-calendar"></i>{{ optional($job->job_expiry_date)->format('M d, Y') }}</span>
                                    <a href="{{ $jobUrl }}" target="_blank">View Details</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="candidate-match-empty">
                        <i class="fa-solid fa-briefcase"></i>
                        <h3>No matching jobs found</h3>
                        <p>Complete your skills, preferred area and location to get better recommendations.</p>
                        <a href="{{ route('candidate.profile') }}" class="btn btn-sm btn-primary">{{ __('messages.user.edit_profile') }}</a>
                    </div>
                @endif
            </div>

            <aside class="candidate-dashboard-panel candidate-dashboard-side-panel">
                <div class="candidate-dashboard-panel__header candidate-dashboard-panel__header--compact">
                    <div>
                        <h2>Quick Overview</h2>
                        <p>Your application activity at a glance.</p>
                    </div>
                </div>
                <div class="candidate-overview-list">
                    <a href="{{ route('candidate.applied.job') }}">
                        <span><i class="fa-solid fa-circle-check"></i>Applied</span>
                        <strong>{{ numberFormatShort($applicationStats['applied'] ?? 0) }}</strong>
                    </a>
                    <a href="{{ route('candidate.applied.job') }}">
                        <span><i class="fa-solid fa-clock"></i>Drafts</span>
                        <strong>{{ numberFormatShort($applicationStats['drafts'] ?? 0) }}</strong>
                    </a>
                    <a href="{{ route('candidate.applied.job') }}">
                        <span><i class="fa-solid fa-star"></i>Hired</span>
                        <strong>{{ numberFormatShort($applicationStats['hired'] ?? 0) }}</strong>
                    </a>
                    <a href="{{ route('favourite.companies') }}">
                        <span><i class="fa-solid fa-users"></i>{{ __('messages.candidate_dashboard.followings') }}</span>
                        <strong>{{ numberFormatShort($followings) }}</strong>
                    </a>
                </div>
            </aside>
        </section>
    </div>
</div>
