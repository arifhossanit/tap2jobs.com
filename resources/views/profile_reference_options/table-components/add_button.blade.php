@php
    $profileReferenceScope = $scope ?? $component->scope ?? null;
    $profileReferenceType = $type ?? $component->type ?? null;
    $dedicatedRouteName = \App\Models\ProfileReferenceOption::dedicatedRouteName($profileReferenceScope, $profileReferenceType);
    $sampleFiles = [
        \App\Models\ProfileReferenceOption::TYPE_GENDER => 'gender-options-sample.csv',
        \App\Models\ProfileReferenceOption::TYPE_LANGUAGE_PROFICIENCY => 'language-proficiency-options-sample.csv',
        \App\Models\ProfileReferenceOption::TYPE_ONLINE_PROFILE_PLATFORM => 'online-profile-platform-options-sample.csv',
    ];
    $sampleFile = $sampleFiles[$profileReferenceType] ?? 'profile-reference-options-sample.csv';
    $hiddenImportTypes = [
        \App\Models\ProfileReferenceOption::TYPE_RELIGION,
        \App\Models\ProfileReferenceOption::TYPE_BLOOD_GROUP,
        \App\Models\ProfileReferenceOption::TYPE_DISABILITY_DIFFICULTY,
        \App\Models\ProfileReferenceOption::TYPE_SKILL_LEARNING_SOURCE,
        \App\Models\ProfileReferenceOption::TYPE_EDUCATION_RESULT,
        \App\Models\ProfileReferenceOption::TYPE_ARMY_BA_NO_PREFIX,
        \App\Models\ProfileReferenceOption::TYPE_ARMY_RANK,
        \App\Models\ProfileReferenceOption::TYPE_ARMY_EMPLOYMENT_TYPE,
        \App\Models\ProfileReferenceOption::TYPE_ARMY_ARMS,
    ];
    $hideCandidateReferenceRelationImport = $profileReferenceScope === \App\Models\ProfileReferenceOption::SCOPE_CANDIDATE
        && $profileReferenceType === \App\Models\ProfileReferenceOption::TYPE_REFERENCE_RELATION;
    $hideEmployerReferenceImport = $profileReferenceScope === \App\Models\ProfileReferenceOption::SCOPE_EMPLOYER;
@endphp
<div class="d-flex align-items-center gap-2 py-1">
    {{-- Selected candidate/employer reference import buttons intentionally hidden. --}}
    @if (! in_array($profileReferenceType, $hiddenImportTypes, true) && ! $hideCandidateReferenceRelationImport && ! $hideEmployerReferenceImport)
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ $dedicatedRouteName ? route($dedicatedRouteName.'.import') : route('profileReferenceOptions.import', [$profileReferenceScope, $profileReferenceType]) }}"
            data-sample-file="{{ asset('sample-imports/'.$sampleFile) }}">
        Import
    </button>
    @endif
    <a type="button" class="btn btn-primary addProfileReferenceOptionModal">
        {{ __('messages.common.add') }}
    </a>
</div>
