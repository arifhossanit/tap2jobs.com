@php
    $iconPad = checkLanguageSession() == 'ar' ? 'ps-3' : 'pe-3';
    $employersActive = Request::is('admin/employers*', 'admin/reported-employers*');
    $candidatesActive = Request::is('admin/candidates*', 'admin/reported-candidates*', 'admin/resumes*', 'admin/selected-candidate*');
    $jobsActive = Request::is('admin/jobs*', 'admin/pending-jobs*', 'admin/reported-jobs*', 'admin/job-notification*', 'admin/expired-jobs*')
        && ! Request::is('admin/job-categories*', 'admin/job-types*', 'admin/job-tags*', 'admin/job-shifts*');
    $blogsActive = Request::is('admin/post-categories*', 'admin/posts*', 'admin/post-comments*');
    $subscriptionsActive = Request::is('admin/plans*', 'admin/transactions*');
    $countriesActive = Request::is('admin/countries*', 'admin/divisions*', 'admin/districts*', 'admin/thanas*', 'admin/states*', 'admin/cities*');
    $educationActive = Request::is('admin/degree-levels*', 'admin/education-degree-titles*', 'admin/education-major-groups*', 'admin/education-boards*', 'admin/education-results*', 'admin/profile-references/candidate/education_result*');
    $armyActive = Request::is('admin/army-ba-no-prefixes*', 'admin/army-ranks*', 'admin/army-employment-types*', 'admin/army-arms*', 'admin/profile-references/candidate/army_ba_no_prefix*', 'admin/profile-references/candidate/army_rank*', 'admin/profile-references/candidate/army_employment_type*', 'admin/profile-references/candidate/army_arms*');
    $profileReferenceMenuGroups = \App\Models\ProfileReferenceOption::menuGroups();
    $profileReferenceTypeLabels = \App\Models\ProfileReferenceOption::typeLabels();
    $commonReferenceRoutes = \App\Models\ProfileReferenceOption::commonDedicatedRouteNames();
    $candidateReferenceRoutes = \App\Models\ProfileReferenceOption::candidateDedicatedRouteNames();
    $employerReferenceRoutes = \App\Models\ProfileReferenceOption::employerDedicatedRouteNames();
    $commonReferencePaths = [
        \App\Models\ProfileReferenceOption::TYPE_GENDER => 'genders',
        \App\Models\ProfileReferenceOption::TYPE_LANGUAGE_PROFICIENCY => 'language-proficiencies',
        \App\Models\ProfileReferenceOption::TYPE_ONLINE_PROFILE_PLATFORM => 'online-profile-platforms',
    ];
    $candidateReferencePaths = [
        \App\Models\ProfileReferenceOption::TYPE_RELIGION => 'candidate-religions',
        \App\Models\ProfileReferenceOption::TYPE_BLOOD_GROUP => 'blood-groups',
        \App\Models\ProfileReferenceOption::TYPE_DISABILITY_DIFFICULTY => 'disability-difficulties',
        \App\Models\ProfileReferenceOption::TYPE_SKILL_LEARNING_SOURCE => 'skill-learning-sources',
        \App\Models\ProfileReferenceOption::TYPE_REFERENCE_RELATION => 'candidate-reference-relations',
        \App\Models\ProfileReferenceOption::TYPE_EDUCATION_RESULT => 'education-results',
        \App\Models\ProfileReferenceOption::TYPE_ARMY_BA_NO_PREFIX => 'army-ba-no-prefixes',
        \App\Models\ProfileReferenceOption::TYPE_ARMY_RANK => 'army-ranks',
        \App\Models\ProfileReferenceOption::TYPE_ARMY_EMPLOYMENT_TYPE => 'army-employment-types',
        \App\Models\ProfileReferenceOption::TYPE_ARMY_ARMS => 'army-arms',
    ];
    $employerReferencePaths = [
        \App\Models\ProfileReferenceOption::TYPE_REFERENCE_RELATION => 'employer-reference-relations',
        \App\Models\ProfileReferenceOption::TYPE_JOB_GENDER_PREFERENCE => 'job-gender-preferences',
        \App\Models\ProfileReferenceOption::TYPE_JOB_EMPLOYMENT_STATUS => 'job-employment-statuses',
        \App\Models\ProfileReferenceOption::TYPE_JOB_WORKPLACE => 'job-workplaces',
        \App\Models\ProfileReferenceOption::TYPE_JOB_EXPERIENCE_UNIT => 'job-experience-units',
        \App\Models\ProfileReferenceOption::TYPE_EMPLOYER_DISABILITY_FACILITY => 'employer-disability-facilities',
    ];
    $referenceGeneralActive = Request::is('admin/profile-references/common*', 'admin/genders*', 'admin/language-proficiencies*', 'admin/online-profile-platforms*', 'admin/countries*', 'admin/divisions*', 'admin/districts*', 'admin/thanas*', 'admin/states*', 'admin/cities*', 'admin/degree-levels*', 'admin/skills*', 'admin/industries*', 'admin/functional-areas*', 'admin/career-levels*', 'admin/salary-currencies*', 'admin/ownership-types*');
    $referenceCandidateActive = Request::is('admin/profile-references/candidate*', 'admin/education-degree-titles*', 'admin/education-major-groups*', 'admin/education-boards*', 'admin/education-results*', 'admin/candidate-religions*', 'admin/blood-groups*', 'admin/disability-difficulties*', 'admin/skill-learning-sources*', 'admin/candidate-reference-relations*', 'admin/army-ba-no-prefixes*', 'admin/army-ranks*', 'admin/army-employment-types*', 'admin/army-arms*', 'admin/marital-status*', 'admin/languages*');
    $referenceEmployerActive = Request::is('admin/profile-references/employer*', 'admin/employer-reference-relations*', 'admin/job-gender-preferences*', 'admin/job-employment-statuses*', 'admin/job-workplaces*', 'admin/job-experience-units*', 'admin/employer-disability-facilities*', 'admin/job-categories*', 'admin/job-types*', 'admin/job-tags*', 'admin/job-shifts*', 'admin/salary-periods*', 'admin/company-sizes*');
    $referencesActive = $referenceGeneralActive || $referenceCandidateActive || $referenceEmployerActive;
    $cmsActive = Request::is('admin/noticeboards*', 'admin/faqs*', 'admin/inquires*', 'admin/notification-settings*', 'admin/privacy-policy*', 'admin/front-settings*', 'admin/email-template*', 'admin/settings*');
    $cmsSlidersActive = Request::is('admin/testimonials*', 'admin/branding-sliders*', 'admin/header-sliders*', 'admin/image-sliders*', 'admin/ads*');
    $frontCmsActive = Request::is('admin/cms-services*', 'admin/cms-about-us*');
