<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/dashboard*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/dashboard*') ? 'active' : '' }}"
       href="{{ route('admin.dashboard') }}">{{ __('messages.dashboard') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/employers*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/employers*') ? 'active' : '' }}"
       href="{{ route('company.index') }}">{{ __('messages.employers') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/reported-employers*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/reported-employers*') ? 'active' : '' }}"
       href="{{ route('reported.companies') }}">{{ __('messages.company.reported_employers') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/admins*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/admins*') ? 'active' : '' }}"
       href="{{ route('admin.index') }}">{{ __('messages.candidate.admins') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/candidates*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/candidates*') ? 'active' : '' }}"
       href="{{ route('candidates.index') }}">{{ __('messages.candidates') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/degree-levels*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/degree-levels*') ? 'active' : '' }}"
       href="{{ route('requiredDegreeLevel.index') }}">{{ __('messages.required_degree_levels') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/reported-candidates*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/reported-candidates*') ? 'active' : '' }}"
       href="{{ route('reported.candidates') }}">{{ __('messages.candidate.reported_candidates') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/resumes*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/resumes*') ? 'active' : '' }}"
       href="{{ route('resumes.index') }}">{{ __('messages.all_resumes') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/selected-candidates*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/selected-candidates*') ? 'active' : '' }}"
       href="{{ route('selected.candidate') }}">{{ __('messages.selected_candidate') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/jobs*') || Request::is('admin/pending-jobs*', 'admin/reported-jobs*', 'admin/job-notification*', 'admin/expired-jobs*', 'admin/job-categories*', 'admin/job-types*', 'admin/job-tags*', 'admin/job-shifts*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/jobs*') ? 'active' : '' }}"
       href="{{ route('admin.jobs.index') }}">{{ __('messages.jobs') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/pending-jobs*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/pending-jobs*') ? 'active' : '' }}"
       href="{{ route('admin.PendingJobs.index') }}">{{ __('messages.pending_jobs.pending_jobs') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/job-categories*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/job-categories*') ? 'active' : '' }}"
       href="{{ route('job-categories.index') }}">{{ __('messages.job_categories') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/job-types*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/job-types*') ? 'active' : '' }}"
       href="{{ route('jobType.index') }}">{{ __('messages.job_types') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/job-tags*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/job-tags*') ? 'active' : '' }}"
       href="{{ route('jobTag.index') }}">{{ __('messages.job_tags') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/job-shifts*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/job-shifts*') ? 'active' : '' }}"
       href="{{ route('jobShift.index') }}">{{ __('messages.job_shifts') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/reported-jobs*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/reported-jobs*') ? 'active' : '' }}"
       href="{{ route('reported.jobs') }}">{{ __('messages.reported_jobs') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/job-notification*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/job-notification*') ? 'active' : '' }}"
       href="{{ route('job-notification.index') }}">{{ __('messages.job_notification.job_notifications') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/expired-jobs*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/expired-jobs*') ? 'active' : '' }}"
       href="{{ route('admin.jobs.expiredJobs') }}">{{ __('messages.expired_jobs') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/post-categories*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/post-categories*') ? 'active' : '' }}"
       href="{{ route('post-categories.index') }}">{{ __('messages.post_category.post_categories') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/posts*') || Request::is('admin/post-categories*', 'admin/post-comments*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/posts*') ? 'active' : '' }}"
       href="{{ route('posts.index') }}">{{ __('messages.post.posts') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/post-comments*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/post-comments*') ? 'active' : '' }}"
       href="{{ route('post.comments') }}">{{ __('messages.post_comments') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/plans*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/plans*') ? 'active' : '' }}"
       href="{{ route('plans.index') }}">{{ __('messages.subscriptions_plans') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/transactions*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/transactions*') ? 'active' : '' }}"
       href="{{ route('admin.transactions.index') }}">{{ __('messages.transactions') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/subscribers*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/subscribers*') ? 'active' : '' }}"
       href="{{ route('subscribers.index') }}">{{ __('messages.subscribers') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/countries*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/countries*') ? 'active' : '' }}"
       href="{{ route('countries.index') }}">{{ __('messages.country.countries') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/states*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/states*') ? 'active' : '' }}"
       href="{{ route('states.index') }}">{{ __('messages.state.states') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/cities*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/cities*') ? 'active' : '' }}"
       href="{{ route('cities.index') }}">{{ __('messages.city.cities') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/profile-references/common*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/profile-references/common*') ? 'active' : '' }}"
       href="{{ route('profileReferenceOptions.index', [\App\Models\ProfileReferenceOption::SCOPE_COMMON, \App\Models\ProfileReferenceOption::TYPE_GENDER]) }}">General</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/profile-references/candidate*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/profile-references/candidate*') ? 'active' : '' }}"
       href="{{ route('profileReferenceOptions.index', [\App\Models\ProfileReferenceOption::SCOPE_CANDIDATE, \App\Models\ProfileReferenceOption::TYPE_RELIGION]) }}">Candidate</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/profile-references/employer*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/profile-references/employer*') ? 'active' : '' }}"
       href="{{ route('profileReferenceOptions.index', [\App\Models\ProfileReferenceOption::SCOPE_EMPLOYER, \App\Models\ProfileReferenceOption::TYPE_REFERENCE_RELATION]) }}">Employer</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/marital-status*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/marital-status*') ? 'active' : '' }}"
       href="{{ route('maritalStatus.index') }}">{{ __('messages.marital_statuses') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/skills*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/skills*') ? 'active' : '' }}"
       href="{{ route('skills.index') }}">{{ __('messages.skills') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/salary-periods*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/salary-periods*') ? 'active' : '' }}"
       href="{{ route('salaryPeriod.index') }}">{{ __('messages.salary_periods') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/industries*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/industries*') ? 'active' : '' }}"
       href="{{ route('industry.index') }}">{{ __('messages.industries') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/company-sizes*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/company-sizes*') ? 'active' : '' }}"
       href="{{ route('companySize.index') }}">{{ __('messages.company_sizes') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/functional-areas*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/functional-areas*') ? 'active' : '' }}"
       href="{{ route('functionalArea.index') }}">{{ __('messages.functional_areas') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/career-levels*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/career-levels*') ? 'active' : '' }}"
       href="{{ route('careerLevel.index') }}">{{ __('messages.career_levels') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/salary-currencies*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/salary-currencies*') ? 'active' : '' }}"
       href="{{ route('salaryCurrency.index') }}">{{ __('messages.salary_currencies') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/ownership-types*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/ownership-types*') ? 'active' : '' }}"
       href="{{ route('ownerShipType.index') }}">{{ __('messages.ownership_types') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/languages*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/languages*') ? 'active' : '' }}"
       href="{{ route('languages.index') }}">{{ __('messages.languages') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/noticeboards*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/noticeboards*') ? 'active' : '' }}"
       href="{{ route('noticeboards.index') }}">{{ __('messages.noticeboards') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/faqs*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/faqs*') ? 'active' : '' }}"
       href="{{ route('faqs.index') }}">{{ __('messages.faq.faq') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/inquires*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/inquires*') ? 'active' : '' }}"
       href="{{ route('inquires.index') }}">{{ __('messages.inquires') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/notification-settings*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/notification-settings*') ? 'active' : '' }}"
       href="{{ route('notification.settings.index') }}">{{ __('messages.setting.notification_settings') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/privacy-policy*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/privacy-policy*') ? 'active' : '' }}"
       href="{{ route('privacy.policy.index') }}">{{ __('messages.setting.privacy_policy') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/front-settings*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/front-settings*') ? 'active' : '' }}"
       href="{{ route('front.settings.index') }}">{{ __('messages.setting.front_settings') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/email-template*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/email-template*') ? 'active' : '' }}"
       href="{{ route('email.template.index') }}">{{ __('messages.email_templates') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/settings*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/settings*') ? 'active' : '' }}"
       href="{{ route('settings.index') }}">{{ __('messages.settings') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/testimonials*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/testimonials*') ? 'active' : '' }}"
       href="{{ route('testimonials.index') }}">{{ __('messages.testimonials') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/branding-sliders*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/branding-sliders*') ? 'active' : '' }}"
       href="{{ route('branding.sliders.index') }}">{{ __('messages.branding_sliders') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/header-sliders*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/header-sliders*') ? 'active' : '' }}"
       href="{{ route('header.sliders.index') }}">{{ __('messages.header_sliders') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/image-sliders*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/image-sliders*') ? 'active' : '' }}"
       href="{{ route('image-sliders.index') }}">{{ __('messages.image_sliders') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/ads*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/ads*') ? 'active' : '' }}"
       href="{{ route('ads.index') }}">{{ __('messages.ads') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/cms-services*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/cms-services*') ? 'active' : '' }}"
       href="{{ route('cms.services.index') }}">{{ __('messages.cms_services') }}</a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/cms-about-us*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/cms-about-us*') ? 'active' : '' }}"
       href="{{ route('cms.about-us.service') }}">{{ __('messages.about_us_services') }}</a>
</li>
