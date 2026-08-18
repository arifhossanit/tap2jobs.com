@php
    $isDefaultResume = (bool) $row->getCustomProperty('is_default', false);
    $isApplicationCv = (bool) $row->getCustomProperty(\App\Services\ApplicationCvService::APPLICATION_CV_PROPERTY, false);
    $resumeTitle = $isApplicationCv
        ? \App\Services\ApplicationCvService::TITLE
        : $row->getCustomProperty('title', $row->name);
@endphp

<div class="py-1 text-dark font-weight-semibold">
    {{ $resumeTitle }}
</div>
