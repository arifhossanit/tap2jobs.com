@php
    $statusClass = match ($row->media_processing_status) {
        \App\Models\Ad::MEDIA_STATUS_PROCESSING => 'bg-light-warning text-warning',
        \App\Models\Ad::MEDIA_STATUS_FAILED => 'bg-light-danger text-danger',
        default => 'bg-light-success text-success',
    };

    $statusLabel = __('messages.ad.media_statuses.'.($row->media_processing_status ?: \App\Models\Ad::MEDIA_STATUS_READY));
@endphp

<span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
@if ($row->media_processing_status === \App\Models\Ad::MEDIA_STATUS_FAILED && $row->media_processing_error)
    <span class="ms-1 text-danger" data-bs-toggle="tooltip"
          data-bs-original-title="{{ $row->media_processing_error }}">
        <i class="fa-solid fa-circle-info"></i>
    </span>
@endif
