@php
    $user = $company->user;
    $companyName = $user && $user->full_name ? html_entity_decode($user->full_name) : __('messages.n/a');
    $displayValue = fn ($value) => filled($value) ? html_entity_decode((string) $value) : __('messages.n/a');
    $yesNo = fn ($value) => $value ? __('messages.common.yes') : __('messages.common.no');
    $statusLabel = $user && $user->is_active == 1 ? __('messages.common.active') : __('messages.common.de_active');
    $statusClass = $user && $user->is_active == 1 ? 'bg-light-success text-success' : 'bg-light-danger text-danger';
    $location = collect([
        $company->location,
        !empty($user->thana_id) ? $user->thana_name : null,
        !empty($user->city_id) ? $user->city_name : null,
        !empty($user->state_id) ? $user->state_name : null,
        !empty($user->country_id) ? $user->country_name : null,
    ])->filter()->implode(', ');
    $industryIds = collect($company->industry_ids ?: [$company->industry_id])
        ->filter()
        ->map(fn ($id) => (int) $id)
        ->unique()
        ->values();
    $industries = $industryIds->isNotEmpty()
        ? \App\Models\Industry::whereIn('id', $industryIds)->orderBy('name')->pluck('name')
        : collect();
    $profileItems = [
        ['label' => __('messages.company.company_name_bn'), 'value' => $company->company_name_bn, 'icon' => 'fa-language'],
        ['label' => __('messages.company.industry'), 'value' => $industries->isNotEmpty() ? $industries->implode(', ') : optional($company->industry)->name, 'icon' => 'fa-layer-group'],
        ['label' => __('messages.company.ownership_type'), 'value' => optional($company->ownerShipType)->name, 'icon' => 'fa-building'],
        ['label' => __('messages.company.company_size'), 'value' => $company->employee_range ?: optional($company->companySize)->size, 'icon' => 'fa-users'],
        ['label' => __('messages.company.established_in'), 'value' => $company->established_in, 'icon' => 'fa-calendar-alt'],
        ['label' => __('messages.company.no_of_offices'), 'value' => $company->no_of_offices, 'icon' => 'fa-sitemap'],
        ['label' => __('messages.employer_account.trade_license_no'), 'value' => $company->trade_license_no, 'icon' => 'fa-file-alt'],
        ['label' => __('messages.employer_account.rl_no'), 'value' => $company->rl_no, 'icon' => 'fa-id-card'],
    ];
    $contactItems = [
        ['label' => __('messages.employer_account.contact_person_name'), 'value' => $company->contact_person_name ?: $companyName, 'icon' => 'fa-user'],
        ['label' => __('messages.employer_account.contact_person_designation'), 'value' => $company->contact_person_designation ?: $company->ceo, 'icon' => 'fa-user-tie'],
        ['label' => __('messages.employer_account.contact_person_email'), 'value' => optional($user)->email, 'icon' => 'fa-envelope'],
        ['label' => __('messages.employer_account.contact_person_mobile'), 'value' => optional($user)->phone, 'icon' => 'fa-phone'],
        ['label' => __('messages.company.fax'), 'value' => $company->fax, 'icon' => 'fa-fax'],
        ['label' => __('messages.employer_account.billing_contact_email'), 'value' => $company->billing_email, 'icon' => 'fa-file-invoice'],
        ['label' => __('messages.employer_account.billing_contact_number'), 'value' => trim(($company->billing_region_code ? '+' . $company->billing_region_code . ' ' : '') . ($company->billing_phone ?: '')), 'icon' => 'fa-phone-volume'],
        ['label' => __('messages.employer_account.billing_address'), 'value' => $company->billing_address, 'icon' => 'fa-map-marker-alt'],
    ];
    $locationItems = [
        ['label' => __('messages.company.country'), 'value' => !empty($user->country_id) ? $user->country_name : null],
        ['label' => __('messages.company.state'), 'value' => !empty($user->state_id) ? $user->state_name : null],
        ['label' => __('messages.company.city'), 'value' => !empty($user->city_id) ? $user->city_name : null],
        ['label' => __('messages.thana.thana_name'), 'value' => !empty($user->thana_id) ? $user->thana_name : null],
        ['label' => __('messages.company.location'), 'value' => $company->location],
        ['label' => __('messages.employer_account.company_address_bn'), 'value' => $company->company_address_bn],
    ];
    $socialItems = [
        ['label' => __('messages.company.website'), 'value' => $company->website, 'icon' => 'fa-globe', 'brand' => false],
        ['label' => __('messages.company.facebook_url'), 'value' => optional($user)->facebook_url, 'icon' => 'fa-facebook-f', 'brand' => true],
        ['label' => __('messages.company.twitter_url'), 'value' => optional($user)->twitter_url, 'icon' => 'fa-twitter', 'brand' => true],
        ['label' => __('messages.company.linkedin_url'), 'value' => optional($user)->linkedin_url, 'icon' => 'fa-linkedin-in', 'brand' => true],
        ['label' => __('messages.company.google_plus_url'), 'value' => optional($user)->google_plus_url, 'icon' => 'fa-google-plus-g', 'brand' => true],
        ['label' => __('messages.company.pinterest_url'), 'value' => optional($user)->pinterest_url, 'icon' => 'fa-pinterest-p', 'brand' => true],
    ];
    $disabilityFacilityOptions = [
        'accessible_documentation' => __('messages.employer_register.facilities.accessible_documentation'),
        'accessible_washrooms' => __('messages.employer_register.facilities.accessible_washrooms'),
        'adapted_transport' => __('messages.employer_register.facilities.adapted_transport'),
        'adapted_work_station' => __('messages.employer_register.facilities.adapted_work_station'),
        'assistive_devices' => __('messages.employer_register.facilities.assistive_devices'),
        'accessible_online_tools' => __('messages.employer_register.facilities.accessible_online_tools'),
        'flexible_work_arrangements' => __('messages.employer_register.facilities.flexible_work_arrangements'),
        'support_staff' => __('messages.employer_register.facilities.support_staff'),
        'accessible_recruitment' => __('messages.employer_register.facilities.accessible_recruitment'),
    ];
    $selectedFacilities = collect($company->disability_facilities ?: [])
        ->map(fn ($facility) => $disabilityFacilityOptions[$facility] ?? $facility);
    $linkHref = function ($value) {
        if (! filled($value)) {
            return null;
        }

        return \Illuminate\Support\Str::startsWith($value, ['http://', 'https://'])
            ? $value
            : 'https://' . $value;
    };
