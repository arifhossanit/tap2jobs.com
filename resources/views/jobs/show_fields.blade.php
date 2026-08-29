@php
    $company = $job->company;
    $companyUser = optional($company)->user;
    $companyName = $companyUser && $companyUser->full_name
        ? html_entity_decode($companyUser->full_name)
        : __('messages.n/a');
    $jobTitle = html_entity_decode($job->job_title);
    $location = collect([
        !empty($job->thana_id) ? $job->thana_name : null,
        !empty($job->city_id) ? $job->city_name : null,
        !empty($job->state_id) ? $job->state_name : null,
        !empty($job->country_id) ? $job->country_name : null,
    ])->filter()->implode(', ');
    $salaryRange = $job->hide_salary
        ? 'Negotiable'
        : numberFormatShort($job->salary_from).' - '.numberFormatShort($job->salary_to);
    $skills = $job->jobsSkill->pluck('name');
    $tags = $job->jobsTag->pluck('name');
    $displayValue = fn ($value) => filled($value) ? html_entity_decode((string) $value) : __('messages.n/a');
    $overviewItems = [
        ['label' => __('messages.job.job_type'), 'value' => optional($job->jobType)->name, 'icon' => 'fa-briefcase'],
        ['label' => __('messages.job_category.job_category'), 'value' => optional($job->jobCategory)->name, 'icon' => 'fa-layer-group'],
        ['label' => __('messages.job.career_level'), 'value' => optional($job->careerLevel)->level_name, 'icon' => 'fa-chart-line'],
        ['label' => __('messages.job.job_shift'), 'value' => optional($job->jobShift)->shift, 'icon' => 'fa-clock'],
        ['label' => __('messages.job_experience.job_experience'), 'value' => $job->formatted_experience, 'icon' => 'fa-user-tie'],
        ['label' => __('messages.job.vacancy'), 'value' => $job->vacancy, 'icon' => 'fa-users'],
        ['label' => __('messages.job.currency'), 'value' => optional($job->currency)->currency_name, 'icon' => 'fa-money-bill'],
        ['label' => __('messages.job.salary_period'), 'value' => optional($job->salaryPeriod)->period, 'icon' => 'fa-calendar-alt'],
        ['label' => __('messages.job.functional_area'), 'value' => optional($job->functionalArea)->name, 'icon' => 'fa-sitemap'],
        ['label' => __('messages.job.degree_level'), 'value' => optional($job->degreeLevel)->name, 'icon' => 'fa-graduation-cap'],
        ['label' => __('messages.job.is_freelance'), 'value' => $job->is_freelance == 1 ? __('messages.common.yes') : __('messages.common.no'), 'icon' => 'fa-laptop-house'],
        ['label' => __('messages.job.hide_salary'), 'value' => $job->hide_salary == 1 ? __('messages.common.yes') : __('messages.common.no'), 'icon' => 'fa-eye-slash'],
    ];
@endphp

