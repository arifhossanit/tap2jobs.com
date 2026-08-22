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
                            <a class="header-navbar-color text-gray nav-link {{ Request::is('posts*') || Request::is('blogs*') ? 'header-navbar-color-active' : '' }}"
                                href="{{ route('front.blogs') }}">{{ __('web.blogs') }}</a>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown language-dropdown"
                                 data-language-url="{{ route('front.change-language') }}">
                                <a href="#" class="nav-link text-gray dropdown-toggle language-dropdown-btn"
                                   id="frontLanguageToggle" role="button" aria-expanded="false"
                                   aria-controls="frontLanguageMenu">
                                    <i class="fa-solid fa-globe {{ getFrontSelectLanguage() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
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

                        @if (!Auth::check())
                            <div class="d-flex align-items-center gap-xl-4 gap-3 mt-lg-0 mt-2 ms-xl-3 ms-lg-2">
                                <ul class="navbar-nav d-flex flex-row align-items-center py-2 py-lg-0">
                                    <li class="nav-item login_btn">
                                        <a href="{{ route('front.candidate.login') }}"
                                            class="nav-link btn btn-secondary btn-secondary-login {{ getFrontSelectLanguage() == 'ar' ? 'ms-2' : 'me-2' }} mb-3 mb-lg-0 nav-link">{{ __('web.login') }}</a>
                                        <ul class="nav submenu">
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
                                            class="btn btn-primary mb-3 mb-lg-0">{{ __('web.register') }}</a>
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
                                <ul class="navbar-nav align-items-center py-2 py-lg-0 front-user-nav d-flex flex-row align-items-center gap-2">
                                    @auth
                                        @php
                                            $notifications = getNotification(\App\Models\Notification::CANDIDATE);
                                            if(Auth::user()->hasRole('Employer')) {
                                                $notifications = getNotification(\App\Models\Notification::EMPLOYER);
                                            }
                                            $unreadCount = $notifications ? $notifications->count() : 0;
                                        @endphp
                                        <li class="nav-item dropdown front-user-dropdown me-2">
                                            <button class="btn dropdown-toggle front-user-dropdown-toggle d-flex align-items-center position-relative p-2"
                                                    type="button" id="frontNotificationDropdown" aria-expanded="false" style="border:none; background:transparent;">
                                                <i class="fa-solid fa-bell fs-4 text-primary"></i>
                                                @if($unreadCount > 0)
                                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger fs-8 p-1" style="min-width: 18px; height: 18px; line-height: 10px;" id="candidateNotificationCount">
                                                        {{ $unreadCount }}
                                                    </span>
                                                @endif
                                            </button>
                                             <div class="dropdown-menu dropdown-menu-end front-user-dropdown-menu p-0 shadow-lg border-0 rounded-3"
                                                  style="width: 320px; max-height: 420px; overflow: hidden;"
                                                  aria-labelledby="frontNotificationDropdown">
                                                 <div class="d-flex align-items-center justify-content-between px-3 py-2 bg-light border-bottom">
                                                     <h6 class="fw-bold mb-0 text-dark fs-7 d-flex align-items-center gap-2">
                                                         <i class="fa-solid fa-bell text-primary"></i> {{ __('messages.notification.notifications') }}
                                                     </h6>
                                                     @if($unreadCount > 0)
                                                         <a href="javascript:void(0)" class="text-primary fs-8 text-decoration-none read-all-notification-btn">{{ __('messages.notification.mark_all_as_read') ?? 'Mark all as read' }}</a>
                                                     @endif
                                                 </div>
                                                 <div class="notification-scroll-body" style="max-height: 320px; overflow-y: auto;">
                                                     @if($notifications && $notifications->isNotEmpty())
                                                         @foreach($notifications as $notification)
                                                             <div class="dropdown-item border-bottom py-2 px-3 text-wrap d-flex align-items-start gap-2 read-notification-item" data-id="{{ $notification->id }}" data-url="{{ getNotificationUrl($notification) }}" style="cursor: pointer; transition: background 0.2s ease, opacity 0.2s ease;">
                                                                 <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center mt-1" style="width: 28px; height: 28px; flex-shrink: 0;">
                                                                     <i class="{{ getNotificationIcon($notification->type) }}" style="font-size: 0.75rem;"></i>
                                                                 </div>
                                                                 <div class="w-100">
                                                                     <p class="mb-1 fw-semibold text-dark lh-sm" style="font-size: 0.8125rem;">{{ $notification->title }}</p>
                                                                     <span class="text-muted" style="font-size: 0.725rem;"><i class="fa-regular fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}</span>
                                                                 </div>
                                                             </div>
                                                         @endforeach
                                                     @else
                                                         <div class="p-4 text-center text-muted">
                                                             <i class="fa-regular fa-bell-slash fs-3 mb-2 d-block text-secondary"></i>
                                                             <span class="fs-7">{{ __('messages.notification.empty_notifications') ?? 'No notifications found' }}</span>
                                                         </div>
                                                     @endif
                                                 </div>
                                             </div>
                                         </li>
                                     @endauth
                                     <li class="nav-item dropdown front-user-dropdown">
                                         <button class="btn dropdown-toggle front-user-dropdown-toggle d-flex align-items-center p-0 border-0"
                                             type="button" id="frontUserDropdown" aria-expanded="false"
                                             aria-controls="frontUserDropdownMenu" style="background:transparent;">
                                             <img src="{{ getLoggedInUser()->avatar }}" class="front-user-avatar"
                                                 alt="{{ getLoggedInUser()->full_name }}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover;">
                                         </button>
                                         <div class="dropdown-menu dropdown-menu-end front-user-dropdown-menu p-3 shadow-lg border-0 rounded-3"
                                             id="frontUserDropdownMenu"
                                             aria-labelledby="frontUserDropdown" style="min-width: 250px;">
                                             <div class="front-user-summary d-flex align-items-center border-bottom pb-3 mb-2 text-start">
                                                 <img src="{{ getLoggedInUser()->avatar }}" class="front-user-summary-avatar me-3 flex-shrink-0"
                                                     alt="{{ getLoggedInUser()->full_name }}" style="width: 44px; height: 44px; margin: 0; border-radius: 50%; object-fit: cover;">
                                                 <div class="front-user-info overflow-hidden ms-1">
                                                     <h3 class="front-user-name fs-6 fw-bold mb-0 text-truncate text-dark">{{ getLoggedInUser()->full_name }}</h3>
                                                     <p class="front-user-email fs-7 text-muted mb-0 text-truncate">{{ getLoggedInUser()->email }}</p>
                                                 </div>
                                             </div>
                                             <ul class="front-user-menu-list list-unstyled mb-0">
                                                 <li>
                                                     <a href="{{ dashboardURL() }}" class="dropdown-item py-2 px-2 d-flex align-items-center gap-2 rounded">
                                                         <span class="front-user-menu-icon text-muted" style="width: 20px;"><i class="fa-solid fa-gauge-high"></i></span>
                                                         <span>{{ __('web.go_to_dashboard') }}</span>
                                                     </a>
                                                 </li>
                                             @role('Candidate')
                                                 <li>
                                                     <a href="{{ route('candidate.profile') }}" class="dropdown-item py-2 px-2 d-flex align-items-center gap-2 rounded">
                                                         <span class="front-user-menu-icon text-muted" style="width: 20px;"><i class="fa-solid fa-user"></i></span>
                                                         <span>{{ __('web.my_profile') }}</span>
                                                     </a>
                                                 </li>
                                                 <li>
                                                     <a href="{{ route('favourite.jobs') }}" class="dropdown-item py-2 px-2 d-flex align-items-center gap-2 rounded">
                                                         <span class="front-user-menu-icon text-muted" style="width: 20px;"><i class="fa-solid fa-heart"></i></span>
                                                         <span>{{ __('messages.favourite_jobs') }}</span>
                                                     </a>
                                                 </li>
                                                 <li>
                                                     <a href="{{ route('favourite.companies') }}" class="dropdown-item py-2 px-2 d-flex align-items-center gap-2 rounded">
                                                         <span class="front-user-menu-icon text-muted" style="width: 20px;"><i class="fa-solid fa-building-circle-check"></i></span>
                                                         <span>{{ __('messages.candidate_dashboard.followings') }}</span>
                                                     </a>
                                                 </li>
                                                 <li>
                                                     <a href="{{ route('candidate.applied.job') }}" class="dropdown-item py-2 px-2 d-flex align-items-center gap-2 rounded">
                                                         <span class="front-user-menu-icon text-muted" style="width: 20px;"><i class="fa-solid fa-briefcase"></i></span>
                                                         <span>{{ __('messages.applied_job.applied_jobs') }}</span>
                                                     </a>
                                                 </li>
                                                 <li>
                                                     <a href="{{ route('candidate.job.alert') }}" class="dropdown-item py-2 px-2 d-flex align-items-center gap-2 rounded">
                                                         <span class="front-user-menu-icon text-muted" style="width: 20px;"><i class="fa-solid fa-bell"></i></span>
                                                         <span>{{ __('messages.job.job_alert') }}</span>
                                                     </a>
                                                 </li>
                                             @endrole
                                             @role('Employer')
                                                 <li>
                                                     <a href="{{ route('company.edit.form', \Illuminate\Support\Facades\Auth::user()->owner_id) }}" class="dropdown-item py-2 px-2 d-flex align-items-center gap-2 rounded">
                                                         <span class="front-user-menu-icon text-muted" style="width: 20px;"><i class="fa-solid fa-user"></i></span>
                                                         <span>{{ __('web.my_profile') }}</span>
                                                     </a>
                                                 </li>
                                                 <li>
                                                     <a href="{{ route('job.index') }}" class="dropdown-item py-2 px-2 d-flex align-items-center gap-2 rounded">
                                                         <span class="front-user-menu-icon text-muted" style="width: 20px;"><i class="fa-solid fa-briefcase"></i></span>
                                                         <span>{{ __('messages.employer_menu.jobs') }}</span>
                                                     </a>
                                                 </li>
                                                 <li>
                                                     <a href="{{ route('followers.index') }}" class="dropdown-item py-2 px-2 d-flex align-items-center gap-2 rounded">
                                                         <span class="front-user-menu-icon text-muted" style="width: 20px;"><i class="fa-solid fa-users"></i></span>
                                                         <span>{{ __('messages.employer_menu.followers') }}</span>
                                                     </a>
                                                 </li>
                                                 <li>
                                                     <a href="{{ route('manage-subscription.index') }}" class="dropdown-item py-2 px-2 d-flex align-items-center gap-2 rounded">
                                                         <span class="front-user-menu-icon text-muted" style="width: 20px;"><i class="fa-solid fa-credit-card"></i></span>
                                                         <span>{{ __('messages.employer_menu.manage_subscriptions') }}</span>
                                                     </a>
                                                 </li>
                                                 <li>
                                                     <a href="{{ route('transactions.index') }}" class="dropdown-item py-2 px-2 d-flex align-items-center gap-2 rounded">
                                                         <span class="front-user-menu-icon text-muted" style="width: 20px;"><i class="fa-solid fa-receipt"></i></span>
                                                         <span>{{ __('messages.employer_menu.transactions') }}</span>
                                                     </a>
                                                 </li>
                                             @endrole
                                                 <li>
                                                     <a href="{{ url('logout') }}" class="dropdown-item py-2 px-2 d-flex align-items-center gap-2 rounded text-danger"
                                                         onclick="event.preventDefault(); localStorage.clear(); document.getElementById('logout-form').submit();">
                                                         <span class="front-user-menu-icon text-danger" style="width: 20px;"><i class="fa-solid fa-right-from-bracket"></i></span>
                                                         <span>{{ __('web.logout') }}</span>
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
