@php
    $isDefaultResume = (bool) $row->getCustomProperty('is_default', false);
    $isApplicationCv = (bool) $row->getCustomProperty(\App\Services\ApplicationCvService::APPLICATION_CV_PROPERTY, false);
    $resumeTitle = $isApplicationCv
        ? \App\Services\ApplicationCvService::TITLE
        : $row->getCustomProperty('title', $row->name);
@endphp

<div class="py-2 text-primary">
    {{ $resumeTitle }}
    @if($isApplicationCv)
        <span class="badge bg-light-info text-info ms-2">{{ __('messages.candidate_profile.generated') }}</span>
    @endif
    @if($isDefaultResume)
        <span class="badge bg-light-primary text-primary ms-2">{{ __('messages.candidate_profile.selected') }}</span>
    @endif
</div>
