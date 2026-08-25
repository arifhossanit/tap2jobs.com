@php($notifications = \App\Models\Notification::whereNotificationFor(\App\Models\Notification::CANDIDATE)->where('user_id', getLoggedInUserId())->orderByDesc('created_at')->take(10)->get())
@php($notificationCount = $notifications->whereNull('read_at')->count())
@php($isCandidateUserDropdownActive = Request::is('candidate/profile*', 'candidate/favourite-companies*', 'candidate/change-password*'))
<style>
    .candidate-user-dropdown-menu .dropdown-item.active,
    .candidate-user-dropdown-menu .dropdown-item.active .dropdown-icon {
        color: #ffffff !important;
    }
    .candidate-notification-badge {
        top: 6px !important;
        right: -7px !important;
        left: auto !important;
        transform: none !important;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        font-size: 11px;
        line-height: 18px;
    }
</style>
<header class='candidate-dashboard-header container-fluid container-xxl d-flex align-items-stretch justify-content-between'>
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
            @include('candidate.layouts.sidebar')
        </nav>
        <ul class="nav align-items-stretch flex-nowrap">
            <li class="px-xxl-3 px-2 d-flex align-items-stretch">
                <div class="dropdown language-dropdown d-flex align-items-stretch" data-language-url="{{ route('front.change-language') }}">
                    <button type="button"
                            class="btn px-0 text-gray-600 d-flex align-items-center gap-1"
                            id="candidateLanguageDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-globe {{ checkLanguageSession() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        <span>{{ getCurrentLanguageName() }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow p-2"
                        aria-labelledby="candidateLanguageDropdown">
                        @foreach (getUserLanguages() as $languageCode => $languageName)
                            <li>
                                <button type="button"
                                        class="dropdown-item rounded d-flex align-items-center px-3 py-2 candidate-header-language-option {{ checkLanguageSession() === $languageCode ? 'active' : '' }}"
                                        data-language="{{ $languageCode }}"
                                        {{ checkLanguageSession() === $languageCode ? 'aria-current=true' : '' }}>
                                    <span>{{ $languageName }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
            <li class="px-xxl-3 px-2 d-flex align-items-stretch">
                <a href="{{ route('theme.mode') }}" class="d-flex align-items-center" >
                    <i class="fas user-check-icon {{ getLoggedInUser()->theme_mode ? 'fa-sun' : 'fa-moon' }} fs-2"></i>
                </a>
            </li>
            <li class="px-xxl-3 px-2 d-flex align-items-stretch">
                <div class="dropdown custom-dropdown d-flex align-items-stretch">
                    <button class="btn dropdown-toggle hide-arrow p-0 d-flex align-items-center"
                            type="button" id="candidateNotificationDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="position-relative">
                            <i class="fa-solid fa-bell text-primary fs-2"></i>
                            <span class="position-absolute notification-count candidate-notification-badge badge badge-circle bg-danger {{ $notificationCount == 0 ? 'd-none' : '' }}" id="candidateNotificationCount">
                                {{ $notificationCount }}
                                <span class="visually-hidden">{{ __('messages.unread_messages') }}</span>
                            </span>
                        </div>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end py-0 my-2" aria-labelledby="candidateNotificationDropdown" style="min-width: 320px;">
                        <div class="{{ checkLanguageSession() == 'ar' ? 'text-end' : 'text-start' }} border-bottom py-4 px-7">
                            <h3 class="text-gray-900 mb-0">{{__('messages.notification.notifications')}}</h3>
                        </div>
                        <div class="px-7 py-5 candidate-notification-list" style="max-height: 390px; overflow-y: auto; overflow-x: hidden;">
                            @if($notifications->isNotEmpty())
                                @foreach($notifications as $notification)
                                    <div class="candidate-notification-item {{ $notification->read_at ? 'candidate-notification-read' : 'candidate-notification-unread' }} d-flex position-relative mb-5 p-3 rounded candidateReadNotification cursor-pointer"
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
                                <div class="candidate-notification-empty d-flex flex-column align-items-center justify-content-center text-center py-8" data-height="400">
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
                    <button class="btn dropdown-toggle px-0 text-gray-600 d-flex align-items-center {{ $isCandidateUserDropdownActive ? 'active' : '' }}" type="button"
                            id="candidateUserDropdown" data-bs-auto-close="outside"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="image image-circle image-mini d-flex align-items-center {{ checkLanguageSession() == 'ar' ? 'ms-sm-3' : 'me-sm-3' }}">
                            <img src="{{ getLoggedInUser()->avatar }}"
                                 class="img-fluid" alt="profile image">
                        </div>
                        {{-- {{\Illuminate\Support\Facades\Auth::user()->full_name}} --}}
                        {{-- <i class="fa-solid fa-angle-down ms-2"></i>--}}
                    </button>
                    <div class="dropdown-menu candidate-user-dropdown-menu p-4 pb-4" aria-labelledby="candidateUserDropdown"
                         data-bs-auto-close="outside">
                        <div class="d-flex align-items-center border-bottom pb-4 text-start">
                            <div class="image image-circle image-tiny me-3 flex-shrink-0 mb-0 ms-2">
                                <img src="{{ getLoggedInUser()->avatar }}" class="img-fluid" alt="profile image" style="width: 44px; height: 44px; object-fit: cover; border-radius: 50%;">
                            </div>
                            <div class="overflow-hidden ms-2">
                                <h3 class="text-gray-900 fs-6 fw-bold mb-0 text-truncate">{{\Illuminate\Support\Facades\Auth::user()->full_name}}</h3>
                                <h4 class="mb-0 fw-400 fs-7 text-muted text-truncate">{{\Illuminate\Support\Facades\Auth::user()->email}}</h4>
                            </div>
                        </div>
                        <ul class="pt-4 pe-0">
                            <li>
                                <a href="{{ route('candidate.profile') }}"
                                   class="dropdown-item text-gray-900 {{ Request::is('candidate/profile*') ? 'active' : '' }} {{ checkLanguageSession() == 'ar' ? 'text-end' : '' }}">
                                     <span class="dropdown-icon {{ checkLanguageSession() == 'ar' ? 'ms-4' : 'me-4' }} text-gray-600">
                                        <i class="fa-solid fa-user"></i>
                                     </span> {{ __('messages.user.edit_profile') }}</a>
                            </li>
                            
                            <li>
                                <a class="dropdown-item text-gray-900 {{ Request::is('candidate/change-password*') ? 'active' : '' }} {{ checkLanguageSession() == 'ar' ? 'text-end' : '' }}"
                                   href="{{ route('candidate.change-password.form') }}">
                                    <span class="dropdown-icon {{ checkLanguageSession() == 'ar' ? 'ms-4' : 'me-4' }} text-gray-600">
                                        <i class="fa-solid fa-lock"></i>
                                    </span> {{ (Str::limit(__('messages.user.change_password'),20,'...')) }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-red-700 {{ checkLanguageSession() == 'ar' ? 'text-end' : '' }}" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();">
                                    <span class="dropdown-icon {{ checkLanguageSession() == 'ar' ? 'ms-4' : 'me-4' }} text-red-600">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                    </span> {{ __('messages.user.logout') }}
                                </a>
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
        const option = event.target.closest('.candidate-header-language-option');

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
