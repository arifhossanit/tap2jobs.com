<div class="row mainJobNotification">
    <div class="form-group col-xl-3 col-md-3 col-sm-12 select-candidate-width">
        {{ Form::label('candidate_id', __('messages.front_home.candidates').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{Form::select('candidate_id[]',$candidates, null, ['class' => 'form-select status-filter select2-hidden-accessible data-allow-clear="true"','id'=>'candidateId','data-control'=>'select2','multiple'=>true,'required', 'data-placeholder'=> __('messages.candidate.select_candidate') ])}}

        <div class="my-5">
            {{--            <label>{{__('messages.job_notification.select_all_jobs')}}: </label>--}}
            {{--            <input type="checkbox" class="form-group ml-2 notification_select_all" id="ckbCheckAll">--}}
            <div class="form-check custom-checkbox">
                <input type="checkbox" id="ckbCheckAll"
                       class="form-check-input notification_select_all {{ checkLanguageSession() == 'ar' ? 'float-end ms-5' : '' }}"
                       value="">
                <label class="form-check-label {{ checkLanguageSession() == 'ar' ? ' me-3' : '' }}" for="ckbCheckAll">
                    {{__('messages.job_notification.select_all_jobs')}}
                </label>
            </div>
        </div>
    </div>

    <div class="form-group col-xl-9 col-md-9 col-sm-12">
        <ul class="list-unstyled job-notification-ul ml-5">
            @include('job_notification.job_list')
        </ul>
    </div>

    <!-- Submit Field -->
    <div class="d-flex justify-content-end">
        {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-3','name' => 'save', 'id' => 'saveJobNotification']) }}
        <a href="{{ route('job-notification.index') }}"
           class="btn btn-secondary me-2">{{__('messages.common.cancel')}}</a>
    </div>
</div>

