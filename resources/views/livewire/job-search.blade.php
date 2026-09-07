<div class="row">
    @forelse($jobs as $job)
        <div class="col-lg-12 px-lg-3">
            @php
                $companyName = getFrontSelectLanguage() === 'bn' && filled($job->company?->company_name_bn)
                    ? $job->company->company_name_bn
                    : ($job->company?->company_name ?: $job->company?->user?->full_name);
                $education = collect([$job->degreeLevel?->name, $job->degreeTitle?->name])
                    ->filter()
                    ->unique()
                    ->implode(', ');
            @endphp
            <a href="{{ route('front.job.details', $job->job_id) }}"
               class="job-search-result-card text-decoration-none">
                <h2 class="job-search-result-card__title">{{ html_entity_decode($job->job_title) }}</h2>
                <p class="job-search-result-card__company">{{ $companyName ?: __('messages.n/a') }}</p>

                <div class="job-search-result-card__details">
                    <div class="job-search-result-card__detail">
                        <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                        <span>{{ $job->district_thana_location ?: __('messages.n/a') }}</span>
                    </div>
                    <div class="job-search-result-card__detail">
                        <i class="fa-solid fa-graduation-cap" aria-hidden="true"></i>
                        <span>{{ $education ?: __('messages.n/a') }}</span>
                    </div>
                </div>

                <div class="job-search-result-card__footer">
                    <div class="job-search-result-card__detail">
                        <i class="fa-solid fa-briefcase" aria-hidden="true"></i>
                        <span>{{ $job->formatted_experience ?: __('messages.n/a') }}</span>
                    </div>
                    <div class="job-search-result-card__deadline">
                        <span>{{ __('messages.job.deadline') }}:</span>
                        <i class="fa-solid fa-calendar-day" aria-hidden="true"></i>
                        <time datetime="{{ $job->job_expiry_date->toDateString() }}">
                            {{ $job->job_expiry_date->translatedFormat('d M Y') }}
                        </time>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-md-12 text-center text-gray">
            @lang('web.job_menu.no_results_found')
        </div>
    @endforelse
    @if($jobs->hasPages())
        {{ $jobs->links() }}
    @endif
</div>
