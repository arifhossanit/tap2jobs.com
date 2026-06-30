<header class="bg-gradient">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset($settings['logo']) }}" alt="" class="d-inline-block img-fluid h-100" />
            </a>
            <div class="d-flex align-items-center">
                <button class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <div class="navbar-toggler-icon" id="toggler-icon">
                        <span class="icon-bar top-bar"></span>
                        <span class="icon-bar middle-bar"></span>
                        <span class="icon-bar bottom-bar"></span>
                    </div>
                </button>
                <div class="collapse navbar-collapse justify-content-lg-between justify-content-end" id="navbarNav">
                    <ul class="navbar-nav d-flex justify-content-end align-items-lg-center w-100">
                        <li class="nav-item">
                            <a class="header-navbar-color text-gray nav-link {{ Request::is('/') ? 'header-navbar-color-active' : '' }}"
                                aria-current="page" href="{{ route('front.home') }}">{{ __('web.home') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="header-navbar-color text-gray nav-link {{ Request::is('search-jobs') || Request::is('job-details*') ? 'header-navbar-color-active' : '' }}"
                                href="{{ route('front.search.jobs') }}">{{ __('web.jobs') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="header-navbar-color text-gray nav-link {{ Request::is('company-lists') || Request::is('company-details*') ? 'header-navbar-color-active' : '' }}"
                                href="{{ route('front.company.lists') }}">{{ __('web.companies') }}</a>
                        </li>

                        @auth
                            @role('Employer|Admin')
                                <li class="nav-item">
                                    <a class="header-navbar-color text-gray nav-link {{ Request::is('candidate-lists') || Request::is('candidate-details*') ? 'header-navbar-color-active' : '' }}"
                                        href="{{ route('front.candidate.lists') }}">{{ __('web.job_seekers') }}</a>
                                </li>
                            @endrole
                        @endauth

                        <li class="nav-item">
                            <a class="header-navbar-color text-gray nav-link {{ Request::is('about-us') ? 'header-navbar-color-active' : '' }}"
                                href="{{ route('front.about.us') }}">{{ __('web.about_us') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="header-navbar-color text-gray nav-link {{ Request::is('contact-us') ? 'header-navbar-color-active' : '' }}"
                                href="{{ route('front.contact') }}">{{ __('web.contact_us') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="header-navbar-color text-gray nav-link {{ Request::is('posts*') ? 'header-navbar-color-active' : '' }}"
                                href="{{ route('front.post.lists') }}">{{ __('messages.post.blog') }}</a>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown">
                                <a class="nav-link text-gray dropdown-toggle language-dropdown-btn" type="button"
                                     aria-expanded="false">
                                    {{ getCurrentLanguageName() }}
                                </a>
                                <ul class="language-dropdown-menu language-menu">
                                    @foreach (getUserLanguages() as $key => $value)
                                        <li class="languageSelection {{ checkLanguageSession() == $key ? 'languageSelection-active' : '' }}"
                                            data-prefix-value="{{ $key }}">
                                            <a href="javascript:void(0)"
                                                class="dropdown-item text-gray d-flex align-items-center {{ checkLanguageSession() == $key ? 'active' : '' }}">
                                                @if (array_key_exists($key, \App\Models\User::LANGUAGES_IMAGE))
                                                    @foreach (\App\Models\User::LANGUAGES_IMAGE as $imageKey => $imageValue)
                                                        @if ($imageKey == $key)
                                                            <img class="{{ getFrontSelectLanguage() == 'ar' ? 'ms-2' : 'me-2' }} country-flag"
                                                                src="{{ asset($imageValue) }}" />
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <i class="fa fa-flag {{ getFrontSelectLanguage() == 'ar' ? 'ms-2' : 'me-2' }} fs-7 text-danger" aria-hidden="true"
                                                        style="width: 20px;"></i>
                                                @endif
                                                {{ $value }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>

                        {{-- <div class="d-flex align-items-center gap-xl-4 gap-3 mt-lg-0 mt-2 ms-xl-3 ms-lg-2">
                            <button class="btn btn-secondary" type="submit">Login</button>
                            <button class="btn btn-primary" type="submit">
                                Register
                            </button>
                        </div> --}}
                        @if (!Auth::check())
                            <div class="d-flex align-items-center gap-xl-4 gap-3 mt-lg-0 mt-2 ms-xl-3 ms-lg-2">
                                <ul class="navbar-nav d-flex flex-row align-items-center py-2 py-lg-0">
                                    <li class="nav-item login_btn">
                                        <a href="{{ route('admin.login') }}"
                                            class="nav-link btn btn-secondary btn-secondary-login {{ getFrontSelectLanguage() == 'ar' ? 'ms-xxl-4 ms-2' : 'me-xxl-4 me-2' }} mb-3 mb-lg-0 nav-link">{{ __('web.login') }}</a>
                                        <ul class="nav submenu">
                                            <li class="nav-item mb-3 mt-2">
                                                <a href="{{ route('admin.login') }}"
                                                    class="nav-link text-gray d-flex align-items-center {{ request()->routeIs('admin.login') ? ' active' : '' }}">
                                                    @lang('web.admin')
                                                </a>
                                            </li>
                                            <li class="nav-item mb-3 mt-2">
                                                <a href="{{ route('front.candidate.login') }}"
                                                    class="nav-link text-gray d-flex align-items-center {{ request()->routeIs('front.candidate.login') ? ' active' : '' }}">
                                                    {{ __('messages.notification_settings.candidate') }}
                                                </a>
                                            </li>
                                            <li class="nav-item mb-3">
                                                <a href="{{ route('front.employee.login') }}"
                                                    class="nav-link text-gray d-flex align-items-center {{ request()->routeIs('front.employee.login') ? ' active' : '' }}">
                                                    {{ __('messages.company.employer') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="nav-item register_btn">
                                        <a href="{{ route('candidate.register') }}"
                                            class="btn btn-primary  mb-3 mb-lg-0">{{ __('web.register') }}</a>
                                        <ul class="nav submenu">
                                            <li class="nav-item mb-3 mt-2 ">
                                                <a href="{{ route('candidate.register') }}"
                                                    class="nav-link text-gray d-flex align-items-center {{ request()->routeIs('candidate.register') ? ' active' : '' }}">
                                                    {{ __('messages.notification_settings.candidate') }}
                                                </a>
                                            </li>
                                            <li class="nav-item mb-2">
                                                <a href="{{ route('employer.register') }}"
                                                    class="nav-link text-gray d-flex align-items-center {{ request()->routeIs('employer.register') ? ' active' : '' }}">
                                                    {{ __('messages.company.employer') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <div class="d-flex align-items-center gap-xl-4 gap-3 mt-lg-0 mt-2 ms-xl-3 ms-lg-2">
                                <ul class="navbar-nav align-items-center py-2 py-lg-0">
                                    <li class="nav-item">
                                        <a href="javascript:void(0)"
                                            class="mb-3 mb-lg-0 user-logo d-flex align-items-center">
                                            <img src="{{ getLoggedInUser()->avatar }}"
                                                style="height: 44px;width: 45px;object-fit: cover;"
                                                class="rounded object-cover" />&nbsp;&nbsp;
                                            <span class="text-truncate"> {{ __('messages.common.hi') }},
                                                {{ getLoggedInUser()->full_name }}</span>
                                        </a>
                                        <ul class="nav submenu" style="text-align: initial;">
                                            <li class="nav-item mb-3 mt-2">
                                                <a href="{{ dashboardURL() }}"
                                                    class="nav-link text-gray d-flex align-items-center">
                                                    {{ __('web.go_to_dashboard') }}
                                                </a>
                                            </li>
                                            @role('Candidate')
                                                <li class="nav-item mb-3">
                                                    <a href="{{ route('candidate.profile') }}"
                                                        class="nav-link text-gray d-flex align-items-center">
                                                        {{ __('web.my_profile') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item mb-3">
                                                    <a href="{{ route('favourite.jobs') }}"
                                                        class="nav-link text-gray d-flex align-items-center">
                                                        {{ __('messages.favourite_jobs') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item mb-3">
                                                    <a href="{{ route('favourite.companies') }}"
                                                        class="nav-link text-gray d-flex align-items-center">
                                                        {{ __('messages.candidate_dashboard.followings') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item mb-3">
                                                    <a href="{{ route('candidate.applied.job') }}"
                                                        class="nav-link text-gray d-flex align-items-center">
                                                        {{ __('messages.applied_job.applied_jobs') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item mb-3">
                                                    <a href="{{ route('candidate.job.alert') }}"
                                                        class="nav-link text-gray d-flex align-items-center">
                                                        {{ __('messages.job.job_alert') }}
                                                    </a>
                                                </li>
                                            @endrole
                                            @role('Employer')
                                                <li class="nav-item mb-3">
                                                    <a href="{{ route('company.edit.form', \Illuminate\Support\Facades\Auth::user()->owner_id) }}"
                                                         class="nav-link text-gray d-flex align-items-center">
                                                        {{ __('web.my_profile') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item mb-3">
                                                    <a href="{{ route('job.index') }}"
                                                        class="nav-link text-gray d-flex align-items-center">
                                                        {{ __('messages.employer_menu.jobs') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item mb-3">
                                                    <a href="{{ route('followers.index') }}"
                                                        class="nav-link text-gray d-flex align-items-center">
                                                        {{ __('messages.employer_menu.followers') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item mb-3">
                                                    <a href="{{ route('manage-subscription.index') }}"
                                                        class="nav-link text-gray d-flex align-items-center">
                                                        {{ __('messages.employer_menu.manage_subscriptions') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item mb-3">
                                                    <a href="{{ route('transactions.index') }}"
                                                        class="nav-link text-gray d-flex align-items-center">
                                                        {{ __('messages.employer_menu.transactions') }}
                                                    </a>
                                                </li>
                                            @endrole
                                            <li class="nav-item mb-2">
                                                <a href="{{ url('logout') }}"
                                                    class="nav-link text-gray d-flex align-items-center"
                                                    onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();">
                                                    {{ __('web.logout') }}
                                                </a>
                                                <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                                    class="d-none">
                                                    {{ csrf_field() }}
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
