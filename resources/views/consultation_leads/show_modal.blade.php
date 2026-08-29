<div class="modal fade" id="showConsultationLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Consultation Lead</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.common.close') }}"></button>
            </div>
            <form id="consultationLeadUpdateForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="consultationLeadId">
                <div class="modal-body">
                    <div class="row gy-3">
                        <div class="col-md-6"><strong>Name:</strong> <span id="consultationLeadName"></span></div>
                        <div class="col-md-6"><strong>Phone:</strong> <span id="consultationLeadPhone"></span></div>
                        <div class="col-md-6"><strong>Email:</strong> <span id="consultationLeadEmail"></span></div>
                        <div class="col-md-6"><strong>Company:</strong> <span id="consultationLeadCompany"></span></div>
                        <div class="col-md-6"><strong>Designation:</strong> <span id="consultationLeadDesignation"></span></div>
                        <div class="col-md-6"><strong>Website:</strong> <span id="consultationLeadWebsite"></span></div>
                        <div class="col-md-6"><strong>Company Size:</strong> <span id="consultationLeadSize"></span></div>
                        <div class="col-md-6"><strong>Category:</strong> <span id="consultationLeadCategory"></span></div>
                        <div class="col-md-6"><strong>Type:</strong> <span id="consultationLeadType"></span></div>
                        <div class="col-md-6"><strong>Contact Method:</strong> <span id="consultationLeadContactMethod"></span></div>
                        <div class="col-md-6"><strong>Contact Time:</strong> <span id="consultationLeadContactTime"></span></div>
                        <div class="col-md-6"><strong>Source Ad:</strong> <span id="consultationLeadAd"></span></div>
                        <div class="col-12"><strong>Source Page:</strong> <span id="consultationLeadSource"></span></div>
                        <div class="col-12"><strong>UTM:</strong> <span id="consultationLeadUtm"></span></div>
                        <div class="col-12"><strong>Requirement:</strong> <div id="consultationLeadMessage" class="mt-1 text-gray"></div></div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" id="consultationLeadStatus" class="form-select">
                                @foreach (\App\Models\ConsultationLead::STATUSES as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Admin Notes</label>
                            <textarea name="admin_notes" id="consultationLeadNotes" rows="4" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="consultationLeadBtnSave">{{ __('messages.common.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
