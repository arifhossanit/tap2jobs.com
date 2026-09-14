@extends('employer.layouts.app')
@section('title')
    {{ __('messages.job_applications') }}
@endsection
@section('content')
        <div class="d-flex flex-column ">
            @include('flash::message')
            <livewire:job-application-table :job-id="$jobId"/>
            @include('employer.job_applications.job_stages_modal')
        </div>
        {{Form::hidden('jobApplicationData',true,['id'=>'indexJobApplicationData'])}}
        {{Form::hidden('changeJobStage', route('change.job.stage', ['jobId' => $jobId]), ['id'=>'changeJobStage'])}}
        {{Form::hidden('statusArray',json_encode($statusArray),['id'=>'employerJobStatusArray'])}}
        {{Form::hidden('removeApplicationTitle', __('messages.common.remove'), ['id'=>'removeApplicationTitle'])}}
        {{Form::hidden('removeApplicationConfirmation', __('messages.common.remove_application_confirmation'), ['id'=>'removeApplicationConfirmation'])}}
        {{Form::hidden('removeApplicationSuccessTitle', __('messages.common.success'), ['id'=>'removeApplicationSuccessTitle'])}}
@endsection
{{--@push('scripts')--}}
{{--    <script>--}}
{{--        var statusArray = JSON.parse('@json($statusArray)');--}}
{{--    </script>--}}
{{--@endpush--}}

