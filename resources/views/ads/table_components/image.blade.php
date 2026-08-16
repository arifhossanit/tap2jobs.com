<div class="d-flex align-items-center">
    @if (!empty($row->ad_media_url))
        @if ($row->ad_media_type === 'video')
            <video src="{{ $row->ad_media_url }}" class="rounded"
                   style="max-height: 50px; max-width: 120px; object-fit: contain;" muted preload="metadata"></video>
            <span class="badge bg-light-primary text-primary ms-2">Video</span>
        @else
            <img src="{{ $row->ad_media_url }}" alt="{{ $row->title }}" class="img-fluid"
                 style="max-height: 50px; max-width: 120px; object-fit: contain;">
        @endif
    @else
        <span class="text-muted">—</span>
    @endif
</div>
