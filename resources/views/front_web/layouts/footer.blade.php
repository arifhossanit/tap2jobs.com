<footer class="footer bg-gradient front-shared-footer">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-12 mb-lg-0 mb-4">
                <div class="footer-logo">
                    <a href="{{ route('front.home') }}">
                        <img src="{{ asset($settings['footer_logo']) }}" alt="jobs-landing" class="img-fluid"
                            style="width: 80px" />
                    </a>
                </div>
                <p class="d-block text-gray my-4">
                    {{ __('web.footer.newsletter_text') }}
                </p>
                <form id="newsLetterForm">
                    <div class="email d-flex">
                        <input type="email" id="mc-email" name="email"
                            placeholder="{{ __('web.enter_your_mail') }}" class="text-gray" />
                        <div class="icon d-flex justify-content-center align-items-center bg-primary">
                            <button
                                class="icon d-flex justify-content-center align-items-center bg-primary border-0 btnLetterSave"
                                title="Subscribe">
                                <i class="fa-solid fa-paper-plane text-white"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <div class="social-icon d-flex mt-4">
                    @if (!empty($settings['facebook_url']))
                        <a href="{{ $settings['facebook_url'] }}" target="_blank" class=" me-2">
                            <i
                                class="fa-brands fa-facebook-f me-4 pe-1 d-flex align-items-center justify-content-center"></i>
                        </a>
                    @endif
                    @if (!empty($settings['twitter_url']))
                        <a href="{{ $settings['twitter_url'] }}" target="_blank" class=" mx-2">
                            <i class="fa-brands fa-twitter me-4 d-flex align-items-center justify-content-center"></i>
                        </a>
                    @endif
                    @if (!empty($settings['google_plus_url']))
                        <a href="{{ $settings['google_plus_url'] }}" target="_blank" class=" mx-2">
                            <i class="fa-brands fa-google me-4 d-flex align-items-center justify-content-center"></i>
                        </a>
                    @endif
                    @if (!empty($settings['linkedIn_url']))
                        <a href="{{ $settings['linkedIn_url'] }}" target="_blank" class=" mx-2">
                            <i
                                class="fa-brands fa-linkedin-in me-4 d-flex align-items-center justify-content-center"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- About Us Column -->
            <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-4 mb-3">
                <h3 class="mb-3 text-secondary fs-18">{{ __('web.footer.about_us') }}</h3>
                <ul class="p-0">
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
            <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-4 mb-3">
                <h3 class="mb-3 text-secondary fs-18">{{ __('web.footer.job_seekers') }}</h3>
                <ul class="p-0">
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
            <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-4 mb-3">
                <h3 class="mb-3 text-secondary fs-18">{{ __('web.footer.recruiter') }}</h3>
                <ul class="p-0">
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
            <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-6 mb-3">
                <h3 class="mb-3 text-secondary fs-18">{{ __('web.contact_us') }}</h3>
                <div class="footer-info">
                    <div class="d-flex footer-info__block mb-3">
                        <div class="{{ getFrontSelectLanguage() == 'ar' ? 'ms-3' : 'me-3' }} align-content-center">
                            <img src="{{ asset('img_template/contact.svg') }}" class="w-100" />
                        </div>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', ($settings['region_code'] ?? '').($settings['phone'] ?? '')) }}"
                           class="text-decoration-none text-gray fs-13">
                            {{ $settings['region_code'] . ' ' . $settings['phone'] }}
                        </a>
                    </div>
                    <div class="d-flex footer-info__block mb-3">
                        <div class="{{ getFrontSelectLanguage() == 'ar' ? 'ms-3' : 'me-3' }}">
                            <img src="{{ asset('img_template/address.svg') }}" />
                        </div>
                        <p class="text-gray mb-0 fs-14">
                            {{ $settings['address'] }}
                        </p>
                    </div>
                    <div class="d-flex footer-info__block mb-3">
                        <div class="{{ getFrontSelectLanguage() == 'ar' ? 'ms-3' : 'me-3' }} align-content-center">
                            <img src="{{ asset('img_template/email.svg') }}" class="w-100" />
                        </div>
                        <a href="mailto:{{ $settings['email'] }}" class="text-decoration-none text-gray">
                            {{ $settings['email'] }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 text-center mt-lg-5 mt-4 copy-right">
                <p class="pt-4 pb-4 text-gray fs-13">
                    &copy;{{ date('Y') }}
                    <a href="{{ getSettingValue('company_url') }}" class="text-primary" target="_blank">
                        {{ html_entity_decode($settings['application_name']) }}</a>.
                    {{ __('web.footer.all_rights_reserved') }}.
                    Developed by
                    <a href="https://www.tap2dealit.com/" class="text-primary" target="_blank" rel="noopener">Tap2Jobs IT</a>.
                </p>
            </div>
        </div>
    </div>
</footer>
