@php
    $profileReferenceScope = $scope ?? $component->scope ?? null;
    $profileReferenceType = $type ?? $component->type ?? null;
    $dedicatedRouteName = \App\Models\ProfileReferenceOption::dedicatedRouteName($profileReferenceScope, $profileReferenceType);
@endphp
<div class="d-flex align-items-center gap-2 py-1">
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ $dedicatedRouteName ? route($dedicatedRouteName.'.import') : route('profileReferenceOptions.import', [$profileReferenceScope, $profileReferenceType]) }}"
            data-sample-file="{{ asset('sample-imports/profile-reference-options-sample.csv') }}">
        Import
    </button>
    <a type="button" class="btn btn-primary addProfileReferenceOptionModal">
        {{ __('messages.common.add') }}
    </a>
</div>
