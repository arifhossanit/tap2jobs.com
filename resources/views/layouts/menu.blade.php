@php
    $iconPad = checkLanguageSession() == 'ar' ? 'ps-3' : 'pe-3';
    $employersActive = Request::is('admin/employers*', 'admin/reported-employers*');
    $candidatesActive = Request::is('admin/candidates*', 'admin/degree-levels*', 'admin/reported-candidates*', 'admin/resumes*', 'admin/selected-candidate*');
    $jobsActive = Request::is('admin/jobs*', 'admin/pending-jobs*', 'admin/job-categories*', 'admin/job-types*', 'admin/job-tags*', 'admin/job-shifts*', 'admin/reported-jobs*', 'admin/job-notification*', 'admin/expired-jobs*');
    $blogsActive = Request::is('admin/post-categories*', 'admin/posts*', 'admin/post-comments*');
    $subscriptionsActive = Request::is('admin/plans*', 'admin/transactions*');
    $countriesActive = Request::is('admin/countries*', 'admin/states*', 'admin/cities*');
    $generalActive = Request::is('admin/marital-status*', 'admin/skills*', 'admin/salary-periods*', 'admin/industries*', 'admin/company-sizes*', 'admin/functional-areas*', 'admin/career-levels*', 'admin/salary-currencies*', 'admin/ownership-types*', 'admin/languages*');
    $cmsActive = Request::is('admin/noticeboards*', 'admin/faqs*', 'admin/inquires*', 'admin/notification-settings*', 'admin/privacy-policy*', 'admin/front-settings*', 'admin/email-template*', 'admin/settings*');
    $cmsSlidersActive = Request::is('admin/testimonials*', 'admin/branding-sliders*', 'admin/header-sliders*', 'admin/image-sliders*', 'admin/ads*');
    $frontCmsActive = Request::is('admin/cms-services*', 'admin/cms-about-us*');
@endphp

<li class="nav-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('admin.dashboard') }}">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fas fa fa-digital-tachograph"></i></span>
        <span class="aside-menu-title">{{ __('messages.dashboard') }}</span>
    </a>
</li>

