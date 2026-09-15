<div class="d-flex align-items-center gap-3">
    @if($this->type === \App\Models\ProfileReferenceOption::TYPE_CONSULTATION_TYPE)
        <button type="button" class="consultation-type-drag-handle btn btn-sm p-1 text-muted" title="Long press and drag to reorder" aria-label="Long press and drag to reorder">
            <i class="fa-solid fa-grip-vertical fs-4"></i>
        </button>
    @endif
    <span>{{ $row->label }}</span>
</div>