@endphp

<!-- SECTION: MAIN -->
<li class="sidebar-section-header px-4 pt-3 pb-1 text-uppercase text-muted fw-bold fs-8 d-flex align-items-center" style="letter-spacing: 0.08em; font-size: 11px;">
    <span>MAIN</span>
    <span class="flex-grow-1 ms-3 border-bottom"></span>
</li>

<li class="nav-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('admin.dashboard') }}">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fas fa fa-digital-tachograph"></i></span>
        <span class="aside-menu-title">{{ __('messages.dashboard') }}</span>
    </a>
</li>

<!-- SECTION: USER MANAGEMENT -->
<li class="sidebar-section-header px-4 pt-4 pb-1 text-uppercase text-muted fw-bold fs-8 d-flex align-items-center" style="letter-spacing: 0.08em; font-size: 11px;">
    <span>MANAGEMENT</span>
    <span class="flex-grow-1 ms-3 border-bottom"></span>
</li>

<li class="nav-item aside-item-collapse {{ $employersActive ? 'active collapse-submenu' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" data-bs-toggle="collapse" href="#asideEmployersMenu"
       role="button" aria-expanded="{{ $employersActive ? 'true' : 'false' }}" aria-controls="asideEmployersMenu">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fas fa-user-friends"></i></span>
        <span class="aside-menu-title">{{ __('messages.employers') }}</span>
        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
    </a>
    <ul class="aside-submenu nav flex-column collapse {{ $employersActive ? 'show' : '' }} ps-4 ms-2 border-start opacity-75" id="asideEmployersMenu">
        <li class="nav-item {{ Request::is('admin/employers*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('company.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.employers') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/reported-employers*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('reported.companies') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.company.reported_employers') }}</span>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item {{ Request::is('admin/admin*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('admin.index') }}">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fa-solid fa-user-tie"></i></span>
        <span class="aside-menu-title">{{ __('messages.candidate.admins') }}</span>
    </a>
