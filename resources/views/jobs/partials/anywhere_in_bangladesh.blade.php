@php
    $anywhereInBangladesh = (bool) old('anywhere_in_bangladesh', isset($job) ? $job->anywhere_in_bangladesh : false);
@endphp

<input type="hidden" name="anywhere_in_bangladesh" value="0">
<div class="form-check form-check-custom form-check-solid mb-0">
    <input class="form-check-input" type="checkbox" name="anywhere_in_bangladesh"
           id="anywhereInBangladesh" value="1" @checked($anywhereInBangladesh)>
    <label class="form-check-label fw-semibold" for="anywhereInBangladesh">
        {{ __('messages.job.anywhere_in_bangladesh') }}
    </label>
</div>
