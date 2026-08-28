@php
    $notifications = null;
    $unreadCount = 0;
    if (Auth::check()) {
        $notifications = getNotification(\App\Models\Notification::CANDIDATE);
        if(Auth::user()->hasRole('Employer')) {
            $notifications = getNotification(\App\Models\Notification::EMPLOYER);
        }
        $unreadCount = $notifications ? $notifications->count() : 0;
    }
@endphp
<header class="bg-gradient">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset($settings['logo']) }}" alt="" class="d-inline-block img-fluid h-100" />
            </a>
            <div class="front-mobile-actions d-flex d-lg-none align-items-center">
                @auth
                    <div class="dropdown front-user-dropdown d-lg-none">
                        <button class="btn dropdown-toggle front-user-dropdown-toggle front-mobile-notification-toggle d-flex align-items-center justify-content-center position-relative"
                                type="button" id="mobileNotificationDropdown" aria-expanded="false" style="border:none; background:transparent;">
                            <i class="fa-solid fa-bell fs-4 text-primary"></i>
                            @if($unreadCount > 0)
                                <span class="front-mobile-notification-badge position-absolute badge rounded-circle bg-danger fs-8 p-1" id="mobileCandidateNotificationCount">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-end front-user-dropdown-menu front-mobile-notification-menu p-0 shadow-lg border-0 rounded-3"
                             style="max-height: 420px; overflow: hidden;"
                             aria-labelledby="mobileNotificationDropdown">
                            <div class="d-flex align-items-center justify-content-between px-3 py-2 bg-light border-bottom">
                                <h6 class="fw-bold mb-0 text-dark fs-7 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-bell text-primary"></i> {{ __('messages.notification.notifications') }}
                                </h6>

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
                    </div>
                @endauth
                <button class="navbar-toggler border-0 p-0" type="button"
                        id="mobileNavbarToggler"
                        aria-controls="navbarNav"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                    <div class="navbar-toggler-icon" id="toggler-icon">
                        <span class="icon-bar top-bar"></span>
                        <span class="icon-bar middle-bar"></span>
                        <span class="icon-bar bottom-bar"></span>
                    </div>
                </button>
            </div>
            <div class="collapse navbar-collapse justify-content-lg-between justify-content-end" id="navbarNav">
                    <ul class="navbar-nav d-flex justify-content-end align-items-lg-center w-100">
                        <li class="nav-item mb-2 mb-lg-0">
                            <a class="header-navbar-color text-gray nav-link px-2 px-lg-0 {{ Request::is('/') ? 'header-navbar-color-active' : '' }}"
                                aria-current="page" href="{{ route('front.home') }}">{{ __('web.home') }}</a>
                        </li>
                        <li class="nav-item mb-2 mb-lg-0">
                            <a class="header-navbar-color text-gray nav-link px-2 px-lg-0 {{ Request::is('search-jobs') || Request::is('job-details*') ? 'header-navbar-color-active' : '' }}"
                                href="{{ route('front.search.jobs') }}">{{ __('web.jobs') }}</a>
                        </li>
                        <li class="nav-item mb-2 mb-lg-0">
                            <div class="dropdown language-dropdown"
                                 data-language-url="{{ route('front.change-language') }}">
                                <a href="#" class="nav-link text-gray dropdown-toggle language-dropdown-btn px-2 px-lg-0"
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
                            <li class="nav-item front-auth-actions d-flex align-items-center gap-xl-4 gap-3 mt-lg-0 mt-2 ms-xl-3 ms-lg-2">
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
                            </li>
                        @else
                            <li class="nav-item front-auth-actions d-flex align-items-center gap-xl-4 gap-3 mt-lg-0 mt-2 ms-xl-3 ms-lg-2">
                                <ul class="navbar-nav align-items-center py-2 py-lg-0 front-user-nav d-flex flex-row align-items-center gap-2">
                                    @auth
                                        <li class="nav-item dropdown front-user-dropdown me-2 d-none d-lg-block">
                                            <button class="btn dropdown-toggle front-user-dropdown-toggle d-flex align-items-center position-relative p-2"
                                                    type="button" id="frontNotificationDropdown" aria-expanded="false" style="border:none; background:transparent;">
                                                <i class="fa-solid fa-bell fs-4 text-primary"></i>
                                                @if($unreadCount > 0)
                                                    <span class="position-absolute front-notification-badge badge rounded-circle bg-danger" id="candidateNotificationCount">
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
                                     <li class="nav-item dropdown front-user-dropdown front-account-dropdown d-flex align-items-center position-relative">
                                         <button class="btn dropdown-toggle front-user-dropdown-toggle d-flex align-items-center p-0 border-0"
                                             type="button" id="frontUserDropdown" aria-expanded="false"
                                             aria-controls="frontUserDropdownMenu" style="background:transparent;">
                                             <img src="{{ getLoggedInUser()->avatar }}" class="front-user-avatar"
                                                 alt="{{ getLoggedInUser()->full_name }}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover;" onerror="this.src='{{ asset('assets/img/default-user.png') }}'">
                                             <span class="front-account-name ms-2 fw-semibold text-dark d-lg-none fs-6 text-truncate">{{ getLoggedInUser()->full_name }}</span>
                                             <span class="front-account-chevron d-lg-none ms-auto" aria-hidden="true">
                                                 <i class="fa-solid fa-chevron-down"></i>
                                             </span>
                                         </button>
                                         <div class="dropdown-menu dropdown-menu-end front-user-dropdown-menu p-3 shadow-lg border-0 rounded-3"
                                             id="frontUserDropdownMenu"
                                             aria-labelledby="frontUserDropdown" style="min-width: 250px;">
                                             <div class="front-user-summary d-flex align-items-center border-bottom pb-3 mb-2 text-start">
                                                 <img src="{{ getLoggedInUser()->avatar }}" class="front-user-summary-avatar me-3 flex-shrink-0"
                                                     alt="{{ getLoggedInUser()->full_name }}" style="width: 44px; height: 44px; margin: 0; border-radius: 50%; object-fit: cover;" onerror="this.src='{{ asset('assets/img/default-user.png') }}'">
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
                             </li>
                         @endif
                     </ul>
             </div>
         </div>
     </nav>