</li>

<li class="nav-item aside-item-collapse {{ $candidatesActive ? 'active collapse-submenu' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" data-bs-toggle="collapse" href="#asideCandidatesMenu"
       role="button" aria-expanded="{{ $candidatesActive ? 'true' : 'false' }}" aria-controls="asideCandidatesMenu">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fas fa-user-circle"></i></span>
        <span class="aside-menu-title">{{ __('messages.candidates') }}</span>
        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
    </a>
    <ul class="aside-submenu nav flex-column collapse {{ $candidatesActive ? 'show' : '' }} ps-4 ms-2 border-start opacity-75" id="asideCandidatesMenu">
        <li class="nav-item {{ Request::is('admin/candidates*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('candidates.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.candidates') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/reported-candidates*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('reported.candidates') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.candidate.reported_candidates') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/resumes*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('resumes.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.all_resumes') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/selected-candidate*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('selected.candidate') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.selected_candidate') }}</span>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item aside-item-collapse {{ $jobsActive ? 'active collapse-submenu' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" data-bs-toggle="collapse" href="#asideJobsMenu"
       role="button" aria-expanded="{{ $jobsActive ? 'true' : 'false' }}" aria-controls="asideJobsMenu">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fas fa-briefcase"></i></span>
        <span class="aside-menu-title">{{ __('messages.jobs') }}</span>
        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
    </a>
    <ul class="aside-submenu nav flex-column collapse {{ $jobsActive ? 'show' : '' }} ps-4 ms-2 border-start opacity-75" id="asideJobsMenu">
        <li class="nav-item {{ Request::is('admin/jobs*') && !Request::is('admin/job-categories*', 'admin/job-types*', 'admin/job-tags*', 'admin/job-shifts*', 'admin/job-notification*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('admin.jobs.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.jobs') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/pending-jobs*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('admin.PendingJobs.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.pending_jobs.pending_jobs') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/reported-jobs*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('reported.jobs') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.reported_jobs') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/job-notification*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('job-notification.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.job_notification.job_notifications') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/expired-jobs*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('admin.jobs.expiredJobs') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.expired_jobs') }}</span>
            </a>
        </li>
    </ul>
</li>

<!-- SECTION: SUBSCRIPTIONS & REFERENCES -->
<li class="sidebar-section-header px-4 pt-4 pb-1 text-uppercase text-muted fw-bold fs-8 d-flex align-items-center" style="letter-spacing: 0.08em; font-size: 11px;">
    <span>REFERENCES & DATA</span>
    <span class="flex-grow-1 ms-3 border-bottom"></span>
</li>

<li class="nav-item aside-item-collapse {{ $subscriptionsActive ? 'active collapse-submenu' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" data-bs-toggle="collapse" href="#asideSubscriptionsMenu"
       role="button" aria-expanded="{{ $subscriptionsActive ? 'true' : 'false' }}" aria-controls="asideSubscriptionsMenu">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fab fa-bandcamp"></i></span>
        <span class="aside-menu-title">{{ __('messages.plan.subscriptions') }}</span>
        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
    </a>
    <ul class="aside-submenu nav flex-column collapse {{ $subscriptionsActive ? 'show' : '' }} ps-4 ms-2 border-start opacity-75" id="asideSubscriptionsMenu">
        <li class="nav-item {{ Request::is('admin/plans*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('plans.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.subscriptions_plans') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/transactions*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('admin.transactions.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.transactions') }}</span>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item {{ Request::is('admin/subscribers*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('subscribers.index') }}">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fas fa-bell"></i></span>
        <span class="aside-menu-title">{{ __('messages.subscribers') }}</span>
    </a>
</li>

