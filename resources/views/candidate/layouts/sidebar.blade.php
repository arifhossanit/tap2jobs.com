<div class="px-2 px-xl-0 d-flex align-items-stretch flex-grow-1">
    <ul class="horizontal-menu navbar-nav d-flex justify-content-end align-items-xl-center w-100">
        <li class="nav-item {{ Request::is('candidate/dashboard*') ? 'active' : ''}}">
            <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
               href="{{ route('dashboard') }}">
                {{ __('messages.candidate.dashboard') }}
            </a>
        </li>
        <li class="nav-item {{ Request::is('search-jobs*') ? 'active' : '' }}">
            <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
               href="{{ route('front.search.jobs') }}">
                {{ __('web.web_home.find_jobs') }}
            </a>
        </li>
        {{-- <li class="nav-item {{ Request::is('candidate/profile*') ? 'active' : ''}}">
            <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
               href="{{ route('candidate.profile') }}">
                {{ __('messages.profile') }}
            </a>
        </li> --}}
        <li class="nav-item {{ Request::is('candidate/favourite-jobs*') ? 'active' : ''}}">
            <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
               href="{{ route('favourite.jobs') }}">
                {{ __('messages.favourite_jobs') }}
            </a>
        </li>
        <li class="nav-item {{ Request::is('candidate/favourite-companies*') ? 'active' : ''}}">
            <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
               href="{{ route('favourite.companies') }}">
                {{ __('messages.favourite_companies') }}
            </a>
        </li>
        @if(getCurrentLanguageCode() == 'de' || getCurrentLanguageCode() == 'tr' || getCurrentLanguageCode() == 'pt' || getCurrentLanguageCode() == 'ru' || getCurrentLanguageCode() == 'es' || getCurrentLanguageCode() == 'fr')
            <li class="nav-item d-none d-xl-grid dropdown dropdown-hover {{ Request::is('candidate/applied-jobs*','candidate/job-alerts*') ? 'active' : '' }}">
                <button class="nav-link header-navbar-color text-gray py-3 ps-2 border-0 bg-transparent dropdown-toggle"
                        type="button" aria-label="More navigation">
                    {{ __('web.more') }}
                </button>
                <ul class="horizontal-submenu dropdown-menu top-100">
                    <li>
                        <a class="dropdown-item {{ Request::is('candidate/applied-jobs*') ? 'active' : '' }}"
                           href="{{ route('candidate.applied.job') }}">
                            {{ __('messages.applied_job.applied_jobs') }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ Request::is('candidate/job-alerts*') ? 'active' : '' }}"
                           href="{{ route('candidate.job.alert') }}">
                            {{ __('messages.job.job_alert') }}
                        </a>
                    </li>
                </ul>
            </li>

            {{-- start side bar menu for bar--}}
            <li class="nav-item d-xl-none {{ Request::is('candidate/applied-jobs*') ? 'active' : ''}}">
                <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
                   href="{{ route('candidate.applied.job') }}">
                    {{ __('messages.applied_job.applied_jobs') }}
                </a>
            </li>
            <li class="nav-item d-xl-none {{ Request::is('candidate/job-alerts*') ? 'active' : ''}}">
                <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
                   href="{{ route('candidate.job.alert') }}">
                    {{ __('messages.job.job_alert') }}
                </a>
            </li>
            {{-- end side bar menu for bar--}}
        @else
            <li class="nav-item {{ Request::is('candidate/applied-jobs*') ? 'active' : ''}}">
                <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
                   href="{{ route('candidate.applied.job') }}">
                    {{ __('messages.applied_job.applied_jobs') }}
                </a>
            </li>
            <li class="nav-item {{ Request::is('candidate/job-alerts*') ? 'active' : ''}}">
                <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
                   href="{{ route('candidate.job.alert') }}">
                    {{ __('messages.job.job_alert') }}
                </a>
            </li>
        @endif
    </ul>
</div>
