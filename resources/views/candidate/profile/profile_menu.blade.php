@php
    $sectionName = $data['sectionName'] ?? 'general';
@endphp

<div class="candidate-profile-menu">
    <div class="candidate-profile-menu__top overflow-auto">
        <a class="candidate-profile-menu__main-link {{ $sectionName == 'general' ? 'active' : '' }}"
           href="{{ route('candidate.profile', ['section' => 'general']) }}">
            <i class="fa-regular fa-user"></i>
            <span>{{ __('messages.candidate_profile.personal_information') }}</span>
        </a>
        <a class="candidate-profile-menu__main-link {{ $sectionName == 'career-informations' ? 'active' : '' }}"
           href="{{ route('candidate.profile', ['section' => 'career-informations']) }}">
            <i class="fa-solid fa-graduation-cap"></i>
            <span>{{ __('messages.candidate_profile.education_training') }}</span>
        </a>
        <a class="candidate-profile-menu__main-link"
           href="{{ route('candidate.profile', ['section' => 'career-informations']) }}">
            <i class="fa-solid fa-briefcase"></i>
            <span>{{ __('messages.candidate_profile.employment') }}</span>
        </a>
        <a class="candidate-profile-menu__main-link {{ $sectionName == 'cv-builder' ? 'active' : '' }}"
           href="{{ route('candidate.profile', ['section' => 'cv-builder']) }}">
            <i class="fa-solid fa-table-cells-large"></i>
            <span>{{ __('messages.candidate_profile.other_information') }}</span>
        </a>
        <a class="candidate-profile-menu__main-link {{ $sectionName == 'resume' ? 'active' : '' }}"
           href="{{ route('candidate.profile', ['section' => 'resume']) }}">
            <i class="fa-solid fa-award"></i>
            <span>{{ __('messages.candidate_profile.accomplishment') }}</span>
        </a>
    </div>

    <div class="candidate-profile-menu__sub overflow-auto">
        <a class="candidate-profile-menu__sub-link active" href="#candidatePersonalDetails"
           data-profile-section-link="candidatePersonalDetails">
            {{ __('messages.candidate_profile.personal_details') }}
        </a>
        <a class="candidate-profile-menu__sub-link" href="#candidateAddressDetails"
           data-profile-section-link="candidateAddressDetails">
            {{ __('messages.candidate_profile.address_details') }}
        </a>
        <a class="candidate-profile-menu__sub-link" href="#candidateCareerApplication"
           data-profile-section-link="candidateCareerApplication">
            {{ __('messages.candidate_profile.career_and_application') }}
        </a>
        <a class="candidate-profile-menu__sub-link" href="#candidatePreferredArea"
           data-profile-section-link="candidatePreferredArea">
            {{ __('messages.candidate_profile.preferred_area') }}
        </a>
        <a class="candidate-profile-menu__sub-link" href="#candidateRelevantInformation"
           data-profile-section-link="candidateRelevantInformation">
            {{ __('messages.candidate_profile.relevant_information') }}
        </a>
        <a class="candidate-profile-menu__sub-link" href="#candidateDisabilityInformation"
           data-profile-section-link="candidateDisabilityInformation">
            {{ __('messages.candidate_profile.disability_information') }}
        </a>
    </div>
</div>


