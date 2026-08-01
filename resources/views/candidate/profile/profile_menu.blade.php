@php
    $sectionName = $data['sectionName'] ?? 'personal-information';
@endphp

<div class="candidate-profile-menu">
    <div class="candidate-profile-menu__top overflow-auto">
        <a class="candidate-profile-menu__main-link {{ $sectionName == 'personal-information' ? 'active' : '' }}"
           href="{{ route('candidate.profile', ['section' => 'personal-information']) }}">
            <i class="fa-regular fa-user"></i>
            <span>{{ __('messages.candidate_profile.personal_information') }}</span>
        </a>
        <a class="candidate-profile-menu__main-link {{ $sectionName == 'education-training' ? 'active' : '' }}"
           href="{{ route('candidate.profile', ['section' => 'education-training']) }}">
            <i class="fa-solid fa-graduation-cap"></i>
            <span>{{ __('messages.candidate_profile.education_training') }}</span>
        </a>
        <a class="candidate-profile-menu__main-link {{ $sectionName == 'employment' ? 'active' : '' }}"
           href="{{ route('candidate.profile', ['section' => 'employment']) }}">
            <i class="fa-solid fa-briefcase"></i>
            <span>{{ __('messages.candidate_profile.employment') }}</span>
        </a>
        <a class="candidate-profile-menu__main-link {{ $sectionName == 'other-information' ? 'active' : '' }}"
           href="{{ route('candidate.profile', ['section' => 'other-information']) }}">
            <i class="fa-solid fa-table-cells-large"></i>
            <span>{{ __('messages.candidate_profile.other_information') }}</span>
        </a>
        <a class="candidate-profile-menu__main-link {{ $sectionName == 'accomplishment' ? 'active' : '' }}"
           href="{{ route('candidate.profile', ['section' => 'accomplishment']) }}">
            <i class="fa-solid fa-award"></i>
            <span>{{ __('messages.candidate_profile.accomplishment') }}</span>
        </a>
    </div>

    <div class="candidate-profile-menu__sub overflow-auto">
        @if($sectionName == 'education-training')
            <a class="candidate-profile-menu__sub-link active" href="#candidateEducationDetails"
               data-career-section-link="candidateEducationDetails">
                {{ __('messages.candidate_profile.education') }}
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateTrainingDetails"
               data-career-section-link="candidateTrainingDetails">
                {{ __('messages.candidate_profile.training') }}
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateProfessionalCertification"
               data-career-section-link="candidateProfessionalCertification">
                {{ __('messages.candidate_profile.professional_certification') }}
            </a>
        @elseif($sectionName == 'employment')
            <a class="candidate-profile-menu__sub-link active" href="#candidateExperienceDetails"
               data-employment-section-link="candidateExperienceDetails">
                {{ __('messages.candidate_profile.experience') }}
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateRetiredArmyEmployment"
               data-employment-section-link="candidateRetiredArmyEmployment">
                Employment History(For Retired Army Person)
            </a>
        @elseif($sectionName == 'other-information')
            <a class="candidate-profile-menu__sub-link active" href="#candidateSkillInformation"
               data-other-section-link="candidateSkillInformation">
                Skill
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateExtracurricularActivities"
               data-other-section-link="candidateExtracurricularActivities">
                Extracurricular Activities
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateLanguageProficiency"
               data-other-section-link="candidateLanguageProficiency">
                Language Proficiency
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateLinkAccount"
               data-other-section-link="candidateLinkAccount">
                Link Account
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateReference"
               data-other-section-link="candidateReference">
                Reference
            </a>
        @elseif($sectionName == 'accomplishment')
            <a class="candidate-profile-menu__sub-link active" href="#candidatePortfolioInformation"
               data-accomplishment-section-link="candidatePortfolioInformation">
                Portfolio
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidatePublicationInformation"
               data-accomplishment-section-link="candidatePublicationInformation">
                Publication
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateAwardHonorInformation"
               data-accomplishment-section-link="candidateAwardHonorInformation">
                Award/Honor
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateProjectInformation"
               data-accomplishment-section-link="candidateProjectInformation">
                Project
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateOtherAccomplishmentInformation"
               data-accomplishment-section-link="candidateOtherAccomplishmentInformation">
                Other
            </a>
        @else
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
        @endif
    </div>
</div>