</header>

<script>
(function () {
    if (window.frontHeaderInitialized) return;
    window.frontHeaderInitialized = true;

    const header = document.querySelector('header');
    const toggler = document.getElementById('mobileNavbarToggler');
    const navbar = document.getElementById('navbarNav');
    const togglerIcon = document.getElementById('toggler-icon');
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.content : '';

    if (!header || !toggler || !navbar) return;

    function closeLanguageDropdowns(restoreFocus) {
        header.querySelectorAll('.language-dropdown.is-open').forEach(function (dropdown) {
            const button = dropdown.querySelector('.language-dropdown-btn');
            dropdown.classList.remove('is-open');
            if (button) {
                button.setAttribute('aria-expanded', 'false');
                if (restoreFocus) button.focus();
            }
        });
    }

    function closeUserDropdowns(restoreFocus) {
        header.querySelectorAll('.front-user-dropdown.is-open').forEach(function (dropdown) {
            const button = dropdown.querySelector('.front-user-dropdown-toggle');
            const menu = dropdown.querySelector('.front-user-dropdown-menu');
            dropdown.classList.remove('is-open');
            if (menu) menu.classList.remove('show');
            if (button) {
                button.setAttribute('aria-expanded', 'false');
                if (restoreFocus) button.focus();
            }
        });
    }

    function closeAuthDropdowns(exceptDropdown) {
        header.querySelectorAll('#navbarNav .login_btn.is-open, #navbarNav .register_btn.is-open').forEach(function (dropdown) {
            if (dropdown !== exceptDropdown) {
                dropdown.classList.remove('is-open');
                const button = dropdown.querySelector(':scope > a');
                if (button) button.setAttribute('aria-expanded', 'false');
            }
        });
    }

    function closeMobileNavbar() {
        if (window.innerWidth >= 992) return;

        if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
            bootstrap.Collapse.getOrCreateInstance(navbar, { toggle: false }).hide();
        } else {
            navbar.classList.remove('show');
            toggler.classList.add('collapsed');
            toggler.setAttribute('aria-expanded', 'false');
            if (togglerIcon) togglerIcon.classList.remove('open');
        }
    }

    function toggleMobileNavbar() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
            bootstrap.Collapse.getOrCreateInstance(navbar, { toggle: false }).toggle();
            return;
        }

        const willOpen = !navbar.classList.contains('show');
        navbar.classList.toggle('show', willOpen);
        toggler.classList.toggle('collapsed', !willOpen);
        toggler.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        if (togglerIcon) togglerIcon.classList.toggle('open', willOpen);
    }

    function updateNotificationBadges(count) {
        header.querySelectorAll('#candidateNotificationCount, #mobileCandidateNotificationCount').forEach(function (badge) {
            badge.textContent = count;
            badge.classList.toggle('d-none', count === 0);
        });
    }

    function createNotificationContent(notification) {
        const item = document.createElement('div');
        const isRead = Boolean(notification.is_read);
        item.className = 'dropdown-item border-bottom py-2 px-3 text-wrap d-flex align-items-start gap-2 read-notification-item';
        item.dataset.id = notification.id;
        item.dataset.url = notification.url || '';
        item.dataset.read = isRead ? '1' : '0';
        item.style.cssText = 'cursor:pointer;transition:background .2s ease,opacity .2s ease;';
        item.style.background = isRead ? 'transparent' : 'rgba(101, 113, 255, 0.08)';
        item.style.opacity = isRead ? '0.7' : '1';

        const iconWrap = document.createElement('div');
        iconWrap.className = 'rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center mt-1';
        iconWrap.style.cssText = 'width:28px;height:28px;flex-shrink:0;';
        const icon = document.createElement('i');
        icon.className = notification.icon || 'fa fa-inbox';
        icon.style.fontSize = '0.75rem';
        iconWrap.appendChild(icon);

        const textWrap = document.createElement('div');
        textWrap.className = 'w-100';
        const title = document.createElement('p');
        title.className = 'mb-1 fw-semibold text-dark lh-sm';
        title.style.fontSize = '0.8125rem';
        title.textContent = notification.title || '';
        const time = document.createElement('span');
        time.className = 'text-muted';
        time.style.fontSize = '0.725rem';
        const clock = document.createElement('i');
        clock.className = 'fa-regular fa-clock me-1';
        time.appendChild(clock);
        time.appendChild(document.createTextNode(notification.created_at || ''));
        textWrap.append(title, time);
        item.append(iconWrap, textWrap);
        return item;
    }

    function renderNotifications(data) {
        const notifications = data.notifications || [];
        updateNotificationBadges(parseInt(data.count || 0, 10));

        header.querySelectorAll('.notification-scroll-body').forEach(function (list) {
            list.replaceChildren();
            if (!notifications.length) {
                const empty = document.createElement('div');
                empty.className = 'p-4 text-center text-muted d-flex flex-column align-items-center justify-content-center';
                empty.innerHTML = '<i class="fa-regular fa-bell-slash fs-3 mb-2 text-secondary"></i><span class="fs-7">No notification found</span>';
                list.appendChild(empty);
                return;
            }
            notifications.forEach(function (notification) {
                list.appendChild(createNotificationContent(notification));
            });
        });
    }

    function refreshNotifications() {
        if (!header.querySelector('#frontNotificationDropdown') || typeof route !== 'function') return;

        fetch(route('notifications.latest'), {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function (response) {
            if (!response.ok) throw new Error('Unable to load notifications.');
            return response.json();
        }).then(function (result) {
            if (result.success) renderNotifications(result.data);
        }).catch(function () {});
    }

    navbar.addEventListener('shown.bs.collapse', function () {
        toggler.setAttribute('aria-expanded', 'true');
        if (togglerIcon) togglerIcon.classList.add('open');
    });

    navbar.addEventListener('hidden.bs.collapse', function () {
        toggler.setAttribute('aria-expanded', 'false');
        if (togglerIcon) togglerIcon.classList.remove('open');
        closeLanguageDropdowns(false);
        closeUserDropdowns(false);
        closeAuthDropdowns();
    });

    toggler.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        toggleMobileNavbar();
    });

    header.addEventListener('click', function (event) {
        const languageButton = event.target.closest('.language-dropdown-btn');
        if (languageButton) {
            event.preventDefault();
            event.stopPropagation();
            const dropdown = languageButton.closest('.language-dropdown');
            const willOpen = !dropdown.classList.contains('is-open');
            closeLanguageDropdowns(false);
            closeUserDropdowns(false);
            closeAuthDropdowns();
            dropdown.classList.toggle('is-open', willOpen);
            languageButton.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            return;
        }

        const userButton = event.target.closest('.front-user-dropdown-toggle');
        if (userButton) {
            event.preventDefault();
            event.stopPropagation();
            const dropdown = userButton.closest('.front-user-dropdown');
            const menu = dropdown.querySelector('.front-user-dropdown-menu');
            const willOpen = !dropdown.classList.contains('is-open');
            closeUserDropdowns(false);
            closeLanguageDropdowns(false);
            closeAuthDropdowns();
            dropdown.classList.toggle('is-open', willOpen);
            if (menu) menu.classList.toggle('show', willOpen);
            userButton.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            return;
        }

        const authButton = event.target.closest('#navbarNav .login_btn > a, #navbarNav .register_btn > a');
        if (authButton && window.innerWidth < 992) {
            const dropdown = authButton.closest('.login_btn, .register_btn');
            const submenu = dropdown ? dropdown.querySelector('.submenu') : null;

            if (submenu) {
                event.preventDefault();
                event.stopPropagation();
                const willOpen = !dropdown.classList.contains('is-open');
                closeLanguageDropdowns(false);
                closeUserDropdowns(false);
                closeAuthDropdowns(dropdown);
                dropdown.classList.toggle('is-open', willOpen);
                authButton.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                return;
            }
        }

        const languageOption = event.target.closest('.languageSelection');
        if (languageOption) {
            event.preventDefault();
            const dropdown = languageOption.closest('.language-dropdown');
            const languageUrl = dropdown ? dropdown.dataset.languageUrl : '';
            const languageName = languageOption.dataset.prefixValue;
            if (!languageUrl || !languageName) return;

            fetch(languageUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({ languageName: languageName }).toString()
            }).then(function (response) {
                if (!response.ok) throw new Error('Unable to change language.');
                window.location.reload();
            }).catch(function (error) {
                if (typeof displayErrorMessage === 'function') {
                    displayErrorMessage(error.message);
                }
            });
            return;
        }

        const notification = event.target.closest('.read-notification-item');
        if (notification) {
            event.preventDefault();
            const notificationId = notification.dataset.id;
            const notificationUrl = notification.dataset.url;
            const wasUnread = notification.dataset.read !== '1';
            const readUrl = typeof route === 'function' && notificationId
                ? route('read-notification', notificationId)
                : '';

            if (!readUrl) {
                if (notificationUrl) window.location.href = notificationUrl;
                return;
            }

            header.querySelectorAll('.read-notification-item[data-id="' + notificationId + '"]').forEach(function (item) {
                item.dataset.read = '1';
                item.style.opacity = '0.7';
                item.style.background = 'transparent';
            });
            if (wasUnread) {
                const badge = header.querySelector('#candidateNotificationCount, #mobileCandidateNotificationCount');
                const count = badge ? parseInt(badge.textContent || '0', 10) : 0;
                updateNotificationBadges(Math.max(count - 1, 0));
            }

            fetch(readUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({ notificationId: notificationId }).toString()
            }).then(function (response) {
                if (!response.ok) throw new Error('Unable to read notification.');
                return response.json();
            }).then(function (result) {
                const responseUrl = result.data && result.data.url ? result.data.url : '';
                if (notificationUrl || responseUrl) window.location.href = notificationUrl || responseUrl;
            }).catch(function () {
                if (wasUnread) refreshNotifications();
            });
            return;
        }

        const navLink = event.target.closest('#navbarNav a');
        if (navLink && navLink.getAttribute('href') && navLink.getAttribute('href') !== '#'
            && !navLink.getAttribute('href').startsWith('javascript:')) {
            closeMobileNavbar();
        }
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('.language-dropdown')) closeLanguageDropdowns(false);
        if (!event.target.closest('.front-user-dropdown')) closeUserDropdowns(false);
        if (!event.target.closest('.login_btn, .register_btn')) closeAuthDropdowns();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeLanguageDropdowns(true);
            closeUserDropdowns(true);
            closeAuthDropdowns();
            closeMobileNavbar();
        }
    });

    if (header.querySelector('#frontNotificationDropdown')) {
        window.setInterval(refreshNotifications, 30000);
    }
})();
</script>