@endphp

<style>
    .admin-employer-detail {
        color: #1f2937;
        width: 100%;
    }

    .admin-employer-detail__hero {
        background: linear-gradient(135deg, #f4f8ff 0%, #ffffff 52%, #f7fbfa 100%);
        border-bottom: 1px solid #eef2f7;
        padding: 28px;
    }

    .admin-employer-detail__logo {
        align-items: center;
        background: #ffffff;
        border: 1px solid #e8edf5;
        border-radius: 6px;
        display: flex;
        flex: 0 0 86px;
        height: 86px;
        justify-content: center;
        overflow: hidden;
        width: 86px;
    }

    .admin-employer-detail__logo img {
        height: 100%;
        object-fit: contain;
        padding: 10px;
        width: 100%;
    }

    .admin-employer-detail__stat,
    .admin-employer-detail__section {
        background: #ffffff;
        border: 1px solid #edf1f6;
        border-radius: 10px;
    }

    .admin-employer-detail__stat {
        min-width: 168px;
        padding: 14px 16px;
    }

    .admin-employer-detail__section {
        padding: 22px;
    }

    .admin-employer-detail__item {
        align-items: flex-start;
        border-bottom: 1px solid #f0f3f7;
        display: flex;
        gap: 12px;
        min-height: 72px;
        padding: 4px 0 18px;
    }

    .admin-employer-detail__item-icon {
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

    .admin-employer-detail__value {
        overflow-wrap: anywhere;
    }

    .admin-employer-detail__chip {
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

    .admin-employer-detail__content {
        color: #334155;
        font-size: 14px;
        line-height: 1.75;
        overflow-wrap: anywhere;
    }

    .admin-employer-detail__content p {
        margin-bottom: 12px;
    }

    .admin-employer-detail__content ul,
    .admin-employer-detail__content ol {
        margin: 0 0 14px 0;
        padding-left: 24px;
    }

    .admin-employer-detail__content ul {
        list-style: disc;
    }

    .admin-employer-detail__content ol {
        list-style: decimal;
    }

    .admin-employer-detail__content li {
        display: list-item;
        margin-bottom: 7px;
        padding-left: 2px;
    }

    .admin-employer-detail__content blockquote {
        border-left: 3px solid #d8e1ef;
        color: #475569;
        margin: 0 0 14px;
        padding: 8px 0 8px 14px;
    }

    @media (max-width: 575.98px) {
        .admin-employer-detail__hero {
            padding: 20px;
        }

        .admin-employer-detail__stat {
            width: 100%;
        }
    }
</style>

<div class="admin-employer-detail">
    <div class="admin-employer-detail__hero">
        <div class="d-flex flex-column flex-xl-row justify-content-between gap-6">
            <div class="d-flex flex-column flex-sm-row gap-4">
                <div class="admin-employer-detail__logo">
                    <img src="{{ $company->company_url }}" alt="{{ $companyName }}">
                </div>
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="badge {{ $statusClass }} px-3 py-2 fs-7 fw-semibold">{{ $statusLabel }}</span>
                        <span class="badge {{ $company->activeFeatured ? 'bg-light-info text-info' : 'bg-light-secondary text-secondary' }} px-3 py-2 fs-7 fw-semibold">
                            {{ $company->activeFeatured ? __('messages.company.is_featured') : __('messages.not_featured') }}
                        </span>
                    </div>
                    <h2 class="fs-1 fw-bold text-gray-900 mb-3">{{ $companyName }}</h2>
                    <div class="d-flex flex-wrap gap-4 text-gray-700 fs-6">
                        <span><i class="fas fa-envelope text-primary me-2"></i>{{ $displayValue(optional($user)->email) }}</span>
                        <span><i class="fas fa-map-marker-alt text-primary me-2"></i>{{ $location ?: __('messages.n/a') }}</span>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column flex-sm-row gap-3">
                <div class="admin-employer-detail__stat">
                    <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ __('messages.company.industry') }}</div>
                    <div class="text-gray-900 fs-6 fw-bold">{{ $industries->isNotEmpty() ? $industries->first() : $displayValue(optional($company->industry)->name) }}</div>
                </div>
                <div class="admin-employer-detail__stat">
                    <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ __('messages.common.created_on') }}</div>
                    <div class="text-gray-900 fs-6 fw-bold">
                        <span data-toggle="tooltip" data-placement="right"
                              title="{{ date('jS M, Y', strtotime($company->created_at)) }}">{{ $company->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-7">
        <div class="row g-6">
            <div class="col-xl-8">
                <div class="admin-employer-detail__section mb-6">
                    <h3 class="fs-3 fw-bold text-gray-900 mb-5">{{ __('messages.company.company_details') }}</h3>
                    <div class="row g-5">
                        @foreach($profileItems as $item)
                            <div class="col-md-6">
                                <div class="admin-employer-detail__item">
                                    <div class="admin-employer-detail__item-icon">
                                        <i class="fas {{ $item['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ $item['label'] }}</div>
                                        <div class="text-gray-900 fs-6 fw-semibold admin-employer-detail__value">{{ $displayValue($item['value']) }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="admin-employer-detail__section mb-6">
                    <h3 class="fs-3 fw-bold text-gray-900 mb-4">{{ __('messages.employer_account.business_description') }}</h3>
                    @if(filled($company->details))
                        <div class="admin-employer-detail__content">{!! $company->details !!}</div>
                    @else
                        <div class="text-gray-700 fs-6">{{ __('messages.n/a') }}</div>
                    @endif
                </div>

                <div class="admin-employer-detail__section">
                    <h3 class="fs-3 fw-bold text-gray-900 mb-4">{{ __('messages.employer_account.company_address') }}</h3>
                    <div class="row g-5">
                        @foreach($locationItems as $item)
                            <div class="col-md-6">
                                <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ $item['label'] }}</div>
                                <div class="text-gray-900 fs-6 fw-semibold admin-employer-detail__value">{{ $displayValue($item['value']) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="admin-employer-detail__section mb-6">
                    <h3 class="fs-4 fw-bold text-gray-900 mb-4">{{ __('messages.company.contact_details') }}</h3>
                    <div class="d-flex flex-column gap-4">
                        @foreach($contactItems as $item)
                            <div class="d-flex gap-3">
                                <div class="admin-employer-detail__item-icon">
                                    <i class="fas {{ $item['icon'] }}"></i>
                                </div>
                                <div>
                                    <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ $item['label'] }}</div>
                                    <div class="text-gray-900 fs-6 fw-semibold admin-employer-detail__value">{{ $displayValue($item['value']) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="admin-employer-detail__section mb-6">
                    <h3 class="fs-4 fw-bold text-gray-900 mb-4">{{ __('messages.company.website') }} / Social</h3>
                    <div class="d-flex flex-column gap-4">
                        @foreach($socialItems as $item)
                            @php($href = $linkHref($item['value']))
                            <div class="d-flex gap-3">
                                <div class="admin-employer-detail__item-icon">
                                    <i class="{{ $item['brand'] ? 'fab' : 'fas' }} {{ $item['icon'] }}"></i>
                                </div>
                                <div>
                                    <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ $item['label'] }}</div>
                                    @if($href)
                                        <a href="{{ $href }}" target="_blank" class="text-gray-900 fs-6 fw-semibold admin-employer-detail__value">{{ $displayValue($item['value']) }}</a>
                                    @else
                                        <div class="text-gray-900 fs-6 fw-semibold">{{ __('messages.n/a') }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="admin-employer-detail__section mb-6">
                    <h3 class="fs-4 fw-bold text-gray-900 mb-4">{{ __('messages.employer_account.disability_facilities_question') }}</h3>
                    <div class="d-flex flex-column gap-4">
                        <div>
                            <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ __('messages.employer_account.has_disability_facilities_question') }}</div>
                            <div class="text-gray-900 fs-6 fw-semibold">{{ $yesNo($company->has_disability_facilities) }}</div>
                        </div>
                        <div>
                            <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ __('messages.employer_account.disability_inclusion_policy') }}</div>
                            <div class="text-gray-900 fs-6 fw-semibold">{{ $company->disability_inclusion_policy === null ? __('messages.n/a') : $yesNo($company->disability_inclusion_policy) }}</div>
                        </div>
                        <div>
                            <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ __('messages.employer_account.disability_support') }}</div>
                            <div class="text-gray-900 fs-6 fw-semibold">{{ $company->disability_inclusion_support === null ? __('messages.n/a') : $yesNo($company->disability_inclusion_support) }}</div>
                        </div>
                        <div>
                            <div class="text-gray-600 fs-8 fw-semibold text-uppercase mb-1">{{ __('messages.employer_account.disability_training') }}</div>
                            <div class="text-gray-900 fs-6 fw-semibold">{{ $company->disability_inclusion_training === null ? __('messages.n/a') : $yesNo($company->disability_inclusion_training) }}</div>
                        </div>
                        @if($selectedFacilities->isNotEmpty())
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($selectedFacilities as $facility)
                                    <span class="admin-employer-detail__chip">{{ $facility }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="admin-employer-detail__section">
                    <h3 class="fs-4 fw-bold text-gray-900 mb-4">{{ __('messages.common.last_updated') }}</h3>
                    <div class="text-gray-900 fs-6 fw-semibold">
                        <span data-toggle="tooltip" data-placement="right"
                              title="{{ date('jS M, Y', strtotime($company->updated_at)) }}">{{ $company->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
