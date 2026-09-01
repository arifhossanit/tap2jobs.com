<div class="row">
    @forelse($jobs as $job)
        <div class="col-lg-12 px-lg-3">
            <div class="job-card">

                <div class="mb-4">
                    <a href="{{ route('front.job.details', $job['job_id']) }}" class="card p-3 p-sm-4 border-0 shadow-sm text-decoration-none">
                        <div class="d-flex gap-3 gap-sm-4 justify-content-between position-relative">
                            <div class="flex-grow-1 min-w-0">
                                <div class="card-body p-0">
                                    <h5 class="card-title text-secondary fs-18 mb-0">
                                        {{ html_entity_decode(Str::limit($job['job_title'], 50)) }}
                                    </h5>
                                    <div>
                                        <div class="card-desc d-flex flex-wrap mt-2">
                                            <div class="desc job-card-meta d-flex align-items-center {{ getFrontSelectLanguage() == 'ar' ? 'ms-3 ms-sm-4' : 'me-3 me-sm-4' }}">
                                              <div class="{{ getFrontSelectLanguage() == 'ar' ? 'ms-2' : 'me-2' }} job-card-meta-icon flex-shrink-0">
                                                <img src="{{ asset('img_template/briefcase.svg') }}" class="w-100">
                                              </div>
                                              <p class="fs-14 text-gray mb-0">{{ $job->jobCategory?->name ?? '' }}</p>
                                            </div>
                                            <div class="desc job-card-meta d-flex align-items-center {{ getFrontSelectLanguage() == 'ar' ? 'ms-3 ms-sm-4' : 'me-3 me-sm-4' }}">
                                              <div class="{{ getFrontSelectLanguage() == 'ar' ? 'ms-2' : 'me-2' }} job-card-meta-icon flex-shrink-0">
                                                <img src=" {{ asset('img_template/clock.svg') }}" class="w-100">
                                              </div>
                                              <p class="fs-14 text-gray mb-0">{{ $job->created_at->diffForHumans() }}</p>
                                            </div>
                                          </div>
                                    </div>
                                    <div class="desc d-flex align-items-center flex-wrap mt-3">
                                        <p class="text text-primary fs-14 mb-0 {{ getFrontSelectLanguage() == 'ar' ? 'ms-3' : 'me-3' }}">
                                            {{ !empty($job->jobsSkill[0]->name) ? $job->jobsSkill[0]->name : 'Skill' }}
                                        </p>

                                         @if(!empty($job->jobShift?->shift))
                                             <span class="text text-primary fs-14 mb-0">
                                                 {{ $job->jobShift->shift }}
                                             </span>
                                         @endif
                                    </div>
                                </div>
                            </div>
                            
                            @php
                                $logoUrl = $job->company?->company_url;
                                $hasLogo = $logoUrl && !str_contains($logoUrl, 'infyom-logo.png') && !str_contains($logoUrl, 'employer-image.png');
                            @endphp
                            
                            @if($hasLogo)
                                <div class="flex-shrink-0">
                                    <img src="{{ $logoUrl }}" class="card-img job-card-company-logo" alt="...">
                                </div>
                            @endif
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
