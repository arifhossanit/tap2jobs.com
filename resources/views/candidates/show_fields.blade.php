@php
    $user = $candidate->user;
    $na = __('messages.n/a');
    $value = static fn ($item) => filled($item) ? $item : $na;
    $personalDetails = [
        [__('messages.candidate.father_name'), $candidate->father_name],
        [__('messages.candidate.birth_date'), optional($user->dob)->translatedFormat('d M, Y') ?? $user->dob],
        [__('messages.candidate.gender'), $user->gender == 0 ? __('messages.common.male') : __('messages.common.female')],
        [__('messages.candidate.marital_status'), optional($candidate->maritalStatus)->marital_status],
        [__('messages.candidate.nationality'), $candidate->nationality],
        [__('messages.candidate.national_id_card'), $candidate->national_id_card],
    ];
    $contactDetails = [
        [__('messages.candidate.email'), $user->email],
        [__('messages.candidate.phone'), $user->phone],
        [__('messages.company.country'), $user->country_name],
        [__('messages.company.state'), $user->state_name],
        [__('messages.company.city'), $user->city_name],
        [__('messages.thana.thana_name'), $user->thana_name],
        [__('messages.candidate.address'), $candidate->address],
    ];
    $careerDetails = [
        [__('messages.candidate.experience'), filled($candidate->experience) ? $candidate->experience.' '.__('messages.candidate.in_years') : null],
        [__('messages.candidate.career_level'), optional($candidate->careerLevel)->level_name],
        [__('messages.candidate.industry'), optional($candidate->industry)->name],
        [__('messages.candidate.functional_area'), optional($candidate->functionalArea)->name],
        [__('messages.candidate.current_salary'), filled($candidate->current_salary) ? number_format($candidate->current_salary).' '.($currency[$candidate->salary_currency] ?? '') : null],
        [__('messages.candidate.expected_salary'), filled($candidate->expected_salary) ? number_format($candidate->expected_salary).' '.($currency[$candidate->salary_currency] ?? '') : null],
        [__('messages.candidate.available_at'), $candidate->immediate_available ? __('messages.candidate.immediate_available') : $candidate->available_at],
    ];
@endphp

<style>
    .candidate-detail-hero { background: linear-gradient(120deg, #183b68, #315d98); border-radius: .35rem; color: #fff; overflow: hidden; }
    .candidate-detail-avatar { width: 72px; height: 72px; border: 3px solid rgba(255,255,255,.75); border-radius: 50%; object-fit: cover; }
    .candidate-detail-section { border: 1px solid #e8edf4; border-radius: .75rem; height: 100%; }
    .candidate-detail-section__title { border-bottom: 1px solid #eef2f6; font-size: 1rem; font-weight: 700; margin: 0; padding: 1rem 1.25rem; }
    .candidate-detail-item { min-height: 68px; padding: .75rem 1.25rem; }
    .candidate-detail-label { color: #7b8794; display: block; font-size: .78rem; font-weight: 600; margin-bottom: .25rem; text-transform: uppercase; }
    .candidate-detail-value { color: #172b4d; font-weight: 500; overflow-wrap: anywhere; }
    .candidate-detail-empty { color: #98a2b3; font-weight: 400; }
    .candidate-detail-tag { background: #edf4ff; border-radius: 999px; color: #315d98; display: inline-block; font-size: .82rem; margin: 0 .35rem .35rem 0; padding: .28rem .65rem; }
</style>

<section class="candidate-detail-hero mb-5 p-4 p-lg-5">
    <div class="d-flex align-items-center flex-wrap gap-4">
        <img class="candidate-detail-avatar" src="{{ $user->avatar }}" alt="{{ $user->full_name }}">
        <div class="flex-grow-1">
            <h2 class="mb-1 text-white">{{ $user->full_name }}</h2>
            <div class="opacity-75">{{ $user->email }}</div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }} fs-7">{{ $user->is_active ? __('messages.common.active') : __('messages.common.de_active') }}</span>
            <span class="badge {{ $user->email_verified_at ? 'bg-info' : 'bg-warning text-dark' }} fs-7">{{ $user->email_verified_at ? __('messages.common.verified') : __('messages.common.not_verified') }}</span>
        </div>
    </div>
</section>

<div class="row g-5">
    <div class="col-xl-6">
        <section class="candidate-detail-section">
            <h2 class="candidate-detail-section__title"><i class="fa-regular fa-user me-2 text-primary"></i>Personal Information</h2>
            <div class="row g-0">
                @foreach($personalDetails as [$label, $item])
                    <div class="col-md-6 candidate-detail-item"><span class="candidate-detail-label">{{ $label }}</span><span class="candidate-detail-value {{ filled($item) ? '' : 'candidate-detail-empty' }}">{{ $value($item) }}</span></div>
                @endforeach
            </div>
        </section>
    </div>
    <div class="col-xl-6">
        <section class="candidate-detail-section">
            <h2 class="candidate-detail-section__title"><i class="fa-regular fa-address-card me-2 text-primary"></i>Contact & Location</h2>
            <div class="row g-0">
                @foreach($contactDetails as [$label, $item])
                    <div class="col-md-6 candidate-detail-item"><span class="candidate-detail-label">{{ $label }}</span><span class="candidate-detail-value {{ filled($item) ? '' : 'candidate-detail-empty' }}">{{ $value($item) }}</span></div>
                @endforeach
            </div>
        </section>
    </div>
    <div class="col-xl-6">
        <section class="candidate-detail-section">
            <h2 class="candidate-detail-section__title"><i class="fa-solid fa-briefcase me-2 text-primary"></i>Career Profile</h2>
            <div class="row g-0">
                @foreach($careerDetails as [$label, $item])
                    <div class="col-md-6 candidate-detail-item"><span class="candidate-detail-label">{{ $label }}</span><span class="candidate-detail-value {{ filled($item) ? '' : 'candidate-detail-empty' }}">{{ $value($item) }}</span></div>
                @endforeach
            </div>
        </section>
    </div>
    <div class="col-xl-6">
        <section class="candidate-detail-section">
            <h2 class="candidate-detail-section__title"><i class="fa-solid fa-list-check me-2 text-primary"></i>Skills & Languages</h2>
            <div class="candidate-detail-item"><span class="candidate-detail-label">{{ __('messages.candidate.candidate_skill') }}</span><div class="candidate-detail-value">@forelse($user->candidateSkill as $skill)<span class="candidate-detail-tag">{{ $skill->name }}</span>@empty<span class="candidate-detail-empty">{{ $na }}</span>@endforelse</div></div>
            <div class="candidate-detail-item border-top"><span class="candidate-detail-label">{{ __('messages.candidate.candidate_language') }}</span><div class="candidate-detail-value">@forelse($user->candidateLanguage as $language)<span class="candidate-detail-tag">{{ $language->language }}</span>@empty<span class="candidate-detail-empty">{{ $na }}</span>@endforelse</div></div>
            <div class="candidate-detail-item border-top"><span class="candidate-detail-label">{{ __('messages.common.created_on') }}</span><span class="candidate-detail-value">{{ optional($candidate->created_at)->translatedFormat('d M, Y') ?? $na }}</span></div>
        </section>
    </div>
</div>
