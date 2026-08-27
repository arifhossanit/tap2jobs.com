<div class="candidate-applied-jobs col-12">
{{--    @if(session()->has('message'))--}}
{{--        <div class="alert alert-success">--}}
{{--            {{ session('message') }}--}}
{{--        </div>  --}}
{{--    @endif--}}
    {{-- @if(count($appliedJobs) > 0 || $searchByAppliedJob != '' || $jobApplicationStatus != '') --}}
        <div class="candidate-applied-jobs__toolbar row g-3 mb-4 justify-content-start">
            <div class="col-12 col-md-6 col-lg-4" wire:ignore>
                {{ Form::select('job-application-status', $jobApplicationStatusArr, $value, ['class' => 'form-control','id'=>'jobApplicationStatus','placeholder' => __('messages.common.all'), 'wire:model' => "jobApplicationStatus"]) }}
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <input wire:model.debounce.100ms.live="searchByAppliedJob" type="search"
                       id="searchByAppliedJob"
                       placeholder="{{ __('web.job_menu.search_applied_job') }}"
                       class="form-control search-box-placeholder">
            </div>
        </div>
    {{-- @endif --}}
    @if(count($appliedJobs) > 0)
        <div class="candidate-applied-jobs__content content1">
            <div class="candidate-applied-jobs__grid row g-4 position-relative">
                @foreach($appliedJobs as $appliedJob)
                   <div class="col-12 col-md-6">
                       <div class="candidate-applied-job-card card shadow-sm h-100">
                           <div class="card-body p-4 d-flex flex-column justify-content-between">
                               <div>
                                   @php
                                       $job = $appliedJob->job;
                                       $currencyIcon = $job->currency->currency_icon ?? '';
                                       $statusColor = \App\Models\JobApplication::STATUS_COLOR[$appliedJob->status] ?? 'primary';
                                   @endphp
                                   
                                   {{-- Header: Job Title, Status Badge & Action Menu --}}
                                   <div class="candidate-applied-job-card__header d-flex align-items-start justify-content-between mb-3 gap-2">
                                       <div class="candidate-applied-job-card__heading d-flex align-items-center gap-2 flex-wrap flex-grow-1">
                                           
                                           <div>
                                               <h5 class="candidate-applied-job-card__title mb-1" title="{{ !empty($job) ? $job->job_title : '' }}">
                                                   <a href="{{ !empty($job) ? route('front.job.details', $job->job_id) : 'javascript:void(0)' }}"
                                                      target="_blank" class="text-dark text-hover-primary text-decoration-none fw-bold fs-6">
                                                       {{ !empty($job) ? Str::limit($job->job_title, 25, '...') : __('messages.n/a') }}
                                                   </a>
                                               </h5>
                                               <span class="badge bg-light-{{ $statusColor }} text-{{ $statusColor }} fs-7 fw-semibold">
                                                   @if(\App\Models\JobApplication::STATUS[$appliedJob->status] == 'Drafted')
                                                       {{__('messages.common.drafted')}}
                                                   @elseif(\App\Models\JobApplication::STATUS[$appliedJob->status] == 'Applied')
                                                       {{__('messages.common.applied')}}
                                                   @elseif(\App\Models\JobApplication::STATUS[$appliedJob->status] == 'Declined')
                                                       {{__('messages.common.declined')}}
                                                   @elseif(\App\Models\JobApplication::STATUS[$appliedJob->status] == 'Hired')
                                                       {{__('messages.common.hired')}}
                                                   @else
                                                       {{__('messages.common.ongoing')}}
                                                   @endif
                                               </span>
                                           </div>
                                       </div>

                                       {{-- Action Dropdown --}}
                                       <div class="dropdown">
                                           <button type="button" title="{{__('messages.common.action')}}"
                                                   class="candidate-applied-job-card__action btn text-gray-600 border-0 p-0"
                                                   id="dropdownMenuButton{{ $appliedJob->id }}" data-bs-toggle="dropdown"
                                                   data-bs-boundary="viewport" aria-expanded="false">
                                               <i class="fa-solid fa-ellipsis-vertical fs-5"></i>
                                           </button>
                                           <ul class="dropdown-menu dropdown-menu-end shadow-sm border border-gray-300 min-width-50"
                                               aria-labelledby="dropdownMenuButton{{ $appliedJob->id }}">
                                               <li>
                                                   <a class="dropdown-item apply-job-note py-2"
                                                      href="javascript:void(0)"
                                                      data-id="{{ $appliedJob->id }}">
                                                       <i class="fa-regular fa-eye me-2 text-primary"></i>{{ __('messages.common.view') }}
                                                   </a>
                                               </li>
                                               @if(\App\Models\JobApplicationSchedule::whereJobApplicationId($appliedJob->id)->exists() && !($appliedJob->status == \App\Models\JobApplication::REJECTED) && !($appliedJob->status == \App\Models\JobApplication::STATUS_APPLIED) && !($appliedJob->status == \App\Models\JobApplication::COMPLETE))
                                                   <li>
                                                       <a class="dropdown-item schedule-slot-book py-2" href="javascript:void(0)" data-id="{{ $appliedJob->id }}">
                                                           <i class="fa-regular fa-calendar-check me-2 text-info"></i>{{ __('messages.job_stage.slots') }}
                                                       </a>
                                                   </li>
                                               @endif
                                               <li><hr class="dropdown-divider my-1"></li>
                                               <li>
                                                   <a class="dropdown-item delete-btn remove-applied-jobs text-danger py-2" href="javascript:void(0)" data-id="{{ $appliedJob->id }}">
                                                       <i class="fa-regular fa-trash-can me-2 text-danger"></i>{{ __('messages.common.delete') }}
                                                   </a>
                                               </li>
                                           </ul>
                                       </div>
                                   </div>

                                   {{-- Divider --}}
                                   <hr class="text-gray-200 my-3">

                                   {{-- Info List --}}
                                   <div class="d-flex flex-column gap-2 text-gray-700 fs-7">
                                       <div class="candidate-applied-job-card__info-row">
                                           <span class="text-gray-600 me-2">
                                               <i class="far fa-clock me-2 text-primary"></i>{{ __('messages.common.applied_on') }}:
                                           </span>
                                           <span class="candidate-applied-job-card__value fw-semibold text-gray-800">
                                               {{ (!empty($appliedJob->created_at)) ? \Carbon\Carbon::parse($appliedJob->created_at)->translatedFormat('dS M, Y') : __('messages.n/a') }}
                                           </span>
                                       </div>

                                       <div class="candidate-applied-job-card__info-row">
                                           <span class="text-gray-600 me-2">
                                               <i class="fas fa-money-check-alt me-2 text-success"></i>Expected Salary:
                                           </span>
                                           <span class="candidate-applied-job-card__value fw-semibold text-gray-800">
                                               {{ (!empty($appliedJob->expected_salary)) ? number_format($appliedJob->expected_salary) : __('messages.n/a') }} {{ $currencyIcon }}
                                           </span>
                                       </div>

                                       @isset($appliedJob->jobStage->name)
                                           <div class="candidate-applied-job-card__info-row">
                                               <span class="text-gray-600 me-2">
                                                   <i class="fab fa-usps me-2 text-info"></i>Stage:
                                               </span>
                                               <span class="candidate-applied-job-card__value fw-semibold text-gray-800 badge bg-light-info text-info">
                                                   {{ $appliedJob->jobStage->name }}
                                               </span>
                                           </div>
                                       @endisset
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
                @endforeach
            </div>
            <div class="candidate-applied-jobs__pagination d-flex justify-content-center my-4">
                @if($appliedJobs->count() > 0)
                    {{ $appliedJobs->links() }}
                @endif
            </div>
        </div>
    @else
        @if($searchByAppliedJob == null || empty($searchByAppliedJob))
        <div class="candidate-applied-jobs__empty col-12 d-flex justify-content-center my-9 job-titile">
            <h5>{{ __('messages.job.no_applied_job_found') }} </h5>
        </div>
        @else
        <div class="candidate-applied-jobs__empty col-12 d-flex justify-content-center my-9 job-titile">
            <h5>{{ __('messages.job.applies_job_not_found') }} </h5>
        </div>
        @endif
    @endif
</div>
