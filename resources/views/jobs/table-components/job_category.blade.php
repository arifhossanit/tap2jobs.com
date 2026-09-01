<div class="d-flex">
    @if($row->jobCategory)
        <span class="badge bg-light-primary">{{ Str::limit($row->jobCategory->name, 30) }}</span>
    @else
        <span class="badge bg-secondary">-</span>
    @endif
</div>