<li class="nav-item aside-item-collapse {{ $employersActive ? 'active collapse-submenu' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" data-bs-toggle="collapse" href="#asideEmployersMenu"
       role="button" aria-expanded="{{ $employersActive ? 'true' : 'false' }}" aria-controls="asideEmployersMenu">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fas fa-user-friends"></i></span>
        <span class="aside-menu-title">{{ __('messages.employers') }}</span>
        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
    </a>
    <ul class="aside-submenu nav flex-column collapse {{ $employersActive ? 'show' : '' }}" id="asideEmployersMenu">
        <li class="nav-item {{ Request::is('admin/employers*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('company.index') }}">
                <span class="aside-menu-title">{{ __('messages.employers') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/reported-employers*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('reported.companies') }}">
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
    <ul class="aside-submenu nav flex-column collapse {{ $candidatesActive ? 'show' : '' }}" id="asideCandidatesMenu">
        <li class="nav-item {{ Request::is('admin/candidates*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('candidates.index') }}">
                <span class="aside-menu-title">{{ __('messages.candidates') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/degree-levels*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('requiredDegreeLevel.index') }}">
                <span class="aside-menu-title">{{ __('messages.required_degree_levels') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/reported-candidates*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('reported.candidates') }}">
                <span class="aside-menu-title">{{ __('messages.candidate.reported_candidates') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/resumes*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('resumes.index') }}">
                <span class="aside-menu-title">{{ __('messages.all_resumes') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/selected-candidate*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('selected.candidate') }}">
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
    <ul class="aside-submenu nav flex-column collapse {{ $jobsActive ? 'show' : '' }}" id="asideJobsMenu">
        <li class="nav-item {{ Request::is('admin/jobs*') && !Request::is('admin/job-categories*', 'admin/job-types*', 'admin/job-tags*', 'admin/job-shifts*', 'admin/job-notification*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('admin.jobs.index') }}">
                <span class="aside-menu-title">{{ __('messages.jobs') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/pending-jobs*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('admin.PendingJobs.index') }}">
                <span class="aside-menu-title">{{ __('messages.pending_jobs.pending_jobs') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/job-categories*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('job-categories.index') }}">
                <span class="aside-menu-title">{{ __('messages.job_categories') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/job-types*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('jobType.index') }}">
                <span class="aside-menu-title">{{ __('messages.job_types') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/job-tags*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('jobTag.index') }}">
                <span class="aside-menu-title">{{ __('messages.job_tags') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/job-shifts*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('jobShift.index') }}">
                <span class="aside-menu-title">{{ __('messages.job_shifts') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/reported-jobs*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('reported.jobs') }}">
                <span class="aside-menu-title">{{ __('messages.reported_jobs') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/job-notification*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('job-notification.index') }}">
                <span class="aside-menu-title">{{ __('messages.job_notification.job_notifications') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/expired-jobs*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('admin.jobs.expiredJobs') }}">
                <span class="aside-menu-title">{{ __('messages.expired_jobs') }}</span>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item aside-item-collapse {{ $blogsActive ? 'active collapse-submenu' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" data-bs-toggle="collapse" href="#asideBlogsMenu"
       role="button" aria-expanded="{{ $blogsActive ? 'true' : 'false' }}" aria-controls="asideBlogsMenu">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="far fa-list-alt"></i></span>
        <span class="aside-menu-title">{{ __('messages.blogs') }}</span>
        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
    </a>
    <ul class="aside-submenu nav flex-column collapse {{ $blogsActive ? 'show' : '' }}" id="asideBlogsMenu">
        <li class="nav-item {{ Request::is('admin/post-categories*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('post-categories.index') }}">
                <span class="aside-menu-title">{{ __('messages.post_category.post_categories') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/posts*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('posts.index') }}">
                <span class="aside-menu-title">{{ __('messages.post.posts') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/post-comments*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('post.comments') }}">
                <span class="aside-menu-title">{{ __('messages.post_comments') }}</span>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item aside-item-collapse {{ $subscriptionsActive ? 'active collapse-submenu' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" data-bs-toggle="collapse" href="#asideSubscriptionsMenu"
       role="button" aria-expanded="{{ $subscriptionsActive ? 'true' : 'false' }}" aria-controls="asideSubscriptionsMenu">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fab fa-bandcamp"></i></span>
        <span class="aside-menu-title">{{ __('messages.plan.subscriptions') }}</span>
        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
    </a>
    <ul class="aside-submenu nav flex-column collapse {{ $subscriptionsActive ? 'show' : '' }}" id="asideSubscriptionsMenu">
        <li class="nav-item {{ Request::is('admin/plans*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('plans.index') }}">
                <span class="aside-menu-title">{{ __('messages.subscriptions_plans') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/transactions*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('admin.transactions.index') }}">
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

<li class="nav-item aside-item-collapse {{ $countriesActive ? 'active collapse-submenu' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" data-bs-toggle="collapse" href="#asideCountriesMenu"
       role="button" aria-expanded="{{ $countriesActive ? 'true' : 'false' }}" aria-controls="asideCountriesMenu">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fas fa-globe-americas"></i></span>
        <span class="aside-menu-title">{{ __('messages.country.countries') }}</span>
        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
    </a>
    <ul class="aside-submenu nav flex-column collapse {{ $countriesActive ? 'show' : '' }}" id="asideCountriesMenu">
        <li class="nav-item {{ Request::is('admin/countries*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('countries.index') }}">
                <span class="aside-menu-title">{{ __('messages.country.countries') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/states*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('states.index') }}">
                <span class="aside-menu-title">{{ __('messages.state.states') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/cities*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('cities.index') }}">
                <span class="aside-menu-title">{{ __('messages.city.cities') }}</span>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item aside-item-collapse {{ $generalActive ? 'active collapse-submenu' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" data-bs-toggle="collapse" href="#asideGeneralMenu"
       role="button" aria-expanded="{{ $generalActive ? 'true' : 'false' }}" aria-controls="asideGeneralMenu">
        <span class="aside-menu-icon {{ $iconPad }}"><i class="fas fa-life-ring"></i></span>
        <span class="aside-menu-title">{{ __('messages.general') }}</span>
        <span class="aside-menu-collapse-icon ms-auto"><i class="fas fa-angle-right"></i></span>
    </a>
    <ul class="aside-submenu nav flex-column collapse {{ $generalActive ? 'show' : '' }}" id="asideGeneralMenu">
        <li class="nav-item {{ Request::is('admin/marital-status*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('maritalStatus.index') }}">
                <span class="aside-menu-title">{{ __('messages.marital_statuses') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/skills*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('skills.index') }}">
                <span class="aside-menu-title">{{ __('messages.skills') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/salary-periods*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('salaryPeriod.index') }}">
                <span class="aside-menu-title">{{ __('messages.salary_periods') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/industries*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('industry.index') }}">
                <span class="aside-menu-title">{{ __('messages.industries') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/company-sizes*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('companySize.index') }}">
                <span class="aside-menu-title">{{ __('messages.company_sizes') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/functional-areas*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('functionalArea.index') }}">
                <span class="aside-menu-title">{{ __('messages.functional_areas') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/career-levels*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('careerLevel.index') }}">
                <span class="aside-menu-title">{{ __('messages.career_levels') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/salary-currencies*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('salaryCurrency.index') }}">
                <span class="aside-menu-title">{{ __('messages.salary_currencies') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/ownership-types*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('ownerShipType.index') }}">
                <span class="aside-menu-title">{{ __('messages.ownership_types') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/languages*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('languages.index') }}">
                <span class="aside-menu-title">{{ __('messages.languages') }}</span>
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
    <ul class="aside-submenu nav flex-column collapse {{ $cmsActive ? 'show' : '' }}" id="asideCmsMenu">
        <li class="nav-item {{ Request::is('admin/noticeboards*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('noticeboards.index') }}">
                <span class="aside-menu-title">{{ __('messages.noticeboards') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/faqs*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('faqs.index') }}">
                <span class="aside-menu-title">{{ __('messages.faq.faq') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/inquires*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('inquires.index') }}">
                <span class="aside-menu-title">{{ __('messages.inquires') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/notification-settings*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('notification.settings.index') }}">
                <span class="aside-menu-title">{{ __('messages.setting.notification_settings') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/privacy-policy*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('privacy.policy.index') }}">
                <span class="aside-menu-title">{{ __('messages.setting.privacy_policy') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/front-settings*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('front.settings.index') }}">
                <span class="aside-menu-title">{{ __('messages.setting.front_settings') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/email-template*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('email.template.index') }}">
                <span class="aside-menu-title">{{ __('messages.email_templates') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/settings*') && !Request::is('admin/notification-settings*', 'admin/front-settings*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('settings.index') }}">
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
    <ul class="aside-submenu nav flex-column collapse {{ $cmsSlidersActive ? 'show' : '' }}" id="asideCmsSlidersMenu">
        <li class="nav-item {{ Request::is('admin/testimonials*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('testimonials.index') }}">
                <span class="aside-menu-title">{{ __('messages.testimonials') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/branding-sliders*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('branding.sliders.index') }}">
                <span class="aside-menu-title">{{ __('messages.branding_sliders') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/header-sliders*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('header.sliders.index') }}">
                <span class="aside-menu-title">{{ __('messages.header_sliders') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/image-sliders*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('image-sliders.index') }}">
                <span class="aside-menu-title">{{ __('messages.image_sliders') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/ads*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('ads.index') }}">
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
    <ul class="aside-submenu nav flex-column collapse {{ $frontCmsActive ? 'show' : '' }}" id="asideFrontCmsMenu">
        <li class="nav-item {{ Request::is('admin/cms-services*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('cms.services.index') }}">
                <span class="aside-menu-title">{{ __('messages.cms_services') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/cms-about-us*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-2" href="{{ route('cms.about-us.service') }}">
                <span class="aside-menu-title">{{ __('messages.about_us_services') }}</span>
            </a>
        </li>
    </ul>
</li>
