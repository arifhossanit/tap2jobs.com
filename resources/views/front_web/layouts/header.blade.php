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
                        @if(auth()->guest() || auth()->user()?->hasRole('Employer'))
                            <li class="nav-item">
                                <a class="header-navbar-color text-gray nav-link {{ Request::is('company-lists') || Request::is('company-details*') ? 'header-navbar-color-active' : '' }}"
                                    href="{{ route('job.create') }}">{{ __('web.add_post_job') }}</a>
                            </li>
                        @endif
                        {{--
                        @auth
                            @role('Employer|Admin')
                                <li class="nav-item">
                                    <a class="header-navbar-color text-gray nav-link {{ Request::is('candidate-lists') || Request::is('candidate-details*') ? 'header-navbar-color-active' : '' }}"
                                        href="{{ route('front.candidate.lists') }}">{{ __('web.job_seekers') }}</a>
                                </li>
                            @endrole
                        @endauth
                        --}}
                        <li class="nav-item">
                            <div class="dropdown language-dropdown"
                                 data-language-url="{{ route('front.change-language') }}">
                                <a href="#" class="nav-link text-gray dropdown-toggle language-dropdown-btn"
                                   id="frontLanguageToggle" role="button" aria-expanded="false"
                                   aria-controls="frontLanguageMenu">
                                    {{ getCurrentLanguageName() }}
                                </a>
                                <ul class="language-dropdown-menu language-menu" id="frontLanguageMenu"
                                    aria-labelledby="frontLanguageToggle">
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
                                        <a href="#"
                                            class="nav-link btn btn-secondary btn-secondary-login {{ getFrontSelectLanguage() == 'ar' ? 'ms-xxl-4 ms-2' : 'me-xxl-4 me-2' }} mb-3 mb-lg-0 nav-link">{{ __('web.login') }}</a>
                                        <ul class="nav submenu">
                                            {{-- <li class="nav-item mb-3 mt-2">
                                                <a href="{{ route('admin.login') }}"
                                                    class="nav-link text-gray d-flex align-items-center {{ request()->routeIs('admin.login') ? ' active' : '' }}">
                                                    @lang('web.admin')
                                                </a>
                                            </li> --}}
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
                                <ul class="navbar-nav align-items-center py-2 py-lg-0 front-user-nav">
                                    <li class="nav-item dropdown front-user-dropdown">
                                        <button class="btn dropdown-toggle front-user-dropdown-toggle d-flex align-items-center"
                                            type="button" id="frontUserDropdown" aria-expanded="false"
                                            aria-controls="frontUserDropdownMenu">
                                            <img src="{{ getLoggedInUser()->avatar }}" class="front-user-avatar"
                                                alt="{{ getLoggedInUser()->full_name }}">
                                            {{-- <span class="text-truncate">{{ getLoggedInUser()->full_name }}</span> --}}
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end front-user-dropdown-menu p-4"
                                            id="frontUserDropdownMenu"
                                            aria-labelledby="frontUserDropdown">
                                            <div class="front-user-summary text-center border-bottom pb-4">
                                                <img src="{{ getLoggedInUser()->avatar }}" class="front-user-summary-avatar mb-3"
                                                    alt="{{ getLoggedInUser()->full_name }}">
                                                <h3 class="front-user-name mb-1">{{ getLoggedInUser()->full_name }}</h3>
                                                <p class="front-user-email mb-0 text-truncate">{{ getLoggedInUser()->email }}</p>
                                            </div>
                                            <ul class="front-user-menu-list list-unstyled pt-3 mb-0">
                                                <li>
                                                    <a href="{{ dashboardURL() }}" class="dropdown-item">
                                                        <span class="front-user-menu-icon"><i class="fa-solid fa-gauge-high"></i></span>
                                                        {{ __('web.go_to_dashboard') }}
                                                    </a>
                                                </li>
                                            @role('Candidate')
                                                <li>
                                                    <a href="{{ route('candidate.profile') }}" class="dropdown-item">
                                                        <span class="front-user-menu-icon"><i class="fa-solid fa-user"></i></span>
                                                        {{ __('web.my_profile') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('favourite.jobs') }}" class="dropdown-item">
                                                        <span class="front-user-menu-icon"><i class="fa-solid fa-heart"></i></span>
                                                        {{ __('messages.favourite_jobs') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('favourite.companies') }}" class="dropdown-item">
                                                        <span class="front-user-menu-icon"><i class="fa-solid fa-building-circle-check"></i></span>
                                                        {{ __('messages.candidate_dashboard.followings') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('candidate.applied.job') }}" class="dropdown-item">
                                                        <span class="front-user-menu-icon"><i class="fa-solid fa-briefcase"></i></span>
                                                        {{ __('messages.applied_job.applied_jobs') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('candidate.job.alert') }}" class="dropdown-item">
                                                        <span class="front-user-menu-icon"><i class="fa-solid fa-bell"></i></span>
                                                        {{ __('messages.job.job_alert') }}
                                                    </a>
                                                </li>
                                            @endrole
                                            @role('Employer')
                                                <li>
                                                    <a href="{{ route('company.edit.form', \Illuminate\Support\Facades\Auth::user()->owner_id) }}"
                                                         class="dropdown-item">
                                                        <span class="front-user-menu-icon"><i class="fa-solid fa-user"></i></span>
                                                        {{ __('web.my_profile') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('job.index') }}" class="dropdown-item">
                                                        <span class="front-user-menu-icon"><i class="fa-solid fa-briefcase"></i></span>
                                                        {{ __('messages.employer_menu.jobs') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('followers.index') }}" class="dropdown-item">
                                                        <span class="front-user-menu-icon"><i class="fa-solid fa-users"></i></span>
                                                        {{ __('messages.employer_menu.followers') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('manage-subscription.index') }}" class="dropdown-item">
                                                        <span class="front-user-menu-icon"><i class="fa-solid fa-credit-card"></i></span>
                                                        {{ __('messages.employer_menu.manage_subscriptions') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('transactions.index') }}" class="dropdown-item">
                                                        <span class="front-user-menu-icon"><i class="fa-solid fa-receipt"></i></span>
                                                        {{ __('messages.employer_menu.transactions') }}
                                                    </a>
                                                </li>
                                            @endrole
                                            <li>
                                                <a href="{{ url('logout') }}" class="dropdown-item"
                                                    onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();">
                                                    <span class="front-user-menu-icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                                                    {{ __('web.logout') }}
                                                </a>
                                            </li>
                                            </ul>
                                        </div>
                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">
                                            {{ csrf_field() }}
                                        </form>
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
