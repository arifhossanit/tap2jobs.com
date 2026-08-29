@php
    $sectionName = $data['sectionName'] ?? 'personal-information';
    $profileCompletion = $data['profileCompletion'] ?? ['percentage' => 0, 'completed' => 0, 'total' => 11, 'color' => '#f04438'];
    $completionPercentage = max(0, min(100, (int) ($profileCompletion['percentage'] ?? 0)));
    $completionColor = $profileCompletion['color'] ?? '#f04438';
    $missingProfileItems = collect($profileCompletion['missing'] ?? [])->take(8);
@endphp

<div class="candidate-profile-menu">
    <div class="candidate-profile-menu__top-shell">
        <button type="button" class="candidate-profile-menu__scroll candidate-profile-menu__scroll--prev"
                data-candidate-profile-scroll="prev" aria-label="@lang('pagination.previous')">
            <i class="fa-solid fa-angle-left"></i>
        </button>
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
        <button type="button" class="candidate-profile-menu__scroll candidate-profile-menu__scroll--next"
                data-candidate-profile-scroll="next" aria-label="@lang('pagination.next')">
            <i class="fa-solid fa-angle-right"></i>
        </button>
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

<style>
    .candidate-profile-menu__top-shell {
        position: relative;
    }

    .candidate-profile-menu__scroll {
        display: none;
    }

    @media (max-width: 991.98px) {
        .candidate-profile-menu-shell {
            margin-left: -12px;
            margin-right: -12px;
        }

        .candidate-profile-menu {
            border-left: 0 !important;
            border-radius: 0;
            border-right: 0 !important;
            box-shadow: 0 4px 14px rgba(16, 24, 40, 0.08);
            margin-bottom: 6px;
            overflow: visible;
        }

        .candidate-profile-menu__top-shell {
            padding: 0 32px;
        }

        .candidate-profile-menu__top {
            display: flex !important;
            gap: 0 !important;
            grid-template-columns: none !important;
            min-height: 46px;
            overflow-x: auto;
            overflow-y: hidden;
            padding: 0 !important;
            scroll-behavior: smooth;
            scroll-padding-left: 0;
            scroll-snap-type: x proximity;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
        }

        .candidate-profile-menu__top::-webkit-scrollbar {
            display: none;
        }

        .candidate-profile-menu__main-link {
            background: #ffffff;
            border: 0 !important;
            border-radius: 0 !important;
            border-right: 1px solid #edf0f4 !important;
            color: #6b7280;
            flex: 0 0 auto;
            font-size: 16px;
            font-weight: 600;
            gap: 10px;
            justify-content: flex-start;
            min-height: 52px;
            min-width: 208px;
            padding: 0 16px;
            scroll-snap-align: start;
            text-align: left;
            white-space: nowrap;
        }

        .candidate-profile-menu__main-link:after {
            color: currentColor;
            content: "\f107";
            font-family: "Font Awesome 6 Free", "Font Awesome 5 Free";
            font-size: 13px;
            font-weight: 900;
            margin-left: auto;
        }

        .candidate-profile-menu__main-link i {
            color: currentColor;
            flex: 0 0 auto;
            font-size: 16px;
        }

        .candidate-profile-menu__main-link span {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .candidate-profile-menu__main-link:hover,
        .candidate-profile-menu__main-link.active {
            color: #209776;
        }

        .candidate-profile-menu__main-link.active {
            background: #fff4fa;
            border-color: #f5d4e6;
        }

        .candidate-profile-menu__main-link.active:after {
            content: "\f106";
        }

        .candidate-profile-menu__scroll {
            align-items: center;
            background: rgba(255, 255, 255, .86);
            border: 0;
            bottom: 0;
            color: #0f1b3d;
            display: inline-flex;
            justify-content: center;
            opacity: .72;
            position: absolute;
            top: 0;
            transition: opacity .16s ease, color .16s ease;
            width: 32px;
            z-index: 5;
        }

        .candidate-profile-menu__scroll:hover,
        .candidate-profile-menu__scroll:focus {
            color: #209776;
            opacity: .95;
        }

        .candidate-profile-menu__scroll:disabled {
            cursor: default;
            opacity: .28;
        }

        .candidate-profile-menu__scroll--prev {
            left: 0;
            box-shadow: 10px 0 16px rgba(255, 255, 255, .9);
        }

        .candidate-profile-menu__scroll--next {
            right: 0;
            box-shadow: -10px 0 16px rgba(255, 255, 255, .9);
        }

        .candidate-profile-menu__sub {
            background: #ffffff;
            border: 1px solid #e5eaf1 !important;
            border-radius: 0 0 6px 6px;
            box-shadow: 0 12px 24px rgba(16, 24, 40, 0.12);
            display: grid;
            gap: 0;
            left: 10px;
            margin-top: 0;
            max-height: 0;
            min-height: 0;
            overflow: hidden;
            padding: 0;
            position: absolute;
            right: 10px;
            top: calc(100% + 4px);
            transition: max-height 0.22s ease, padding 0.22s ease;
            z-index: 40;
        }

        .candidate-profile-menu.is-sub-open .candidate-profile-menu__sub {
            max-height: 320px;
            overflow-y: auto;
            padding: 6px 0;
        }

        .candidate-profile-menu__sub-link {
            align-items: center;
            border-radius: 0;
            color: #344054;
            display: flex;
            flex: none;
            font-size: 14px;
            font-weight: 500;
            justify-content: flex-start;
            min-height: 40px;
            padding: 9px 14px;
            text-align: left;
            white-space: normal;
        }

        .candidate-profile-menu__sub-link:after {
            display: none;
        }

        .candidate-profile-menu__sub-link:hover,
        .candidate-profile-menu__sub-link.active {
            background: #fff4fa;
            color: #209776;
            font-weight: 600;
        }

        .candidate-profile-progress {
            padding: 7px 12px 5px;
        }
    }

    @media (max-width: 575.98px) {
        .candidate-profile-menu__top-shell {
            padding: 0 30px;
        }

        .candidate-profile-menu__main-link {
            font-size: 16px;
            min-height: 50px;
            min-width: 184px;
            padding: 0 14px;
        }

        .candidate-profile-menu__main-link i {
            font-size: 16px;
        }

        .candidate-profile-menu__scroll {
            width: 30px;
        }
    }
</style>

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
            return window.matchMedia('(max-width: 991.98px)').matches;
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
            const prevButton = menu.querySelector('[data-candidate-profile-scroll="prev"]');
            const nextButton = menu.querySelector('[data-candidate-profile-scroll="next"]');

            function updateScrollButtons() {
                if (!topTabs || !prevButton || !nextButton) {
                    return;
                }

                if (!isCandidateProfileMobile()) {
                    prevButton.disabled = true;
                    nextButton.disabled = true;
                    return;
                }

                const maxScroll = topTabs.scrollWidth - topTabs.clientWidth;
                prevButton.disabled = topTabs.scrollLeft <= 2;
                nextButton.disabled = topTabs.scrollLeft >= maxScroll - 2;
            }

            function scrollTopTabs(direction) {
                if (!topTabs) {
                    return;
                }

                const step = Math.max(topTabs.clientWidth * 0.72, 180);

                topTabs.scrollBy({
                    left: direction === 'next' ? step : -step,
                    behavior: 'smooth',
                });
            }

            if (topTabs && activeTab && isCandidateProfileMobile()) {
                topTabs.scrollLeft = Math.max(0, activeTab.offsetLeft - 32);
            }

            if (prevButton && nextButton) {
                prevButton.addEventListener('click', function () {
                    scrollTopTabs('prev');
                });

                nextButton.addEventListener('click', function () {
                    scrollTopTabs('next');
                });
            }

            if (topTabs) {
                topTabs.addEventListener('scroll', function () {
                    window.requestAnimationFrame(updateScrollButtons);
                }, { passive: true });
            }

            updateScrollButtons();

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
                } else if (topTabs && activeTab) {
                    topTabs.scrollLeft = Math.max(0, activeTab.offsetLeft - 32);
                }

                updateScrollButtons();
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCandidateProfileMobileMenu, { once: true });
        } else {
            initCandidateProfileMobileMenu();
        }
    })();
</script>
