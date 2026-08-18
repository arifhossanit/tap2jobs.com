<script id="scheduleSlotBookHtmlTemplate" type="text/x-jsrender">
    <label for="{{:index}}" class="slot-card-item w-100 p-3 mb-3 border rounded-3 bg-white shadow-sm cursor-pointer d-block">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2 text-dark fw-bold fs-6">
                <i class="fa-regular fa-calendar-check fs-5 text-primary"></i>
                <span>{{:schedule_date}} &bull; {{:schedule_time}}</span>
            </div>
            <div class="slot-radio-wrap d-flex align-items-center mb-0">
                <input type="radio" name="slot_book" data-schedule="{{:schedule_id}}" id="{{:index}}" class="form-check-input slot-book me-2 cursor-pointer" value="<?php echo \App\Models\JobApplicationSchedule::STATUS_SEND ?>">
            </div>
        </div>
    </label>
</script>

<script id="chooseSlotHistoryHtmlTemplate" type="text/x-jsrender">
    <div class="history-card-item border rounded-3 p-3 mb-3 bg-white shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary text-white rounded px-2 py-1 fs-7 fw-semibold">
                    <i class="fa-solid fa-layer-group me-1"></i>{{:stageName}}
                </span>
            </div>
            {{if slotDateTime}}
                <span class="text-muted fs-7 fw-semibold">
                    <i class="fa-regular fa-calendar-check me-1 text-primary"></i>{{:slotDateTime}}
                </span>
            {{/if}}
        </div>
        {{if notes}}
            <div class="text-gray-700 fs-7 ps-3 border-start border-2 border-primary ms-1 mt-2">
                {{:notes}}
            </div>
        {{/if}}
    </div>
</script>

<script id="selectedSlotBookHtmlTemplate" type="text/x-jsrender">
    <div class="slot-card-item selected">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center gap-2 text-success fw-bold fs-6">
                <i class="fa-solid fa-circle-check fs-5"></i>
                <span>{{:schedule_date}} &bull; {{:schedule_time}}</span>
            </div>
            <span class="badge bg-success text-white fw-bold px-3 py-2 rounded fs-7">Confirmed Slot</span>
        </div>
        <div class="text-success text-opacity-85 fs-6 ps-1">
            <i class="fa-regular fa-message me-1"></i> {{:notes}}
        </div>
    </div>
</script>
