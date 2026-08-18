<div class="modal fade" id="scheduleSlotBookModal" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0 rounded-1">
            <div class="modal-header border-bottom border-gray-200 px-4 py-3 bg-light">
                <h3 class="modal-title fw-bold text-dark fs-5 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-primary"></i>
                    {{__('messages.job_stage.choose_slots')}}
                </h3>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'scheduleSlotBookForm']) }}
                <div class="modal-body p-4">
                    <div class="alert-slot-msg alert-danger d-none rounded-3 p-3 mb-3"
                         id="scheduleSlotBookValidationErrorsBox"></div>
                    <div class="alert-slot-msg alert-success d-none rounded-3 p-3 mb-3"
                         id="selectedSlotBookValidationErrorsBox"></div>
                    <div class="slot-main-div">

                    </div>
                    <div class="choose-slot-textarea d-none mt-3">
                        <label class="form-label fw-bold text-gray-800 mb-2">
                            <i class="fa-regular fa-comment-dots me-1 text-primary"></i> {{ __('messages.job.notes') }}
                        </label>
                        <textarea name="choose_slot_notes" class="form-control rounded-1 p-3 border-gray-300" required
                                  placeholder="{{__('messages.flash.enter_notes')}}" rows="3"></textarea>
                    </div>
                    <div id="historyMainDiv" class="d-none mt-4 pt-3 border-top border-gray-200">
                        <h4 class="fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-muted"></i>
                            {{ __('messages.job_stage.history') }}
                        </h4>
                        <div id="historyDiv" class="scroll-history-div">

                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-gray-200 px-4 py-3 bg-light d-flex flex-wrap align-items-center justify-content-end gap-2">
                    {{ Form::button('<i class="fa-solid fa-paper-plane me-1"></i> '.__('messages.job_stage.send_slots'), ['type'=>'submit','class' => 'btn btn-primary px-4 rounded-3 fw-bold','id'=>'scheduleInterviewBtnSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                    <button type="submit" value="" class="btn btn-danger rejectSlot px-4 rounded-3 fw-bold" id="rejectSlotBtnSave"
                            name="rejectSlot"><i class="fa-solid fa-ban me-1"></i> {{__('messages.job_stage.reject_all_slot')}}
                    </button>
                    <button id="scheduleInterviewBtnCancel" type="button" class="btn btn-outline-secondary px-4 rounded-3 fw-bold"
                            data-bs-dismiss="modal">{{ __('messages.common.cancel') }}
                    </button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
