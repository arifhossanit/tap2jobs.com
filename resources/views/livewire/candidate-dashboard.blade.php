@php
    $profileImage = $user->avatar ?: asset('assets/img/default-user.png');
    $candidateLocation = __('messages.candidate_dashboard.location_information');
    $completionPercentage = $profileCompletion['percentage'] ?? 0;
    $completionColor = $profileCompletion['color'] ?? '#1967d2';
    $dashboardScoringBars = [
        ['label' => 'Completed profile', 'percent' => $completionPercentage],
        ['label' => 'Skill', 'percent' => max(0, $completionPercentage - 12)],
        ['label' => 'Experience', 'percent' => max(0, $completionPercentage - 24)],
        ['label' => 'Education', 'percent' => max(0, $completionPercentage - 36)],
        ['label' => 'Location', 'percent' => max(0, $completionPercentage - 48)],
    ];
    $dashboardNumber = function ($value) {
        $value = (string) $value;

        if (app()->getLocale() !== 'bn') {
            return $value;
        }

        return strtr($value, [
            '0' => '০',
            '1' => '১',
            '2' => '২',
            '3' => '৩',
            '4' => '৪',
            '5' => '৫',
            '6' => '৬',
            '7' => '৭',
            '8' => '৮',
            '9' => '৯',
        ]);
    };
    $dashboardCount = fn ($value) => $dashboardNumber(numberFormatShort($value));
    $completionPercentageLabel = $dashboardNumber($completionPercentage).'%';
    $candidatePhone = ! empty($user->phone)
        ? $dashboardNumber($user->phone)
        : __('messages.candidate_dashboard.no_not_available');

    if ($candidate) {
        $candidateLocation = collect([
            $candidate->thana_name,
            $candidate->city_name,
            $candidate->state_name,
            $candidate->country_name,
        ])->filter()->implode(', ') ?: $candidateLocation;
    }

    $dashboardStats = [
        [
            'label' => __('messages.candidate_dashboard.profile_views'),
            'value' => $dashboardCount($user->profile_views),
            'icon' => 'fa-regular fa-eye',
            'tone' => 'blue',
        ],
        [
            'label' => __('messages.applied_job.applied_jobs'),
            'value' => $dashboardCount($applicationStats['total'] ?? 0),
            'icon' => 'fa-solid fa-paper-plane',
            'tone' => 'green',
            'url' => route('candidate.applied.job'),
        ],
        [
            'label' => __('messages.candidate_dashboard.ongoing'),
            'value' => $dashboardCount($applicationStats['ongoing'] ?? 0),
            'icon' => 'fa-solid fa-list-check',
            'tone' => 'purple',
            'url' => route('candidate.applied.job', ['status' => \App\Models\JobApplication::SHORT_LIST]),
        ],
        [
            'label' => __('messages.apply_job.resume'),
            'value' => $dashboardCount($resumes),
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
                    <h1>{{ html_entity_decode($user->full_name) }}</h1>
                    <div class="candidate-dashboard-meta">
                        <span><i class="fa-solid fa-phone"></i>{{ $candidatePhone }}</span>
                        
                        <span><i class="fa-solid fa-envelope"></i>{{ $user->email }}</span>
                    </div>
                    {{-- <div class="candidate-dashboard-actions">
                        <a href="{{ route('candidate.profile') }}" class="btn btn-primary">
                            <i class="fa-regular fa-pen-to-square"></i>{{ __('messages.user.edit_profile') }}
                        </a>
                        <a href="{{ route('front.search.jobs') }}" target="_blank" class="btn btn-outline-primary">
                            <i class="fa-solid fa-magnifying-glass"></i>Browse Jobs
                        </a>
                    </div> --}}
                </div>
            </div>

            <div class="candidate-profile-completion">
                <div class="candidate-profile-completion__ring"
                     style="--completion: {{ $completionPercentage }}%; --completion-color: {{ $completionColor }};">
                    <span>{{ $completionPercentageLabel }}</span>
                </div>
                <div class="candidate-profile-completion__content">
                    <span>{{ __('messages.candidate_dashboard.profile_completed') }}</span>
                    <strong>{{ __('messages.candidate_dashboard.sections_completed', ['completed' => $dashboardNumber($profileCompletion['completed'] ?? 0), 'total' => $dashboardNumber($profileCompletion['total'] ?? 10)]) }}</strong>
                    <a href="{{ route('candidate.profile') }}">{{ __('messages.candidate_dashboard.edit_profile') }}</a>
                </div>
            </div>
        </section>

        <section class="candidate-dashboard-stats">
            @foreach($dashboardStats as $stat)
                @if(isset($stat['url']))
                    <a href="{{ $stat['url'] }}" class="candidate-stat-card candidate-stat-card--{{ $stat['tone'] }}" title="{{ $stat['label'] }}">
                        <span class="candidate-stat-card__icon"><i class="{{ $stat['icon'] }}"></i></span>
                        <span class="candidate-stat-card__content">
                            <strong>{{ $stat['value'] }}</strong>
                            <small>{{ $stat['label'] }}</small>
                        </span>
                    </a>
                @else
                    <div class="candidate-stat-card candidate-stat-card--{{ $stat['tone'] }}" title="{{ $stat['label'] }}">
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
                <div class="candidate-dashboard-panel__header candidate-dashboard-panel__header--matching">
                    <div>
                        <h2>{{ __('messages.candidate_dashboard.matching_jobs') }}</h2>
                    </div>
                    <a href="{{ route('front.search.jobs', ['matching' => 1]) }}" target="_blank" class="candidate-dashboard-link">
                        {{ __('messages.candidate_dashboard.view_more_jobs') }} <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                @if($matchingJobs->isNotEmpty())
                    <div class="candidate-match-grid">
                        @foreach($matchingJobs as $job)
                            @php
                                $companyName = html_entity_decode($job->company->user->full_name ?? $job->company->ceo ?? __('messages.company.company'));
                                $jobUrl = route('front.job.details', $job->job_id);
                                $matchScore = (int) ($job->match_score ?? 0);
                                $matchColor = $matchScore >= 75 ? '#00ba63' : ($matchScore >= 45 ? '#1967d2' : '#f59e0b');
                                $matchScoreLabel = $dashboardNumber($matchScore).'%';
                                $circumference = 138.23;
                                $dashOffset = round($circumference - ($circumference * ($matchScore / 100)), 2);
                            @endphp
                            <article class="candidate-match-card">
                                <div class="candidate-match-card__top align-items-center">
                                    <a href="{{ $jobUrl }}" target="_blank" class="candidate-match-logo" aria-label="{{ $companyName }}">
                                        <img src="{{ $job->company->company_url }}" alt="{{ $companyName }}">
                                    </a>
                                    <div class="candidate-match-heading flex-grow-1">
                                        <a href="{{ $jobUrl }}" target="_blank" class="candidate-match-title">
                                            {{ html_entity_decode(\Illuminate\Support\Str::limit($job->job_title, 48)) }}
                                        </a>
                                        <span>{{ $companyName }}</span>
                                    </div>
                                    @if($matchScore > 0)
                                        <div class="candidate-job-match-ring ms-2 flex-shrink-0" title="{{ $matchScoreLabel }} Match">
                                            <svg width="54" height="54" viewBox="0 0 54 54">
                                                <circle class="ring-bg" cx="27" cy="27" r="22" fill="#ffffff" stroke="#eef2f6" stroke-width="7" style="fill: #ffffff !important; stroke: #eef2f6 !important;" />
                                                <circle cx="27" cy="27" r="22" stroke="{{ $matchColor }}" stroke-width="7" fill="none"
                                                        stroke-linecap="round"
                                                        stroke-dasharray="{{ $circumference }}"
                                                        stroke-dashoffset="{{ $dashOffset }}"
                                                        transform="rotate(-90 27 27)"
                                                        style="fill: none !important;" />
                                                <text class="ring-text" x="27" y="27" text-anchor="middle" dominant-baseline="central" style="fill: #0b132a !important; font-size: 13px; font-weight: 900;">{{ $matchScoreLabel }}</text>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                @if(!empty($job->match_reasons) && $matchScore > 0)
                                    <div class="candidate-match-card__body py-2 px-3 border-bottom-0">
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($job->match_reasons as $reason)
                                                <span class="badge bg-light text-muted border fs-8 fw-normal" style="font-size: 11px;">{{ $reason }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <div class="candidate-match-card__footer">
                                    <span><i class="fa-regular fa-calendar"></i>{{ $job->job_expiry_date ? $dashboardNumber($job->job_expiry_date->translatedFormat('M d, Y')) : '' }}</span>
                                    <a href="{{ $jobUrl }}" target="_blank">{{ __('messages.candidate_dashboard.view_details') }}</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="candidate-match-empty">
                        <i class="fa-solid fa-briefcase"></i>
                        <h3>{{ __('messages.candidate_dashboard.no_matching_jobs_found') }}</h3>
                        <p>{{ __('messages.candidate_dashboard.complete_profile_for_recommendations') }}</p>
                        <a href="{{ route('candidate.profile') }}" class="btn btn-sm btn-primary">{{ __('messages.user.edit_profile') }}</a>
                    </div>
                @endif
            </div>

            <div class="candidate-dashboard-side-stack">
                <aside class="candidate-dashboard-panel candidate-dashboard-side-panel">
                    <div class="candidate-dashboard-panel__header candidate-dashboard-panel__header--compact">
                        <div>
                            <h2>{{ __('messages.candidate_dashboard.quick_overview') }}</h2>
                        </div>
                    </div>
                    <div class="candidate-overview-list">
                        <a href="{{ route('candidate.applied.job') }}">
                            <span><i class="fa-solid fa-circle-check"></i>{{ __('messages.applied_job.applied_jobs') }}</span>
                            <strong>{{ $dashboardCount($applicationStats['total'] ?? 0) }}</strong>
                        </a>
                        <a href="{{ route('candidate.applied.job', ['status' => \App\Models\JobApplication::STATUS_DRAFT]) }}">
                            <span><i class="fa-solid fa-clock"></i>{{ __('messages.candidate_dashboard.drafts') }}</span>
                            <strong>{{ $dashboardCount($applicationStats['drafts'] ?? 0) }}</strong>
                        </a>
                        <a href="{{ route('candidate.applied.job', ['status' => \App\Models\JobApplication::COMPLETE]) }}">
                            <span><i class="fa-solid fa-star"></i>{{ __('messages.candidate_dashboard.hired') }}</span>
                            <strong>{{ $dashboardCount($applicationStats['hired'] ?? 0) }}</strong>
                        </a>
                        <a href="{{ route('favourite.companies') }}">
                            <span><i class="fa-solid fa-users"></i>{{ __('messages.candidate_dashboard.followings') }}</span>
                            <strong>{{ $dashboardCount($followings) }}</strong>
                        </a>
                    </div>
                </aside>

                <aside class="candidate-dashboard-panel candidate-dashboard-side-panel">
                    <div class="candidate-profile-breakdown">
                        <div class="candidate-dashboard-panel__header candidate-dashboard-panel__header--compact">
                            <div>
                                <h2>Job Matching Criteria</h2>
                            </div>
                        </div>
                        <div class="candidate-profile-breakdown__list">
                            @foreach($dashboardScoringBars as $scoringBar)
                                @php
                                    $barPercent = min(100, max(0, (int) $scoringBar['percent']));
                                @endphp
                                <div class="candidate-profile-breakdown__row">
                                    <div class="candidate-profile-breakdown__top">
                                        <span>{{ $scoringBar['label'] }}</span>
                                    </div>
                                    <div class="candidate-profile-breakdown__track">
                                        <span style="width: {{ $barPercent }}%;"></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </section>
    </div>
</div>
