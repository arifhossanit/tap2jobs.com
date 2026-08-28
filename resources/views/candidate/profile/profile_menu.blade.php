@php
    $sectionName = $data['sectionName'] ?? 'personal-information';
    $profileCompletion = $data['profileCompletion'] ?? ['percentage' => 0, 'completed' => 0, 'total' => 11, 'color' => '#f04438'];
    $completionPercentage = max(0, min(100, (int) ($profileCompletion['percentage'] ?? 0)));
    $completionColor = $profileCompletion['color'] ?? '#f04438';
    $missingProfileItems = collect($profileCompletion['missing'] ?? [])->take(8);
@endphp

<div class="candidate-profile-menu">
    <div class="candidate-profile-menu__top">
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
        <a class="candidate-profile-menu__main-link {{ $sectionName == 'resume' ? 'active' : '' }}"
           href="{{ route('candidate.profile', ['section' => 'resume']) }}">
            <i class="fa-regular fa-file-lines"></i>
            <span>{{ __('messages.candidate_profile.resume') }}</span>
        </a>
    </div>

    <div class="candidate-profile-menu__sub">
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
                {{ __('messages.candidate_profile.job_experience') }}
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateRetiredArmyEmployment"
               data-employment-section-link="candidateRetiredArmyEmployment">
                {{ __('messages.candidate_profile.army_experience') }}
            </a>
        @elseif($sectionName == 'other-information')
            <a class="candidate-profile-menu__sub-link active" href="#candidateSkillInformation"
               data-other-section-link="candidateSkillInformation">
                {{ __('messages.candidate_profile.skill') }}
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateExtracurricularActivities"
               data-other-section-link="candidateExtracurricularActivities">
                {{ __('messages.candidate_profile.extracurricular_activities') }}
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateLanguageProficiency"
               data-other-section-link="candidateLanguageProficiency">
                {{ __('messages.candidate_profile.language_proficiency') }}
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateLinkAccount"
               data-other-section-link="candidateLinkAccount">
                {{ __('messages.candidate_profile.link_account') }}
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateReference"
               data-other-section-link="candidateReference">
                {{ __('messages.candidate_profile.reference') }}
            </a>
        @elseif($sectionName == 'accomplishment')
            <a class="candidate-profile-menu__sub-link active" href="#candidatePortfolioInformation"
               data-accomplishment-section-link="candidatePortfolioInformation">
                {{ __('messages.candidate_profile.portfolio') }}
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidatePublicationInformation"
               data-accomplishment-section-link="candidatePublicationInformation">
                {{ __('messages.candidate_profile.publication') }}
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateAwardHonorInformation"
               data-accomplishment-section-link="candidateAwardHonorInformation">
                {{ __('messages.candidate_profile.award_honor') }}
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateProjectInformation"
               data-accomplishment-section-link="candidateProjectInformation">
                {{ __('messages.candidate_profile.project') }}
            </a>
            <a class="candidate-profile-menu__sub-link" href="#candidateOtherAccomplishmentInformation"
               data-accomplishment-section-link="candidateOtherAccomplishmentInformation">
                {{ __('messages.candidate_profile.other') }}
            </a>
        @elseif($sectionName == 'resume')
            <a class="candidate-profile-menu__sub-link active" href="#">
                {{ __('messages.candidate_profile.application_cv') }}
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
    <div class="candidate-profile-progress">
        <div class="candidate-profile-progress__track"
             aria-label="Profile completed {{ $completionPercentage }}%"
             style="--completion-left: {{ $completionPercentage }}%;">
            <span style="width: {{ $completionPercentage }}%; background-color: {{ $completionColor }};"></span>
            <strong>{{ $completionPercentage }}%</strong>
            @if($missingProfileItems->isNotEmpty())
                <div class="candidate-profile-progress__remaining" tabindex="0"
                     aria-label="Incomplete profile items">
                    <div class="candidate-profile-progress__popover">
                        <span>Complete these items</span>
                        <ul>
                            @foreach($missingProfileItems as $missingProfileItem)
                                <li>{{ $missingProfileItem }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    window.scrollCandidateProfileSection = function (target) {
        if (!target) {
            return;
        }

        window.clearTimeout(window.candidateProfileSectionScrollTimer);
        window.candidateProfileSectionScrollTimer = window.setTimeout(function () {
            const section = target.matches('.candidate-profile-section, .candidate-education-panel')
                ? target
                : target.closest('.candidate-profile-section, .candidate-education-panel') || target;
            const header = document.querySelector('.candidate-dashboard-header');
            const stickyMenu = document.querySelector('.candidate-profile-menu-shell');
            const headerHeight = header ? header.getBoundingClientRect().height : 0;
            const menuHeight = stickyMenu ? stickyMenu.getBoundingClientRect().height : 0;
            const stickyTop = stickyMenu ? parseFloat(window.getComputedStyle(stickyMenu).top) || 0 : 0;
            const visibleOffset = Math.max(headerHeight, stickyTop + menuHeight) + 12;
            const sectionTop = window.scrollY + section.getBoundingClientRect().top - visibleOffset;

            window.scrollTo({
                top: Math.max(0, sectionTop),
                behavior: 'smooth',
            });
        }, 380);
    };

    (function () {
        function isCandidateProfileMobile() {
            return window.matchMedia('(max-width: 767.98px)').matches;
        }

        function closeCandidateProfileSubDropdown(menu) {
            if (!menu) {
                return;
            }

            menu.classList.remove('is-sub-open');
        }

        function initCandidateProfileMobileMenu() {
            const menu = document.querySelector('.candidate-profile-menu');

            if (!menu || menu.dataset.mobileProfileMenuReady === 'true') {
                return;
            }

            menu.dataset.mobileProfileMenuReady = 'true';

            const topTabs = menu.querySelector('.candidate-profile-menu__top');
            const activeTab = topTabs ? topTabs.querySelector('.candidate-profile-menu__main-link.active') : null;

            if (topTabs && activeTab && isCandidateProfileMobile()) {
                topTabs.scrollLeft = Math.max(0, activeTab.offsetLeft - 12);
            }

            menu.addEventListener('click', function (event) {
                const mainLink = event.target.closest('.candidate-profile-menu__main-link');

                if (mainLink && mainLink.classList.contains('active') && isCandidateProfileMobile()) {
                    event.preventDefault();
                    menu.classList.toggle('is-sub-open');
                    return;
                }

                if (mainLink && isCandidateProfileMobile()) {
                    closeCandidateProfileSubDropdown(menu);
                    return;
                }

                const subLink = event.target.closest('.candidate-profile-menu__sub-link');

                if (subLink && isCandidateProfileMobile()) {
                    closeCandidateProfileSubDropdown(menu);
                }
            });

            document.addEventListener('click', function (event) {
                if (isCandidateProfileMobile() && !menu.contains(event.target)) {
                    closeCandidateProfileSubDropdown(menu);
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeCandidateProfileSubDropdown(menu);
                }
            });

            window.addEventListener('resize', function () {
                if (!isCandidateProfileMobile()) {
                    closeCandidateProfileSubDropdown(menu);
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCandidateProfileMobileMenu, { once: true });
        } else {
            initCandidateProfileMobileMenu();
        }
    })();
</script>
