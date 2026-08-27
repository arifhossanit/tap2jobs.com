<footer class="footer bg-gradient front-shared-footer">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-12 mb-lg-0 mb-4">
                <div class="footer-logo mb-4">
                    <a href="{{ route('front.home') }}">
                        <img src="{{ asset($settings['footer_logo']) }}" alt="jobs-landing" class="img-fluid"
                            style="width: 80px" />
                    </a>
                </div>

                <div class="need-support-block">
                    <h3 class="mb-2 text-secondary fs-18 fw-bold">Need any support?</h3>
                    <p class="text-gray fs-14 mb-3">
                        Our Contact Centre is available from 9 am to 8 pm (Sat to Thurs).
                    </p>

                    <div class="support-phone-box d-flex align-items-center">
                        <div class="support-phone-icon me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" viewBox="0 0 24 24" fill="none" stroke="#d93025" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                <path d="M14.05 2a9 9 0 0 1 8 8"/>
                                <path d="M14.05 6a5 5 0 0 1 4 4"/>
                            </svg>
                        </div>
                        <div class="support-phone-numbers">
                            <a href="tel:16479" class="d-block text-danger fw-bold fs-16 text-decoration-none mb-1">16479</a>
                            <a href="tel:09638666444" class="d-block text-danger fw-bold fs-16 text-decoration-none mb-1">09638666444</a>
                            <a href="tel:01897627858" class="d-block text-danger fw-bold fs-16 text-decoration-none">01897627858</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About Us Column -->
            <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-4 mb-3 front-footer-accordion">
                <button type="button" class="front-footer-accordion__toggle" aria-expanded="false">
                    <span>{{ __('web.footer.about_us') }}</span>
                    <i class="fa-solid fa-plus"></i>
                </button>
                <h3 class="mb-3 text-secondary fs-18 front-footer-accordion__desktop-title">{{ __('web.footer.about_us') }}</h3>
                <ul class="p-0 front-footer-accordion__body">
                    <li>
                        <a href="{{ route('front.about.us') }}"
                            class="text-decoration-none mb-3 d-block {{ Request::is('about-us') ? 'footer-navbar-color-active text-dark' : 'text-gray' }} fs-14">{{ __('web.about_us') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('terms.conditions.list') }}"
                            class="text-decoration-none mb-3 d-block {{ Request::is('terms-conditions-list') ? 'footer-navbar-color-active text-dark' : 'text-gray' }} fs-14">{{ __('messages.setting.terms_conditions') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('privacy.policy.list') }}"
                            class="text-decoration-none mb-3 d-block {{ Request::is('privacy-policy-list') ? 'footer-navbar-color-active text-dark' : 'text-gray' }} fs-14">{{ __('messages.setting.privacy_policy') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('front.contact') }}"
                            class="text-decoration-none mb-3 d-block {{ Request::is('contact-us') ? 'footer-navbar-color-active text-dark' : 'text-gray' }} fs-14">{{ __('web.contact_us') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('front.post.lists') }}"
                            class="text-decoration-none mb-3 d-block {{ Request::is('posts*') ? 'footer-navbar-color-active text-dark' : 'text-gray' }} fs-14">{{ __('messages.post.blog') }}</a>
                    </li>
                </ul>
            </div>

            <!-- Job Seekers (Candidate) Column -->
            <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-4 mb-3 front-footer-accordion">
                <button type="button" class="front-footer-accordion__toggle" aria-expanded="false">
                    <span>{{ __('web.footer.job_seekers') }}</span>
                    <i class="fa-solid fa-plus"></i>
                </button>
                <h3 class="mb-3 text-secondary fs-18 front-footer-accordion__desktop-title">{{ __('web.footer.job_seekers') }}</h3>
                <ul class="p-0 front-footer-accordion__body">
                    <li>
                        <a href="{{ route('front.search.jobs') }}"
                            class="text-decoration-none {{ Request::is('search-jobs') || Request::is('job-details*') ? 'footer-navbar-color-active text-dark' : 'text-gray' }} mb-3 d-block fs-14">{{ __('web.find_jobs') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('front.candidate.login') }}"
                            class="text-decoration-none {{ Request::is('candidate-login') ? 'footer-navbar-color-active text-dark' : 'text-gray' }} mb-3 d-block fs-14">{{ __('web.register_menu.candidate') }} {{ __('web.login') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('candidate.register') }}"
                            class="text-decoration-none {{ Request::is('candidate-register') ? 'footer-navbar-color-active text-dark' : 'text-gray' }} mb-3 d-block fs-14">{{ __('web.register_menu.candidate') }} {{ __('web.register') }}</a>
                    </li>
                    <li>
                        <a href="{{ Route::has('dashboard') ? route('dashboard') : route('front.candidate.login') }}"
                            class="text-decoration-none {{ Request::is('dashboard*') ? 'footer-navbar-color-active text-dark' : 'text-gray' }} mb-3 d-block fs-14">{{ __('web.footer.my_panel') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('candidate.faq') }}"
                            class="text-decoration-none {{ Request::is('candidate-faq') ? 'footer-navbar-color-active text-dark' : 'text-gray' }} mb-3 d-block fs-14">{{ __('messages.faq.candidate_faq') }}</a>
                    </li>
                </ul>
            </div>

            <!-- Recruiter (Employer) Column -->
            <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-4 mb-3 front-footer-accordion">
                <button type="button" class="front-footer-accordion__toggle" aria-expanded="false">
                    <span>{{ __('web.footer.recruiter') }}</span>
                    <i class="fa-solid fa-plus"></i>
                </button>
                <h3 class="mb-3 text-secondary fs-18 front-footer-accordion__desktop-title">{{ __('web.footer.recruiter') }}</h3>
                <ul class="p-0 front-footer-accordion__body">
                    <li>
                        <a href="{{ route('employer.register') }}"
                            class="text-decoration-none {{ Request::is('employer-register') ? 'footer-navbar-color-active text-dark' : 'text-gray' }} mb-3 d-block fs-14">{{ __('web.register_menu.create_account') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('front.employee.login') }}"
                            class="text-decoration-none {{ Request::is('employer-login') ? 'footer-navbar-color-active text-dark' : 'text-gray' }} mb-3 d-block fs-14">{{ __('web.register_menu.employer') }} {{ __('web.login') }}</a>
                    </li>
                    {{-- <li>
                        <a href="{{ route('front.company.lists') }}"
                            class="text-decoration-none {{ Request::is('company-lists') || Request::is('company-details*') ? 'footer-navbar-color-active text-dark' : 'text-gray' }} mb-3 d-block fs-14">{{ __('web.companies') }}</a>
                    </li> --}}
                    <li>
                        <a href="{{ Route::has('job.create') ? route('job.create') : route('employer.register') }}"
                            class="text-decoration-none {{ Request::is('employer/jobs/create') ? 'footer-navbar-color-active text-dark' : 'text-gray' }} mb-3 d-block fs-14">{{ __('web.footer.post_a_job') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('employer.faq') }}"
                            class="text-decoration-none {{ Request::is('employer-faq') ? 'footer-navbar-color-active text-dark' : 'text-gray' }} mb-3 d-block fs-14">{{ __('messages.faq.employer_faq') }}</a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info Column -->
            <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-6 mb-3 front-footer-accordion">
                <button type="button" class="front-footer-accordion__toggle" aria-expanded="false">
                    <span>{{ __('web.contact_us') }}</span>
                    <i class="fa-solid fa-plus"></i>
                </button>
                <h3 class="mb-3 text-secondary fs-18 front-footer-accordion__desktop-title">{{ __('web.contact_us') }}</h3>
                <div class="footer-info front-footer-accordion__body">
                    @if(!empty($settings['address']))
                    <div class="d-flex footer-info__block mb-2">
                        <div class="{{ getFrontSelectLanguage() == 'ar' ? 'ms-3' : 'me-3' }}">
                            <img src="{{ asset('img_template/address.svg') }}" alt="address" />
                        </div>
                        <p class="text-gray mb-0 fs-14">
                            {{ $settings['address'] }}
                        </p>
                    </div>
                    @endif
                    @if(!empty($settings['email']))
                    <div class="d-flex footer-info__block mb-3">
                        <div class="{{ getFrontSelectLanguage() == 'ar' ? 'ms-3' : 'me-3' }} align-content-center">
                            <img src="{{ asset('img_template/email.svg') }}" alt="email" class="w-100" />
                        </div>
                        <a href="mailto:{{ $settings['email'] }}" class="text-decoration-none text-gray">
                            {{ $settings['email'] }}
                        </a>
                    </div>
                    @endif

                    
                </div>
                
            </div>
            <div class="stay-connected-container align-items-end">
                        <h4 class="fs-16 fw-semibold text-secondarys">{{ __('web.footer.newsletter_text') }}</h4>
                        <div class="social-icon d-flex align-items-center">
                            @if (!empty($settings['facebook_url']))
                                <a href="{{ $settings['facebook_url'] }}" target="_blank" class="me-2">
                                    <i class="fa-brands fa-facebook-f d-flex align-items-center justify-content-center"></i>
                                </a>
                            @endif
                            @if (!empty($settings['instagram_url']) || !empty($settings['twitter_url']))
                                <a href="{{ $settings['instagram_url'] ?? $settings['twitter_url'] }}" target="_blank" class="mx-2">
                                    <i class="fa-brands fa-instagram d-flex align-items-center justify-content-center"></i>
                                </a>
                            @endif
                            @if (!empty($settings['google_plus_url']))
                                <a href="{{ $settings['google_plus_url'] }}" target="_blank" class="mx-2">
                                    <i class="fa-brands fa-google d-flex align-items-center justify-content-center"></i>
                                </a>
                            @endif
                            @if (!empty($settings['linkedIn_url']))
                                <a href="{{ $settings['linkedIn_url'] }}" target="_blank" class="mx-2">
                                    <i class="fa-brands fa-linkedin-in d-flex align-items-center justify-content-center"></i>
                                </a>
                            @endif
                        </div>
            </div>

            <div class="col-12 text-center copy-right">
                <p class="pt-4 pb-4 text-gray fs-13">
                    &copy;{{ date('Y') }}
                    <a href="{{ getSettingValue('company_url') }}" class="text-primary" target="_blank">
                        {{ html_entity_decode($settings['application_name']) }}</a>.
                    {{ __('web.footer.all_rights_reserved') }}.
                    Developed by
                    <a href="https://www.tap2dealit.com/" class="text-primary" target="_blank" rel="noopener">Tap2Deal IT</a>.
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Fixed Mobile Bottom Navigation Bar -->
<div class="bd-mobile-fixed-footer d-flex d-lg-none">
    <a href="{{ route('front.home') }}" class="bd-mobile-footer-item {{ Request::is('/') || Request::is('home') || request()->routeIs('front.home') ? 'active' : '' }}">
        <div class="bd-mobile-footer-icon">
            <i class="fa-solid fa-house"></i>
        </div>
        <span>@lang('web.home')</span>
    </a>
    <a href="{{ route('front.search.jobs') }}" class="bd-mobile-footer-item {{ Request::is('search-jobs') || Request::is('job-details*') ? 'active' : '' }}">
        <div class="bd-mobile-footer-icon">
            <i class="fa-solid fa-briefcase"></i>
        </div>
        <span>@lang('web.jobs')</span>
    </a>
    @auth
        @php
            $panelUrl = Auth::user()->hasRole('Candidate') ? (Route::has('candidate.profile') ? route('candidate.profile') : route('dashboard')) : (Auth::user()->hasRole('Employer') ? (Route::has('employer.dashboard') ? route('employer.dashboard') : route('dashboard')) : route('dashboard'));
        @endphp
        <a href="{{ $panelUrl }}" class="bd-mobile-footer-item {{ Request::is('candidate*') || Request::is('dashboard*') || Request::is('employer*') ? 'active' : '' }}">
            <div class="bd-mobile-footer-icon">
                @if(!empty(Auth::user()->avatar))
                    <img src="{{ Auth::user()->avatar }}" alt="profile" class="bd-mobile-user-avatar">
                @else
                    <i class="fa-solid fa-user"></i>
                @endif
            </div>
            <span>{{ __('web.footer.my_panel') }}</span>
        </a>
    @else
        <a href="{{ route('front.candidate.login') }}" class="bd-mobile-footer-item {{ Request::is('candidate-login') || Request::is('candidate-register') ? 'active' : '' }}">
            <div class="bd-mobile-footer-icon">
                <i class="fa-solid fa-user"></i>
            </div>
            <span>{{ __('web.footer.my_panel') }}</span>
        </a>
    @endauth
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var footer = document.querySelector('.bd-mobile-fixed-footer');
        if (!footer) return;

        var initialHeight = window.innerHeight;

        function showFooter() {
            footer.classList.remove('keyboard-hidden');
            document.body.classList.remove('keyboard-open');
        }

        function hideFooter() {
            footer.classList.add('keyboard-hidden');
            document.body.classList.add('keyboard-open');
        }

        document.addEventListener('focusin', function (e) {
            if (e.target && (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT')) {
                if (e.target.type !== 'checkbox' && e.target.type !== 'radio') {
                    hideFooter();
                }
            }
        });

        document.addEventListener('focusout', function (e) {
            if (e.target && (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT')) {
                setTimeout(function () {
                    var activeEl = document.activeElement;
                    if (!activeEl || (activeEl.tagName !== 'INPUT' && activeEl.tagName !== 'TEXTAREA' && activeEl.tagName !== 'SELECT')) {
                        showFooter();
                    }
                }, 100);
            }
        });

        if (window.visualViewport) {
            window.visualViewport.addEventListener('resize', function () {
                if (window.visualViewport.height < initialHeight - 120) {
                    hideFooter();
                } else if (!document.activeElement || (document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA')) {
                    showFooter();
                }
            });
        }
    });
</script>