<li class="nav-item aside-item-collapse {{ $referencesActive ? 'active collapse-submenu' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" data-bs-toggle="collapse" href="#asideReferencesMenu"
       role="button" aria-expanded="{{ $referencesActive ? 'true' : 'false' }}" aria-controls="asideReferencesMenu">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fas fa-layer-group"></i></span>
        <span class="aside-menu-title">{{ __('messages.references') }}</span>
        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
    </a>
    <ul class="aside-submenu nav flex-column collapse {{ $referencesActive ? 'show' : '' }} ps-4 ms-2 border-start opacity-75" id="asideReferencesMenu">
        <li class="nav-item aside-item-collapse {{ $referenceGeneralActive ? 'active collapse-submenu' : '' }}">
            <a class="nav-link d-flex align-items-center py-2 pe-3" data-bs-toggle="collapse" href="#asideReferenceGeneralMenu"
               role="button" aria-expanded="{{ $referenceGeneralActive ? 'true' : 'false' }}" aria-controls="asideReferenceGeneralMenu">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title me-auto">General</span>
                <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
            </a>
            <ul class="aside-submenu nav flex-column collapse {{ $referenceGeneralActive ? 'show' : '' }} ps-4 ms-2 border-start opacity-75" id="asideReferenceGeneralMenu">
                <li class="nav-item aside-item-collapse {{ $countriesActive ? 'active collapse-submenu' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2 pe-3" data-bs-toggle="collapse" href="#asideLocationMenu"
                       role="button" aria-expanded="{{ $countriesActive ? 'true' : 'false' }}" aria-controls="asideLocationMenu">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title me-auto">{{ __('messages.country.locations') }}</span>
                        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
                    </a>
                    <ul class="aside-submenu nav flex-column collapse {{ $countriesActive ? 'show' : '' }} ps-4 ms-2 border-start opacity-75" id="asideLocationMenu">
                        <li class="nav-item {{ Request::is('admin/countries*') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center py-2" href="{{ route('countries.index') }}">
                                <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                                <span class="aside-menu-title">{{ __('messages.country.countries') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::is('admin/divisions*', 'admin/states*') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center py-2" href="{{ route('states.index') }}">
                                <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                                <span class="aside-menu-title">{{ __('messages.state.states') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::is('admin/districts*', 'admin/cities*') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center py-2" href="{{ route('cities.index') }}">
                                <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                                <span class="aside-menu-title">{{ __('messages.city.cities') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::is('admin/thanas*') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center py-2" href="{{ route('thanas.index') }}">
                                <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                                <span class="aside-menu-title">{{ __('messages.thana.thanas') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @foreach($profileReferenceMenuGroups[\App\Models\ProfileReferenceOption::SCOPE_COMMON] ?? [] as $profileReferenceType)
                    <li class="nav-item {{ Request::is('admin/'.($commonReferencePaths[$profileReferenceType] ?? 'profile-references/common/'.$profileReferenceType).'*', 'admin/profile-references/common/'.$profileReferenceType) ? 'active' : '' }}">
                        <a class="nav-link d-flex align-items-center py-2" href="{{ isset($commonReferenceRoutes[$profileReferenceType]) ? route($commonReferenceRoutes[$profileReferenceType].'.index') : route('profileReferenceOptions.index', [\App\Models\ProfileReferenceOption::SCOPE_COMMON, $profileReferenceType]) }}">
                            <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                            <span class="aside-menu-title">{{ $profileReferenceTypeLabels[$profileReferenceType] ?? $profileReferenceType }}</span>
                        </a>
                    </li>
                @endforeach
                <li class="nav-item {{ Request::is('admin/degree-levels*') ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2" href="{{ route('requiredDegreeLevel.index') }}">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title">{{ __('messages.required_degree_levels') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/skills*') ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2" href="{{ route('skills.index') }}">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title">{{ __('messages.skills') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/industries*') ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2" href="{{ route('industry.index') }}">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title">{{ __('messages.industries') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/functional-areas*') ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2" href="{{ route('functionalArea.index') }}">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title">{{ __('messages.functional_areas') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/career-levels*') ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2" href="{{ route('careerLevel.index') }}">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title">{{ __('messages.career_levels') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/salary-currencies*') ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2" href="{{ route('salaryCurrency.index') }}">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title">{{ __('messages.salary_currencies') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/ownership-types*') ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2" href="{{ route('ownerShipType.index') }}">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title">{{ __('messages.ownership_types') }}</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item aside-item-collapse {{ $referenceCandidateActive ? 'active collapse-submenu' : '' }}">
            <a class="nav-link d-flex align-items-center py-2 pe-3" data-bs-toggle="collapse" href="#asideReferenceCandidateMenu"
               role="button" aria-expanded="{{ $referenceCandidateActive ? 'true' : 'false' }}" aria-controls="asideReferenceCandidateMenu">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title me-auto">Candidate</span>
                <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
            </a>
            <ul class="aside-submenu nav flex-column collapse {{ $referenceCandidateActive ? 'show' : '' }} ps-4 ms-2 border-start opacity-75" id="asideReferenceCandidateMenu">
                <li class="nav-item aside-item-collapse {{ $educationActive ? 'active collapse-submenu' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2 pe-3" data-bs-toggle="collapse" href="#asideEducationMenu"
                       role="button" aria-expanded="{{ $educationActive ? 'true' : 'false' }}" aria-controls="asideEducationMenu">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title me-auto">{{ __('messages.candidate_profile.education') }}</span>
                        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
                    </a>
                    <ul class="aside-submenu nav flex-column collapse {{ $educationActive ? 'show' : '' }} ps-4 ms-2 border-start opacity-75" id="asideEducationMenu">
                        <li class="nav-item {{ Request::is('admin/education-degree-titles*') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center py-2" href="{{ route('educationDegreeTitles.index') }}">
                                <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                                <span class="aside-menu-title">Degree Titles</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::is('admin/education-major-groups*') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center py-2" href="{{ route('educationMajorGroups.index') }}">
                                <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                                <span class="aside-menu-title">Major / Groups</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::is('admin/education-boards*') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center py-2" href="{{ route('educationBoards.index') }}">
                                <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                                <span class="aside-menu-title">Education Boards</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::is('admin/education-results*', 'admin/profile-references/candidate/education_result*') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center py-2" href="{{ route('educationResults.index') }}">
                                <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                                <span class="aside-menu-title">{{ $profileReferenceTypeLabels[\App\Models\ProfileReferenceOption::TYPE_EDUCATION_RESULT] ?? 'Education Result' }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ Request::is('admin/marital-status*') ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2" href="{{ route('maritalStatus.index') }}">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title">{{ __('messages.marital_statuses') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/languages*') ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2" href="{{ route('languages.index') }}">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title">{{ __('messages.languages') }}</span>
                    </a>
                </li>
                <li class="nav-item aside-item-collapse {{ $armyActive ? 'active collapse-submenu' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2 pe-3" data-bs-toggle="collapse" href="#asideArmyMenu"
                       role="button" aria-expanded="{{ $armyActive ? 'true' : 'false' }}" aria-controls="asideArmyMenu">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title me-auto">Army info</span>
                        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
                    </a>
                    <ul class="aside-submenu nav flex-column collapse {{ $armyActive ? 'show' : '' }} ps-4 ms-2 border-start opacity-75" id="asideArmyMenu">
                        <li class="nav-item {{ Request::is('admin/army-ba-no-prefixes*', 'admin/profile-references/candidate/army_ba_no_prefix*') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center py-2" href="{{ route($candidateReferenceRoutes[\App\Models\ProfileReferenceOption::TYPE_ARMY_BA_NO_PREFIX].'.index') }}">
                                <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                                <span class="aside-menu-title">{{ $profileReferenceTypeLabels[\App\Models\ProfileReferenceOption::TYPE_ARMY_BA_NO_PREFIX] ?? 'BA No Prefix' }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::is('admin/army-ranks*', 'admin/profile-references/candidate/army_rank*') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center py-2" href="{{ route($candidateReferenceRoutes[\App\Models\ProfileReferenceOption::TYPE_ARMY_RANK].'.index') }}">
                                <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                                <span class="aside-menu-title">{{ $profileReferenceTypeLabels[\App\Models\ProfileReferenceOption::TYPE_ARMY_RANK] ?? 'Rank' }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::is('admin/army-employment-types*', 'admin/profile-references/candidate/army_employment_type*') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center py-2" href="{{ route($candidateReferenceRoutes[\App\Models\ProfileReferenceOption::TYPE_ARMY_EMPLOYMENT_TYPE].'.index') }}">
                                <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                                <span class="aside-menu-title">{{ $profileReferenceTypeLabels[\App\Models\ProfileReferenceOption::TYPE_ARMY_EMPLOYMENT_TYPE] ?? 'Employment Type' }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::is('admin/army-arms*', 'admin/profile-references/candidate/army_arms*') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center py-2" href="{{ route($candidateReferenceRoutes[\App\Models\ProfileReferenceOption::TYPE_ARMY_ARMS].'.index') }}">
                                <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                                <span class="aside-menu-title">{{ $profileReferenceTypeLabels[\App\Models\ProfileReferenceOption::TYPE_ARMY_ARMS] ?? 'Arms' }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @php
                    $armyTypes = [
                        \App\Models\ProfileReferenceOption::TYPE_ARMY_BA_NO_PREFIX,
                        \App\Models\ProfileReferenceOption::TYPE_ARMY_RANK,
                        \App\Models\ProfileReferenceOption::TYPE_ARMY_EMPLOYMENT_TYPE,
                        \App\Models\ProfileReferenceOption::TYPE_ARMY_ARMS,
                    ];
                @endphp
                @foreach($profileReferenceMenuGroups[\App\Models\ProfileReferenceOption::SCOPE_CANDIDATE] ?? [] as $profileReferenceType)
                    @if($profileReferenceType === \App\Models\ProfileReferenceOption::TYPE_EDUCATION_RESULT || in_array($profileReferenceType, $armyTypes, true))
                        @continue
                    @endif
                    <li class="nav-item {{ Request::is('admin/'.($candidateReferencePaths[$profileReferenceType] ?? 'profile-references/candidate/'.$profileReferenceType).'*', 'admin/profile-references/candidate/'.$profileReferenceType) ? 'active' : '' }}">
                        <a class="nav-link d-flex align-items-center py-2" href="{{ isset($candidateReferenceRoutes[$profileReferenceType]) ? route($candidateReferenceRoutes[$profileReferenceType].'.index') : route('profileReferenceOptions.index', [\App\Models\ProfileReferenceOption::SCOPE_CANDIDATE, $profileReferenceType]) }}">
                            <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                            <span class="aside-menu-title">{{ $profileReferenceTypeLabels[$profileReferenceType] ?? $profileReferenceType }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>

        <li class="nav-item aside-item-collapse {{ $referenceEmployerActive ? 'active collapse-submenu' : '' }}">
            <a class="nav-link d-flex align-items-center py-2 pe-3" data-bs-toggle="collapse" href="#asideReferenceEmployerMenu"
               role="button" aria-expanded="{{ $referenceEmployerActive ? 'true' : 'false' }}" aria-controls="asideReferenceEmployerMenu">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title me-auto">Employer</span>
                <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
            </a>
            <ul class="aside-submenu nav flex-column collapse {{ $referenceEmployerActive ? 'show' : '' }} ps-4 ms-2 border-start opacity-75" id="asideReferenceEmployerMenu">
                <li class="nav-item {{ Request::is('admin/job-categories*') ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2" href="{{ route('job-categories.index') }}">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title">{{ __('messages.job_categories') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/job-types*') ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2" href="{{ route('jobType.index') }}">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title">{{ __('messages.job_types') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/job-shifts*') ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2" href="{{ route('jobShift.index') }}">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title">{{ __('messages.job_shifts') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/job-tags*') ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2" href="{{ route('jobTag.index') }}">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title">{{ __('messages.job_tags') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/salary-periods*') ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2" href="{{ route('salaryPeriod.index') }}">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title">{{ __('messages.salary_periods') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/company-sizes*') ? 'active' : '' }}">
                    <a class="nav-link d-flex align-items-center py-2" href="{{ route('companySize.index') }}">
                        <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                        <span class="aside-menu-title">{{ __('messages.company_sizes') }}</span>
                    </a>
                </li>
                @foreach($profileReferenceMenuGroups[\App\Models\ProfileReferenceOption::SCOPE_EMPLOYER] ?? [] as $profileReferenceType)
                    <li class="nav-item {{ Request::is('admin/'.($employerReferencePaths[$profileReferenceType] ?? 'profile-references/employer/'.$profileReferenceType).'*', 'admin/profile-references/employer/'.$profileReferenceType) ? 'active' : '' }}">
                        <a class="nav-link d-flex align-items-center py-2" href="{{ isset($employerReferenceRoutes[$profileReferenceType]) ? route($employerReferenceRoutes[$profileReferenceType].'.index') : route('profileReferenceOptions.index', [\App\Models\ProfileReferenceOption::SCOPE_EMPLOYER, $profileReferenceType]) }}">
                            <i class="fa-solid fa-angle-right me-2 text-muted fs-8"></i>
                            <span class="aside-menu-title">{{ $profileReferenceTypeLabels[$profileReferenceType] ?? $profileReferenceType }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
</li>

<!-- SECTION: CONTENT & CMS -->
<li class="sidebar-section-header px-4 pt-4 pb-1 text-uppercase text-muted fw-bold fs-8 d-flex align-items-center" style="letter-spacing: 0.08em; font-size: 11px;">
    <span>CONTENT & CMS</span>
    <span class="flex-grow-1 ms-3 border-bottom"></span>
</li>

<li class="nav-item aside-item-collapse {{ $blogsActive ? 'active collapse-submenu' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" data-bs-toggle="collapse" href="#asideBlogsMenu"
       role="button" aria-expanded="{{ $blogsActive ? 'true' : 'false' }}" aria-controls="asideBlogsMenu">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="far fa-list-alt"></i></span>
        <span class="aside-menu-title">{{ __('messages.blogs') }}</span>
        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
    </a>
    <ul class="aside-submenu nav flex-column collapse {{ $blogsActive ? 'show' : '' }} ps-4 ms-2 border-start opacity-75" id="asideBlogsMenu">
        <li class="nav-item {{ Request::is('admin/post-categories*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('post-categories.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.post_category.post_categories') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/posts*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('posts.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.post.posts') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/post-comments*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('post.comments') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.post_comments') }}</span>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item aside-item-collapse {{ $cmsActive ? 'active collapse-submenu' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" data-bs-toggle="collapse" href="#asideCmsMenu"
       role="button" aria-expanded="{{ $cmsActive ? 'true' : 'false' }}" aria-controls="asideCmsMenu">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fas fa-sticky-note"></i></span>
        <span class="aside-menu-title">{{ __('messages.cms') }}</span>
        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
    </a>
    <ul class="aside-submenu nav flex-column collapse {{ $cmsActive ? 'show' : '' }} ps-4 ms-2 border-start opacity-75" id="asideCmsMenu">
        <li class="nav-item {{ Request::is('admin/noticeboards*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('noticeboards.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.noticeboards') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/faqs*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('faqs.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.faq.faq') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/inquires*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('inquires.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.inquires') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/notification-settings*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('notification.settings.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.setting.notification_settings') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/privacy-policy*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('privacy.policy.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.setting.privacy_policy') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/front-settings*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('front.settings.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.setting.front_settings') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/email-template*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('email.template.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.email_templates') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/settings*') && !Request::is('admin/notification-settings*', 'admin/front-settings*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('settings.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.settings') }}</span>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item aside-item-collapse {{ $cmsSlidersActive ? 'active collapse-submenu' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" data-bs-toggle="collapse" href="#asideCmsSlidersMenu"
       role="button" aria-expanded="{{ $cmsSlidersActive ? 'true' : 'false' }}" aria-controls="asideCmsSlidersMenu">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fas fa-images"></i></span>
        <span class="aside-menu-title">{{ __('messages.cms_sliders') }}</span>
        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
    </a>
    <ul class="aside-submenu nav flex-column collapse {{ $cmsSlidersActive ? 'show' : '' }} ps-4 ms-2 border-start opacity-75" id="asideCmsSlidersMenu">
        <li class="nav-item {{ Request::is('admin/testimonials*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('testimonials.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.testimonials') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/branding-sliders*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('branding.sliders.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.branding_sliders') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/header-sliders*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('header.sliders.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.header_sliders') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/image-sliders*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('image-sliders.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.image_sliders') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/ads*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('ads.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.ads') }}</span>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item aside-item-collapse {{ $frontCmsActive ? 'active collapse-submenu' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" data-bs-toggle="collapse" href="#asideFrontCmsMenu"
       role="button" aria-expanded="{{ $frontCmsActive ? 'true' : 'false' }}" aria-controls="asideFrontCmsMenu">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fas fa-desktop"></i></span>
        <span class="aside-menu-title">{{ __('messages.front_cms') }}</span>
        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
    </a>
    <ul class="aside-submenu nav flex-column collapse {{ $frontCmsActive ? 'show' : '' }} ps-4 ms-2 border-start opacity-75" id="asideFrontCmsMenu">
        <li class="nav-item {{ Request::is('admin/cms-services*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('cms.services.index') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.cms_services') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/cms-about-us*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('cms.about-us.service') }}">
                <i class="fa-solid fa-circle me-2" style="font-size: 7px;"></i>
                <span class="aside-menu-title">{{ __('messages.about_us_services') }}</span>
            </a>
        </li>
    </ul>
</li>
