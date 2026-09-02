@php($notifications = \App\Models\Notification::whereNotificationFor(\App\Models\Notification::ADMIN)->where('user_id', getLoggedInUserId())->orderByDesc('created_at')->take(10)->get())
@php($notificationCount = $notifications->whereNull('read_at')->count())
@php($pendingJobsCount = \App\Models\Job::where('status', \App\Models\Job::SELECT_PANDING)->count())
@php($expireIn7DaysCount = \App\Models\Job::whereDate('job_expiry_date', '<=', \Carbon\Carbon::today()->addDays(7)->toDateString())->whereDate('job_expiry_date', '>=', \Carbon\Carbon::today()->toDateString())->where('status', \App\Models\Job::STATUS_OPEN)->where('is_suspended', \App\Models\Job::NOT_SUSPENDED)->count())
<header class='d-flex align-items-center justify-content-between flex-grow-1 header px-4 px-lg-7 px-xl-0'>
    <button type="button" class="btn px-0 aside-menu-container__aside-menubar d-block d-xl-none sidebar-btn">
        <i class="fa-solid fa-bars fs-1"></i>
    </button>
    <nav class="navbar navbar-expand-xl navbar-light top-navbar d-xl-flex d-block px-3 px-xl-0 py-4 py-xl-0 " id="nav-header">
        <div class="container-fluid">
            <div class="navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @include('layouts.sub_menu')
                </ul>
            </div>
        </div>
    </nav>
    <ul class="nav align-items-center">
        <li class="px-sm-3 px-2 d-none d-lg-block">
            <a href="{{ route('admin.PendingJobs.index') }}" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-2">
                Pending Jobs
                @if($pendingJobsCount > 0)
                    <span class="badge bg-danger text-white rounded-pill border border-light">
                        {{ $pendingJobsCount }}
                    </span>
                @endif
            </a>
        </li>
        <li class="px-sm-3 px-2 d-none d-lg-block">
            <a href="{{ route('admin.jobs.expireIn7DaysJobs') }}" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2">
                Expire in 7 days
                @if($expireIn7DaysCount > 0)
                    <span class="badge bg-danger text-white rounded-pill border border-light">
                        {{ $expireIn7DaysCount }}
                    </span>
                @endif
            </a>
        </li>
        @if(getLoggedInUser()->theme_mode)
            <li class="px-sm-3 px-2">
                <a  href="{{ route('theme.mode') }}" title="Switch to Light Mode">
                    <i class="fa-solid fa-sun text-primary fs-2"></i>
                </a>
            </li>
        @else
            <li class="px-sm-3 px-2">
                <a  href="{{ route('theme.mode') }}" title="Switch to Dark Mode">
                    <i class="fa-solid fa-moon text-primary fs-2"></i>
                </a>
            </li>
        @endif

        <li class="px-sm-3 px-2">
            <div class="dropdown custom-dropdown d-flex align-items-center py-4">
                <button class="btn dropdown-toggle hide-arrow {{ checkLanguageSession() == 'ar' ? 'pe-2 ps-0' : 'ps-2 pe-0' }} py-0 position-relative" type="button" id="adminNotificationDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-bell text-primary fs-2"></i>
                    <span class="position-absolute notification-count top-0 start-100 translate-middle badge badge-circle bg-danger {{ $notificationCount == 0 ? 'd-none' : '' }}" id="counter">
                        {{ $notificationCount }}
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end py-0 my-2" aria-labelledby="adminNotificationDropdown" style="min-width: 320px;">
                    <div class="{{ checkLanguageSession() == 'ar' ? 'text-end' : 'text-start' }} border-bottom py-4 px-7">
                        <h3 class="text-gray-900 mb-0">{{__('messages.notification.notifications')}}</h3>
                    </div>
                    <div class="" id="adminNotificationList" style="max-height: 390px; overflow-y: auto; overflow-x: hidden;">
                        @if($notifications->isNotEmpty())
                            @foreach($notifications as $notification)
                                <div class="admin-notification-item {{ $notification->read_at ? 'admin-notification-read' : 'admin-notification-unread' }} d-flex position-relative p-5 rounded readNotification cursor-pointer" data-id="{{ $notification->id }}" data-url="{{ getNotificationUrl($notification) }}" data-read="{{ $notification->read_at ? '1' : '0' }}" style="background: {{ $notification->read_at ? 'transparent' : 'rgba(101, 113, 255, 0.08)' }}; opacity: {{ $notification->read_at ? '0.7' : '1' }};">
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
                            <div class="admin-notification-empty d-flex flex-column align-items-center justify-content-center text-center py-8" data-height="400">
                                <i class="fa-regular fa-bell-slash text-gray-500 fs-1 mb-3"></i>
                                <p class="fs-6 fw-semibold text-gray-700 mb-0">No notification found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </li>
        <li class="px-sm-3 px-2">
            <div class="dropdown d-flex align-items-center py-4">
                
                <button class="btn dropdown-toggle {{ checkLanguageSession() == 'ar' ? 'pe-2 ps-0' : 'ps-2 pe-0' }} hide-arrow" type="button" id="adminUserDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                    {{-- {{ getLoggedInUser()->full_name }} --}}
                    <div class="image image-circle image-mini">
                        <img src="{{ getLoggedInUser()->avatar }}"
                            class="img-fluid" alt="profile image">
                    </div>
                </button>
                <div class="dropdown-menu py-7 pb-4 my-2" aria-labelledby="adminUserDropdown"
                     data-bs-auto-close="outside">
                    <div class="d-flex align-items-center border-bottom pb-4 px-4 text-start">
                        <div class="image image-circle image-tiny me-3 flex-shrink-0 mb-0">
                            <img src="{{ getLoggedInUser()->avatar }}" class="img-fluid" alt="profile image" style="width: 44px; height: 44px; object-fit: cover; border-radius: 50%;">
                        </div>
                        <div class="overflow-hidden ms-2">
                            <h3 class="text-gray-900 fs-6 fw-bold mb-0 text-truncate">{{ getLoggedInUser()->full_name }}</h3>
                            <h4 class="mb-0 fw-400 fs-7 text-muted text-truncate">{{ getLoggedInUser()->email }}</h4>
                        </div>
                    </div>
                    <ul class="pt-4 pe-0">
                        <li>
                            <a class="dropdown-item text-gray-900 editAdminProfileModal {{ checkLanguageSession() == 'ar' ? 'text-end' : '' }}" href="javascript:void(0)"
                               data-id="{{ getLoggedInUserId() }}">
                            <span class="dropdown-icon {{ checkLanguageSession() == 'ar' ? 'ms-4' : 'me-4' }} text-gray-600">
                                <i class="fa fa-user"></i>
                            </span>
                                {{ __('messages.user.edit_profile') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-gray-900 changeAdminPasswordModal {{ checkLanguageSession() == 'ar' ? 'text-end' : '' }}" href="javascript:void(0)"
                               data-id="{{ getLoggedInUserId() }}">
                            <span class="dropdown-icon {{ checkLanguageSession() == 'ar' ? 'ms-4' : 'me-4' }} text-gray-600">
                                <i class="fa fa-lock"></i>
                            </span>
                                {{ (Str::limit(__('messages.user.change_password'),20,'...')) }}
                            </a>
                        </li>
                        {{-- <li>
                            <a class="dropdown-item text-gray-900 changeAdminLanguageModal {{ checkLanguageSession() == 'ar' ? 'text-end' : '' }}" id="changeLanguage"
                               href="javascript:void(0)"
                               data-id="{{ getLoggedInUserId() }}">
                               <span class="dropdown-icon {{ checkLanguageSession() == 'ar' ? 'ms-4' : 'me-4' }} text-gray-600">
                                   <i class="fa fa-language"></i>
                               </span>
                                {{ (Str::limit(__('messages.user_language.change_language'),20,'...')) }}
                            </a>
                        </li> --}}
                        <li>
                            <a class="dropdown-item text-gray-900 d-flex {{ checkLanguageSession() == 'ar' ? 'text-end' : '' }}" href="javascript:void(0)">
                                <span class="dropdown-icon {{ checkLanguageSession() == 'ar' ? 'ms-4' : 'me-4' }} text-gray-600">
                                    <i class="fas fa-sign-out-alt"></i>
                                </span>
                                <form id="logout-form" action="{{url('/logout')}}" method="post">
                                    @csrf
                                </form>
                                <span onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();">
                                    {{ __('messages.user.logout') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
        <li>
            <button type="button" class="btn px-0 d-block d-xl-none header-btn pb-2">
                <i class="fa-solid fa-bars fs-1"></i>
            </button>
        </li>
    </ul>
</header>
<div class="bg-overlay" id="nav-overly"></div>
