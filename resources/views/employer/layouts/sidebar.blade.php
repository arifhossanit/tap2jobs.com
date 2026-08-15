<div class="px-2 px-xl-0 d-flex align-items-stretch flex-grow-1">
    <ul class="horizontal-menu navbar-nav d-flex justify-content-end align-items-xl-center w-100">
        <li class="nav-item {{ Request::is('employer/dashboard*') ? 'active' : ''}}">
            <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
               href="{{ route('employer.dashboard') }}">
                {{ __('messages.dashboard') }}
            </a>
        </li>
        <li class="nav-item {{ \Illuminate\Support\Facades\Route::is('company.edit.form') ? 'active' : ''}}">
            <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
               href="{{ route('company.edit.form', \Illuminate\Support\Facades\Auth::user()->owner_id) }}">
                {{ __('messages.employer_menu.employer_profile') }}
            </a>
        </li>
        <li class="nav-item {{ Request::is('employer/jobs*') ? 'active' : ''}}">
            <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
               href="{{ route('job.index') }}">
                {{ __('messages.employer_menu.jobs') }}
            </a>
        </li>
        <li class="nav-item {{ Request::is('employer/job-stage*') ? 'active' : ''}}">
            <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
               href="{{ route('job.stage.index') }}">
                {{ __('messages.job_stages') }}
            </a>
        </li>
        <li class="nav-item {{ Request::is('employer/followers*') ? 'active' : ''}}">
            <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
               href="{{ route('followers.index') }}">
                {{ __('messages.employer_menu.followers') }}
            </a>
        </li>
        <!-- <li class="nav-item d-none d-xl-grid dropdown dropdown-hover {{ Request::is('employer/transactions*','employer/manage-subscription*') ? 'active' : '' }}">
            <a class="nav-link header-navbar-color text-gray py-3 ps-2 dropdown-toggle" aria-current="page"
               href="javascript:void(0)">
                {{ __('web.more') }}
            </a>
            <ul class="horizontal-submenu dropdown-menu top-100">
                <li>
                    <a class="dropdown-item {{ Request::is('employer/transaction*') ? 'active' : '' }} {{ checkLanguageSession() == 'ar' ? 'text-end' : 'text-start' }}"
                       href="{{ route('transactions.index') }}">
                        {{ __('messages.employer_menu.transactions') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ Request::is('employer/manage-subscription*') ? 'active' : '' }} {{ checkLanguageSession() == 'ar' ? 'text-end' : 'text-start' }}"
                       href="{{ route('manage-subscription.index') }}">
                        {{ __('messages.employer_menu.manage_subscriptions') }}
                    </a>
                </li>
            </ul>
        </li> -->

        {{-- start side bar menu for bar--}}
        <li class="nav-item d-xl-none {{ Request::is('employer/transaction*') ? 'active' : ''}}">
            <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
               href="{{ route('transactions.index') }}">
                {{ __('messages.employer_menu.transactions') }}
            </a>
        </li>
        <li class="nav-item d-xl-none {{ Request::is('employer/manage-subscription*') ? 'active' : ''}}">
            <a class="nav-link header-navbar-color text-gray py-3" aria-current="page"
               href="{{ route('manage-subscription.index') }}">
                {{ __('messages.employer_menu.manage_subscriptions') }}
            </a>
        </li>
        {{-- end side bar menu for bar--}}
    </ul>
</div>
