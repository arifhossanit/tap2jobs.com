@extends('employer.layouts.app')
@section('title')
    {{ __('messages.job_stage.slots') }}
@endsection
@section('content')
    @include('flash::message')
        <div class="d-flex flex-column">
            @include('layouts.errors')
            @if(isset($jobApplication) && $jobApplication->candidate)
                <div class="card mb-4 shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $jobApplication->candidate->user->avatar }}" alt="{{ $jobApplication->candidate->user->full_name }}" 
                                     class="rounded-circle object-fit-cover shadow-sm" style="width: 54px; height: 54px;">
                                <div>
                                    <h4 class="mb-1 fw-bold text-dark fs-5">{{ $jobApplication->candidate->user->full_name }}</h4>
                                    <div class="d-flex flex-wrap align-items-center gap-3 text-muted fs-7">
                                        <span><i class="fa-solid fa-briefcase text-primary me-1"></i> {{ $jobApplication->job->job_title }}</span>
                                        <span><i class="fa-solid fa-envelope text-primary me-1"></i> {{ $jobApplication->candidate->user->email }}</span>
                                        @if($jobApplication->candidate->user->phone)
                                            <span><i class="fa-solid fa-phone text-primary me-1"></i> {{ $jobApplication->candidate->user->phone }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('job-applications', ['jobId' => $jobApplication->job_id]) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                    <i class="fa-solid fa-arrow-left me-1"></i> {{ __('messages.common.back') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end">
                        @php
                            $stageId = isset($lastStage) ? $lastStage->stage_id : ($jobApplication->job_stage_id ?? null);
                        @endphp
                        @if($jobStage->isNotEmpty())
                            <div class="w-25">
                                {{ Form::select('stage_id', $jobStage, $stageId, ['id' => 'stages', 'class' => 'form-select status-filter w-100']) }}
                            </div>
                        @endif
                        @if($isSelectedRejectedSlot > 0 || $isStageMatch)
                            <div class="d-flex align-items-center me-4 me-md-5 form-btn schedule-interview">
                                <a href="javascript:void(0)"
                                   class="btn btn-primary addJobStageModal ms-2">
                                    {{ __('messages.common.add') }}
                                </a>
                            </div>
                        @endif
                    </div>
                    <hr>
                    @livewire('view-slot-screen',['applicationId'=>$applicationId, 'stageId'=>$stageId])
                </div>
            </div>
            @include('employer.job_applications.schedule_interview_modal')
{{--            @include('employer.job_applications.templates.templates')--}}
            @include('employer.job_applications.add_batch_slot_modal')
            @include('employer.job_applications.edit_batch_slot_modal')
        </div>
        {{Form::hidden('indexEmployerJobSlot',true,['id'=>'indexEmployerJobSlot'])}}
@endsection
@push('scripts')
    <script>
        var interviewSlotStoreUrl = "{{ route('interview.slot.store', ['jobId' => request()->route('jobId')]) }}";
        var batchSlotStoreUrl = "{{ route('batch.slot.store', ['jobId' => request()->route('jobId')]) }}";
        var uniqueId = 1;
        var JobApplicationId = "{{ request()->route('jobApplicationId') }}";
        var getScheduleHistory = "{{ route('get.schedule.history', ['jobId' => request()->route('jobId')]) }}";
        var cancelSlotUrl = "{{ route('cancel.selected.slot', ['jobId' => request()->route('jobId')]) }}";
        var jobApplicationUrl = "{{url('employer/jobs/'.request()->route('jobId').'/applications')}}";
    </script>
    {{--    <script src="{{ asset('assets/js/job_applications/job_slots.js') }}"></script>--}}
@endpush

