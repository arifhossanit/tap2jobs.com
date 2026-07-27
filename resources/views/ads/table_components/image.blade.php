<div class="d-flex align-items-center">
    @if (!empty($row->ad_image_url))
        <img src="{{ $row->ad_image_url }}" alt="{{ $row->title }}" class="img-fluid"
             style="max-height: 50px; max-width: 120px; object-fit: contain;">
    @else
        <span class="text-muted">—</span>
    @endif
</div>
