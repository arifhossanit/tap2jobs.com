<div>
    @if (session()->has('message'))
        <div class="alert alert-success mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="row g-4 align-items-stretch">
        @forelse ($favouriteJobs as $favouriteJob)
            @php
                $job = $favouriteJob->job;
                $isExpired = !empty($job->job_expiry_date) && \Carbon\Carbon::parse($job->job_expiry_date) < \Carbon\Carbon::now();
            @endphp
            <div class="col-12 col-md-6 col-xl-6">
                <div class="card border-0 shadow-sm h-100 favourite-job-card">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            {{-- Header: Company logo / Job title & remove action --}}
                            <div class="d-flex align-items-start justify-content-between mb-3 gap-2">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <img src="{{ $job->company->user->avatar }}"
                                         alt="{{ $job->company->user->first_name }}"
                                         class="rounded-circle object-fit-cover flex-shrink-0"
                                         style="width: 44px; height: 44px;">
                                    <div class="min-w-0">
                                        <h5 class="mb-1 text-truncate">
                                            <a href="{{ route('front.job.details', $job->job_id) }}" target="_blank"
                                               class="text-dark text-hover-primary text-decoration-none fw-semibold fs-6">
                                                {{ !empty($job->job_title) ? \Illuminate\Support\Str::limit($job->job_title, 35, '...') : __('messages.n/a') }}
                                            </a>
                                        </h5>
                                        <span class="text-gray-600 fs-7 d-block text-truncate">
                                            {{ $job->company->user->first_name }}
                                        </span>
                                    </div>
                                </div>
                                <button type="button"
                                        class="removeJob btn px-1 text-danger fs-5 flex-shrink-0"
                                        data-id="{{ $favouriteJob->id }}"
                                        data-bs-toggle="tooltip"
                                        title="{{ __('messages.job.remove_favourite_jobs') }}">
                                    <i class="fas fa-bookmark"></i>
                                </button>
                            </div>

                            {{-- Divider --}}
                            <hr class="text-gray-200 my-3">

                            {{-- Info list --}}
                            <div class="d-flex flex-column gap-2 text-gray-700 fs-7">
                                @if (!empty($job->full_location))
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <span class="text-gray-600 me-2">
                                            <i class="fas fa-map-marker-alt me-2 text-success"></i>{{ __('web.job_menu.location') }}:
                                        </span>
                                        <span class="fw-semibold text-gray-800 text-end">
                                            {{ \Illuminate\Support\Str::limit($job->full_location, 45, '...') }}
                                        </span>
                                    </div>
                                @endif
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <span class="text-gray-600 me-2">
                                        <i class="far fa-clock me-2 text-primary"></i>{{ __('web.job_details.date_posted') }}:
                                    </span>
                                    <span class="fw-semibold text-gray-800">
                                        {{ $job->created_at->translatedFormat('dS M, Y') }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <span class="text-gray-600 me-2">
                                        <i class="far fa-calendar-times me-2 {{ $isExpired ? 'text-danger' : 'text-info' }}"></i>{{ __('messages.job.expires_on') }}:
                                    </span>
                                    <span class="fw-semibold {{ $isExpired ? 'text-danger' : 'text-gray-800' }}">
                                        {{ $job->job_expiry_date->format('d M, Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 d-flex justify-content-center my-9 job-titile">
                <h5>{{ __('messages.job.no_favourite_job_found') }}</h5>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center my-4">
        @if ($favouriteJobs->count() > 0)
            {{ $favouriteJobs->links() }}
        @endif
    </div>
</div>
