@php
    $notifications = getNotification(\App\Models\Notification::EMPLOYER);
    $notificationCount = $notifications->count();
    $loggedInEmployer = getLoggedInUser();
    $employerHeaderName = $loggedInEmployer->company?->contact_person_name ?: $loggedInEmployer->full_name;
@endphp
<header class='employer-dashboard-header container-fluid container-xxl d-flex align-items-stretch justify-content-between'>
    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
        <a href="{{ route('front.home') }}"  target="_blank"
           class="text-decoration-none horizontal-sidebar-logo d-flex align-items-center">
            <div class="image {{ checkLanguageSession() == 'ar' ? 'ms-3' : 'me-3' }}">
                <img src="{{getLogoUrl()}}"
                     class="img-fluid new-logo-image" alt="profile image" style="width: auto; max-width: 100%; max-height: 45px; object-fit: contain;">
            </div>
            <!-- <span class="text-gray-900 fs-4 d-none d-sm-block"> {{ getAppName() }}</span> -->
        </a>
    </div>
    <div class="d-flex align-items-stretch justify-content-xl-between justify-content-end flex-grow-1">
        <nav class="navbar navbar-expand-xl navbar-light horizontal-sidebar d-xl-flex d-block align-items-stretch py-3 py-xl-0 flex-grow-1"
             id="nav-header">
            @include('employer.layouts.sidebar')
        </nav>
        <ul class="nav align-items-stretch flex-nowrap">
            <li class="px-xxl-3 px-2 d-flex align-items-stretch">
                <div class="dropdown d-flex align-items-stretch">
                    <button type="button"
                            class="btn dropdown-toggle px-0 text-gray-600 d-flex align-items-center"
                            id="employerLanguageDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        {{ getCurrentLanguageName() }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow p-2"
                        aria-labelledby="employerLanguageDropdown">
                        @foreach (getUserLanguages() as $languageCode => $languageName)
                            <li>
                                <button type="button"
                                        class="dropdown-item rounded d-flex align-items-center gap-3 px-3 py-2 employer-header-language-option {{ checkLanguageSession() === $languageCode ? 'active' : '' }}"
                                        data-language="{{ $languageCode }}"
                                        {{ checkLanguageSession() === $languageCode ? 'aria-current=true' : '' }}>
                                    @if (isset(\App\Models\User::LANGUAGES_IMAGE[$languageCode]))
                                        <img src="{{ asset(\App\Models\User::LANGUAGES_IMAGE[$languageCode]) }}"
                                             width="20" height="14" alt="" class="flex-shrink-0">
                                    @else
                                        <i class="fa-solid fa-flag text-primary" style="width: 20px"></i>
                                    @endif
                                    <span>{{ $languageName }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
            <li class="px-xxl-3 px-2 d-flex align-items-stretch">
                <div class="dropdown d-flex align-items-stretch">
                    <button type="button"
                            class="btn dropdown-toggle px-0 text-gray-600 d-flex align-items-center"
                            id="employerLanguageDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        {{ getCurrentLanguageName() }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow p-2"
                        aria-labelledby="employerLanguageDropdown">
                        @foreach (getUserLanguages() as $languageCode => $languageName)
                            <li>
                                <button type="button"
                                        class="dropdown-item rounded d-flex align-items-center gap-3 px-3 py-2 employer-header-language-option {{ checkLanguageSession() === $languageCode ? 'active' : '' }}"
                                        data-language="{{ $languageCode }}"
                                        {{ checkLanguageSession() === $languageCode ? 'aria-current=true' : '' }}>
                                    @if (isset(\App\Models\User::LANGUAGES_IMAGE[$languageCode]))
                                        <img src="{{ asset(\App\Models\User::LANGUAGES_IMAGE[$languageCode]) }}"
                                             width="20" height="14" alt="" class="flex-shrink-0">
                                    @else
                                        <i class="fa-solid fa-flag text-primary" style="width: 20px"></i>
                                    @endif
                                    <span>{{ $languageName }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
            <li class="px-xxl-3 px-2 d-flex align-items-stretch">
                <div class="dropdown d-flex align-items-stretch">
                    <button type="button"
                            class="btn dropdown-toggle employer-language-toggle px-0 text-gray-600 d-flex align-items-center"
                            id="employerLanguageDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-globe {{ checkLanguageSession() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        {{ getCurrentLanguageName() }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow p-2"
                        aria-labelledby="employerLanguageDropdown">
                        @foreach (getUserLanguages() as $languageCode => $languageName)
                            <li>
                                <button type="button"
                                        class="dropdown-item rounded d-flex align-items-center gap-3 px-3 py-2 employer-header-language-option {{ checkLanguageSession() === $languageCode ? 'active' : '' }}"
                                        data-language="{{ $languageCode }}"
                                        {{ checkLanguageSession() === $languageCode ? 'aria-current=true' : '' }}>
                                    @if (isset(\App\Models\User::LANGUAGES_IMAGE[$languageCode]))
                                        <img src="{{ asset(\App\Models\User::LANGUAGES_IMAGE[$languageCode]) }}"
                                             width="20" height="14" alt="" class="flex-shrink-0">
                                    @else
                                        <i class="fa-solid fa-flag text-primary" style="width: 20px"></i>
                                    @endif
                                    <span>{{ $languageName }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
            {{-- <li class="px-xxl-3 px-2 d-flex align-items-stretch">
                <a href="{{ route('theme.mode') }}" class="d-flex align-items-center" >
                    <i class="fas user-check-icon {{ getLoggedInUser()->theme_mode ? 'fa-sun' : 'fa-moon' }} fs-2"></i>
                </a>
            </li> --}}
            <li class="px-xxl-3 px-2 d-flex align-items-stretch">
                <div class="dropdown custom-dropdown d-flex align-items-stretch">
                    <button class="btn dropdown-toggle hide-arrow p-0 d-flex align-items-center"
                            type="button" id="employerNotificationDropdown"
                            type="button" id="employerNotificationDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="position-relative">
                            <i class="fa-solid fa-bell text-primary fs-2"></i>
                            <span class="position-absolute notification-count top-0 start-100 translate-middle badge badge-circle bg-danger {{ $notificationCount == 0 ? 'd-none' : '' }}" id="employerNotificationCount">
                                {{ $notificationCount }}
                                <span class="visually-hidden">{{ __('messages.unread_messages') }}</span>
                            </span>
                        </div>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="employerNotificationDropdown" style="min-width: 320px;">
                        <div class="{{ checkLanguageSession() == 'ar' ? 'text-end' : 'text-start' }} border-bottom py-4 px-7">
                            <h3 class="text-gray-900 mb-0">{{__('messages.notification.notifications')}}</h3>
                        </div>
                        <div class="employer-notification-list" style="max-height: 390px; overflow-y: auto; overflow-x: hidden;">
                            @if($notifications->isNotEmpty())
                                @foreach($notifications as $notification)
                                    <div class="employer-notification-item {{ $notification->read_at ? 'employer-notification-read' : 'employer-notification-unread' }} d-flex position-relative border-bottom p-3 rounded employerReadNotification cursor-pointer"
                                         data-id="{{ $notification->id }}" data-url="{{ getNotificationUrl($notification) }}" data-read="{{ $notification->read_at ? '1' : '0' }}"
                                         style="background: {{ $notification->read_at ? 'transparent' : 'rgba(101, 113, 255, 0.08)' }}; opacity: {{ $notification->read_at ? '0.7' : '1' }};">
                                        <span class="{{ checkLanguageSession() == 'ar' ? 'ms-5' : 'me-5' }} text-primary fs-2 icon-label">
                                            <i class="{{ getNotificationIcon($notification->type) }}"></i></span>
                                        <div>
                                            <h5 class="text-gray-900 fs-6 mb-2">{{$notification->title}}</h5>
                                            <h6 class="text-gray-600 fs-small fw-light mb-0">
                                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans(null, true)}}</h6>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="employer-notification-empty d-flex flex-column align-items-center justify-content-center text-center py-8" data-height="400">
                                    <i class="fa-regular fa-bell-slash text-gray-500 fs-1 mb-3"></i>
                                    <p class="fs-6 fw-semibold text-gray-700 mb-0">No notification found</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </li>

            <li class="px-xxl-3 px-2 d-flex align-items-stretch">
                <div class="dropdown dropdown-transparent d-flex align-items-stretch">
                    <button class="btn dropdown-toggle px-0 text-gray-600 d-flex align-items-center" type="button"
                            id="employerUserDropdown" data-bs-auto-close="outside"
                            id="employerUserDropdown" data-bs-auto-close="outside"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="image image-circle image-mini d-flex align-items-center {{ checkLanguageSession() == 'ar' ? 'ms-sm-3' : 'me-sm-3' }}">
                            <img src="{{ $loggedInEmployer->avatar }}"
                                 class="img-fluid" alt="{{ $employerHeaderName }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                        </div>
                    </button>
                    <div class="dropdown-menu border p-4 pb-4 employer-user-dropdown-menu" aria-labelledby="employerUserDropdown"
                         data-bs-auto-close="outside" style="width: 250px; max-width: 250px;">
                        <div class="d-flex align-items-center border-bottom pb-4 mb-3 text-start">
                            <div class="image image-circle image-tiny me-3 flex-shrink-0 mb-0" style="width: 44px; height: 44px;">
                                <img src="{{ $loggedInEmployer->avatar }}" class="img-fluid" alt="{{ $employerHeaderName }}" style="width: 44px; height: 44px; object-fit: cover; border-radius: 50%;">
                            </div>
                            <div class="overflow-hidden ms-1 flex-grow-1" style="min-width: 0;">
                                <h3 class="text-gray-900 fs-6 fw-bold mb-0 text-truncate">{{ $employerHeaderName }}</h3>
                                <h4 class="mb-0 fw-400 fs-7 text-muted text-truncate">{{ $loggedInEmployer->email }}</h4>
                            </div>
                        </div>
                        <ul class="pe-0">
                            <li>
                                <a href="{{ route('company.edit.form', \Illuminate\Support\Facades\Auth::user()->owner_id) }}#company-details" class="dropdown-item text-gray-900 {{ checkLanguageSession() == 'ar' ? 'text-end' : '' }}">
                                <a href="{{ route('company.edit.form', \Illuminate\Support\Facades\Auth::user()->owner_id) }}#company-details" class="dropdown-item text-gray-900 {{ checkLanguageSession() == 'ar' ? 'text-end' : '' }}">
                                     <span class="dropdown-icon {{ checkLanguageSession() == 'ar' ? 'ms-4' : 'me-4' }} text-gray-600">
                                        <i class="fa-solid fa-user"></i>
                                     </span> {{ __('messages.user.edit_profile') }}</a>
                            </li>
                            <li>
                                <a class="dropdown-item text-gray-900 {{ checkLanguageSession() == 'ar' ? 'text-end' : '' }}"
                                   href="{{ route('company.edit.form', \Illuminate\Support\Facades\Auth::user()->owner_id) }}#change-password">
                                <a class="dropdown-item text-gray-900 {{ checkLanguageSession() == 'ar' ? 'text-end' : '' }}"
                                   href="{{ route('company.edit.form', \Illuminate\Support\Facades\Auth::user()->owner_id) }}#change-password">
                                    <span class="dropdown-icon {{ checkLanguageSession() == 'ar' ? 'ms-4' : 'me-4' }} text-gray-600">
                                        <i class="fa-solid fa-lock"></i>
                                    </span> {{ (Str::limit(__('messages.user.change_password'),20,'...')) }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-gray-900 {{ checkLanguageSession() == 'ar' ? 'text-end' : '' }}" href="{{ route('logout') }}"
                                <a class="dropdown-item text-gray-900 {{ checkLanguageSession() == 'ar' ? 'text-end' : '' }}" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();">
                                    <span class="dropdown-icon {{ checkLanguageSession() == 'ar' ? 'ms-4' : 'me-4' }} text-gray-600">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                    </span> {{ __('messages.user.logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>


            <li class="d-flex align-items-center">
                <button type="button" class="btn px-0 horizontal-menubar d-block d-xl-none text-gray-600">
                    <i class="fa-solid fa-bars fs-1"></i>
                </button>
            </li>
        </ul>
    </div>
</header>
<div class="bg-overlay" id="horizontal-menubar-overly"></div>

<script>
    document.addEventListener('click', function (event) {
        const option = event.target.closest('.employer-header-language-option');

        if (!option || option.disabled || option.getAttribute('aria-current') === 'true') {
            return;
        }

        option.disabled = true;

        fetch("{{ route('update-language') }}", {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ language: option.dataset.language })
        })
            .then(async function (response) {
                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'Unable to change language.');
                }

                window.location.reload();
            })
            .catch(function (error) {
                option.disabled = false;
                displayErrorMessage(error.message);
            });
    });
</script>

<script>
    document.addEventListener('click', function (event) {
        const option = event.target.closest('.employer-header-language-option');

        if (!option || option.disabled || option.getAttribute('aria-current') === 'true') {
            return;
        }

        option.disabled = true;

        fetch("{{ route('update-language') }}", {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ language: option.dataset.language })
        })
            .then(async function (response) {
                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'Unable to change language.');
                }

                window.location.reload();
            })
            .catch(function (error) {
                option.disabled = false;
                displayErrorMessage(error.message);
            });
    });
</script>
