<div class="row">
    @forelse($jobs as $job)
        <div class="col-lg-12 px-lg-3">
            <div class="job-card">

                <div class="mb-4">
                    <a href="{{ route('front.job.details', $job['job_id']) }}" class="card p-4 border-0 shadow-sm">
                        <div class="d-sm-flex gap-4 align-items-center position-relative">
                            <div class="mb-sm-0 mb-3 flex-shrink-0">
                                <img src="{{ $job->company?->company_url ?? asset('assets/img/infyom-logo.png') }}" class="card-img" alt="..." style="width: 80px; height: 80px; object-fit: contain;">
                            </div>
                            <div class="flex-grow-1">
                                <div class="card-body p-0">
                                    <h5 class="card-title text-secondary fs-18 mb-0">
                                        {{ html_entity_decode(Str::limit($job['job_title'], 50)) }}
                                    </h5>
                                    <div class="">
                                        <div class="card-desc d-flex flex-wrap mt-2 ">
                                            <div class="desc d-flex {{ getFrontSelectLanguage() == 'ar' ? 'ms-4' : 'me-4' }}">
                                              <div class="{{ getFrontSelectLanguage() == 'ar' ? 'ms-3' : 'me-3' }} w-20">
                                                <img src="{{ asset('img_template/briefcase.svg') }}" class="w-100">
                                              </div>
                                              <p class="fs-14 text-gray mb-2">{{ $job->jobCategory?->name ?? '' }}</p>
                                            </div>
                                            <div class="desc d-flex {{ getFrontSelectLanguage() == 'ar' ? 'ms-4' : 'me-4' }}">
                                              <div class="{{ getFrontSelectLanguage() == 'ar' ? 'ms-3' : 'me-3' }} w-20">
                                                <img src="{{ asset('img_template/location.svg') }}" class="w-100">
                                              </div>
                                              <p class="fs-14 text-gray mb-2">{{ !empty($job->full_location) ? $job->full_location : 'Location Info. not available.' }}</p>
                                            </div>
                                            <div class="desc d-flex {{ getFrontSelectLanguage() == 'ar' ? 'ms-4' : 'me-4' }}">
                                              <div class="{{ getFrontSelectLanguage() == 'ar' ? 'ms-3' : 'me-3' }} w-20">
                                                <img src=" {{ asset('img_template/clock.svg') }}" class="w-100">
                                              </div>
                                              <p class="fs-14 text-gray mb-2">{{ $job->created_at->diffForHumans() }}</p>
                                            </div>
                                          </div>
                                    </div>
                                    <div class="desc d-flex">
                                        <p class="text text-primary fs-14 mb-0 {{ getFrontSelectLanguage() == 'ar' ? 'ms-3' : 'me-3' }}">
                                            {{ !empty($job->jobsSkill[0]->name) ? $job->jobsSkill[0]->name : 'Skill' }}
                                        </p>

                                        {{-- <p class="fs-14 text text-primary mb-0">
                                            {{ $job->jobsSkill->count() }}+
                                        </p> --}}

                                         @if(!empty($job->jobShift?->shift))
                                             <span class="text text-primary fs-14 mb-0">
                                                 {{ $job->jobShift->shift }}
                                             </span>
                                         @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    @empty
        <div class="col-md-12 text-center text-gray">
            @lang('web.job_menu.no_results_found')
        </div>
    @endforelse
    @if($jobs->hasPages())
        {{$jobs->links() }}
    @endif
</div>
