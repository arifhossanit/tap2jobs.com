{{ Form::open(['route' => $sectionName === 'privacy_policy' ? 'privacy.policy.update' : 'terms.conditions.update', 'id' => 'policyTerms']) }}
<div class="row">
    <div class="my-6">
        {{ Form::label($sectionName, __('messages.setting.' . $sectionName).':', ['class' => 'form-label']) }}
        <span class="required"></span>
        @if ($sectionName === 'privacy_policy')
            <x-text-editor id="addPrivacyPolicyDescriptionQuillData" name="privacy_policy" />
            {{ Form::hidden('privacy_policy', null, ['id' => 'privacyData']) }}
        @else
            <x-text-editor id="addTermConditionDescriptionQuillData" name="terms_conditions" />
            {{ Form::hidden('terms_conditions', null, ['id' => 'termData']) }}
        @endif
    </div>
</div>
<div class="d-flex justify-content-end">
    {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary']) }}
    </div>
{{ Form::close() }}
