<div class="container-fluid">
    <div class="d-flex flex-column">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="row">
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href="{{ route('candidates.index') }}" class=" text-decoration-none">
                            <div
                                class="bg-primary shadow-md rounded-10  px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                                <div
                                    class="bg-cyan-300 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-users fs-1-xl text-white"></i>
                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">
                                        {{ numberFormatShort($dashboardData['totalCandidates']) }}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.admin_dashboard.total_candidates') }}
                                    </h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href="{{ route('company.index') }}" class=" text-decoration-none">
                            <div
                                class="bg-success shadow-md rounded-10  px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                                <div
                                    class="bg-green-300 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-user-shield fs-1-xl text-white"></i>
                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">
                                        {{ numberFormatShort($dashboardData['totalEmployers']) }}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.admin_dashboard.total_employers') }}
                                    </h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href="{{ route('admin.jobs.index') }}" class=" text-decoration-none">
                            <div
                                class="bg-info shadow-md rounded-10  px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                                <div
                                    class="bg-blue-300 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-list-alt fs-1-xl text-white"></i>
                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">
                                        {{ numberFormatShort($dashboardData['totalActiveJobs']) }}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">
                                        {{ __('messages.admin_dashboard.total_active_jobs') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href="{{ route('posts.index') }}" class=" text-decoration-none">
                            <div
                                class="bg-warning shadow-md rounded-10  px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                                <div
                                    class="bg-yellow-300 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-blog fs-1-xl text-white"></i>
                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">
                                        {{ numberFormatShort($dashboardData['totalBlogs']) }}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.admin_dashboard.blogs') }}
                                    </h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href="{{ route('admin.jobs.index') }}" class=" text-decoration-none">
                            <div
                                class="bg-secondary shadow-md rounded-10  px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                                <div
                                    class="bg-gray-600 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-briefcase fs-1-xl text-white"></i>
                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">
                                        {{ numberFormatShort($dashboardData['newJobs']) }}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">
                                        {{ __('messages.admin_dashboard.new_jobs_7_days') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href="{{ route('admin.PendingJobs.index') }}" class="text-decoration-none">
                            <div class="bg-warning shadow-md rounded-10 px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                                <div class="bg-yellow-300 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-hourglass-half fs-1-xl text-white"></i>
                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">{{ numberFormatShort($dashboardData['pendingJobs']) }}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.pending_jobs.pending_jobs') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href="{{ route('admin.jobs.expireIn7DaysJobs') }}" class="text-decoration-none">
                            <div class="bg-danger shadow-md rounded-10 px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                                <div class="bg-red-300 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-calendar-xmark fs-1-xl text-white"></i>
                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">{{ numberFormatShort($dashboardData['expiringJobs']) }}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.admin_dashboard.expire_in_7_days') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <div class="bg-primary shadow-md rounded-10 px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                            <div class="bg-cyan-300 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-user-clock fs-1-xl text-white"></i>
                            </div>
                            <div class="text-end text-white">
                                <h2 class="fs-1-xxl fw-bolder text-white">{{ numberFormatShort($dashboardData['weeklyUsers']) }}</h2>
                                <h3 class="mb-0 fs-4 fw-light">{{ __('messages.admin_dashboard.weekly_users') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