<style>
    .admin-job-detail {
        color: #1f2937;
    }

    .admin-job-detail__hero {
        background: linear-gradient(135deg, #f4f8ff 0%, #ffffff 52%, #f7fbfa 100%);
        border-bottom: 1px solid #eef2f7;
        padding: 28px;
    }

    .admin-job-detail__logo {
        align-items: center;
        background: #ffffff;
        border: 1px solid #e8edf5;
        border-radius: 4px;
        display: flex;
        flex: 0 0 72px;
        height: 72px;
        justify-content: center;
        overflow: hidden;
        width: 72px;
    }

    .admin-job-detail__logo img {
        height: 100%;
        object-fit: contain;
        padding: 8px;
        width: 100%;
    }

    .admin-job-detail__stat {
        background: rgba(255, 255, 255, 0.82);
        border: 1px solid #e8edf5;
        border-radius: 10px;
        min-width: 180px;
        padding: 14px 16px;
    }

    .admin-job-detail__section {
        border: 1px solid #edf1f6;
        border-radius: 10px;
        padding: 22px;
    }

    .admin-job-detail__item {
        align-items: flex-start;
        border-bottom: 1px solid #f0f3f7;
        display: flex;
        gap: 12px;
        min-height: 74px;
        padding: 4px 0 18px;
    }

    .admin-job-detail__item-icon {
        align-items: center;
        background: #eef6ff;
        border-radius: 8px;
        color: #2563eb;
        display: flex;
        flex: 0 0 34px;
        height: 34px;
        justify-content: center;
        margin-top: 2px;
        width: 34px;
    }

    .admin-job-detail__chip {
        background: #f4f7fb;
        border: 1px solid #e7edf5;
        border-radius: 999px;
        color: #334155;
        display: inline-flex;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.2;
        padding: 8px 12px;
    }

    .admin-job-detail__content {
        color: #334155;
        font-size: 14px;
        line-height: 1.75;
        overflow-wrap: anywhere;
    }

    .admin-job-detail__content p {
        margin-bottom: 12px;
    }

    .admin-job-detail__content ul,
    .admin-job-detail__content ol {
        margin: 0 0 14px 0;
        padding-left: 24px;
    }

    .admin-job-detail__content ul {
        list-style: disc;
    }

    .admin-job-detail__content ol {
        list-style: decimal;
    }

    .admin-job-detail__content ul ul {
        list-style: circle;
    }

    .admin-job-detail__content ul ul ul {
        list-style: square;
    }

    .admin-job-detail__content li {
        display: list-item;
        margin-bottom: 7px;
        padding-left: 2px;
    }

    .admin-job-detail__content .ql-align-center {
        text-align: center;
    }

    .admin-job-detail__content .ql-align-right {
        text-align: right;
    }

    .admin-job-detail__content .ql-align-justify {
        text-align: justify;
    }

    .admin-job-detail__content .ql-indent-1 {
        margin-left: 24px;
    }

    .admin-job-detail__content .ql-indent-2 {
        margin-left: 48px;
    }

    .admin-job-detail__content .ql-indent-3 {
        margin-left: 72px;
    }

    .admin-job-detail__content blockquote {
        border-left: 3px solid #d8e1ef;
        color: #475569;
        margin: 0 0 14px;
        padding: 8px 0 8px 14px;
    }

    @media (max-width: 575.98px) {
        .admin-job-detail__hero {
            padding: 20px;
        }

        .admin-job-detail__stat {
            width: 100%;
        }
    }
</style>

<div class="admin-job-detail">
    <div class="admin-job-detail__hero">
        <div class="d-flex flex-column flex-xl-row justify-content-between gap-6">
            <div class="d-flex gap-4">
                <div class="admin-job-detail__logo">
                    <img src="{{ optional($company)->company_url ?? asset('assets/img/employer-image.png') }}"
                         alt="{{ $companyName }}">
                </div>
                <div>
                    {{-- <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="badge badge-light-primary">{{ $displayValue(optional($job->jobCategory)->name) }}</span>
                        <span class="badge badge-light-info">{{ $displayValue(optional($job->jobType)->name) }}</span>
                        @if($job->freshers_encouraged)
                            <span class="badge badge-light-success">{{ __('messages.job.freshers_encouraged') }}</span>
                        @endif
                    </div> --}}
                    <h2 class="fs-1 fw-bold text-gray-900 mb-3">{{ $jobTitle }}</h2>
                    <div class="d-flex flex-wrap gap-4 text-gray-700 fs-6">
                        <span><i class="fas fa-building text-primary me-2"></i>{{ $companyName }}</span>
                        <span><i class="fas fa-map-marker-alt text-primary me-2"></i>{{ $location ?: __('messages.n/a') }}</span>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column flex-sm-row gap-3">
                <div class="admin-job-detail__stat">
                    <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ __('messages.job.salary_range') }}</div>
                    <div class="text-gray-900 fs-5 fw-bold">{{ $salaryRange }}</div>
                </div>
                <div class="admin-job-detail__stat">
                    <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ __('messages.job.job_expiry_date') }}</div>
                    <div class="text-gray-900 fs-5 fw-bold">{{ Carbon\Carbon::parse($job->job_expiry_date)->format('jS M, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-7">
        <div class="row g-6">
            <div class="col-xl-8">
                <div class="admin-job-detail__section mb-6">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <h3 class="fs-3 fw-bold text-gray-900 mb-0">{{ __('messages.job.job_details') }}</h3>
                    </div>
                    <div class="row g-5">
                        @foreach($overviewItems as $item)
                            <div class="col-md-6">
                                <div class="admin-job-detail__item">
                                    <div class="admin-job-detail__item-icon">
                                        <i class="fas {{ $item['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ $item['label'] }}</div>
                                        <div class="text-gray-900 fs-6 fw-semibold">{{ $displayValue($item['value']) }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="admin-job-detail__section mb-6">
                    <h3 class="fs-3 fw-bold text-gray-900 mb-4">{{ __('messages.job.description') }}</h3>
                    @if($job->description)
                        <div class="admin-job-detail__content job-editor-content">{!! $job->description !!}</div>
                    @else
                        <div class="text-gray-700 fs-6">{{ __('messages.n/a') }}</div>
                    @endif
                </div>

                <div class="admin-job-detail__section">
                    <h3 class="fs-3 fw-bold text-gray-900 mb-4">{{ __('messages.job.key_responsibilities') }}</h3>
                    @if($job->key_responsibilities)
                        <div class="admin-job-detail__content job-editor-content">{!! $job->key_responsibilities !!}</div>
                    @else
                        <div class="text-gray-700 fs-6">{{ __('messages.n/a') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-xl-4">
                <div class="admin-job-detail__section mb-6">
                    <h3 class="fs-4 fw-bold text-gray-900 mb-4">{{ __('messages.job.job_skill') }}</h3>
                    @if($skills->isNotEmpty())
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($skills as $skill)
                                <span class="admin-job-detail__chip">{{ html_entity_decode($skill) }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="text-gray-700 fs-6">{{ __('messages.n/a') }}</div>
                    @endif
                </div>

                <div class="admin-job-detail__section mb-6">
                    <h3 class="fs-4 fw-bold text-gray-900 mb-4">{{ __('messages.job_tag.show_job_tag') }}</h3>
                    @if($tags->isNotEmpty())
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($tags as $tag)
                                <span class="admin-job-detail__chip">{{ html_entity_decode($tag) }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="text-gray-700 fs-6">{{ __('messages.n/a') }}</div>
                    @endif
                </div>

                <div class="admin-job-detail__section">
                    <h3 class="fs-4 fw-bold text-gray-900 mb-4">{{ __('messages.front_job_details.location') }}</h3>
                    <div class="d-flex flex-column gap-4">
                        <div>
                            <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ __('messages.job.country') }}</div>
                            <div class="text-gray-900 fs-6 fw-semibold">{{ !empty($job->country_id) ? $job->country_name : __('messages.n/a') }}</div>
                        </div>
                        <div>
                            <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ __('messages.job.state') }}</div>
                            <div class="text-gray-900 fs-6 fw-semibold">{{ !empty($job->state_id) ? $job->state_name : __('messages.n/a') }}</div>
                        </div>
                        <div>
                            <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ __('messages.job.city') }}</div>
                            <div class="text-gray-900 fs-6 fw-semibold">{{ !empty($job->city_id) ? $job->city_name : __('messages.n/a') }}</div>
                        </div>
                        <div>
                            <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ __('messages.thana.thana_name') }}</div>
                            <div class="text-gray-900 fs-6 fw-semibold">{{ !empty($job->thana_id) ? $job->thana_name : __('messages.n/a') }}</div>
                        </div>
                        <div>
                            <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ __('messages.common.created_on') }}</div>
                            <div class="text-gray-900 fs-6 fw-semibold">
                                <span data-toggle="tooltip" data-placement="right"
                                      title="{{ date('jS M, Y', strtotime($job->created_at)) }}">{{ $job->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ __('messages.common.last_updated') }}</div>
                            <div class="text-gray-900 fs-6 fw-semibold">
                                <span data-toggle="tooltip" data-placement="right"
                                      title="{{ date('jS M, Y', strtotime($job->updated_at)) }}">{{ $job->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
