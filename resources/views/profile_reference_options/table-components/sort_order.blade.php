<div class="d-flex align-items-center justify-content-center gap-2">
    <span class="badge bg-light-primary text-primary fs-6 min-w-30px">
        {{ $row->sort_order }}
    </span>
    <div class="btn-group btn-group-sm" role="group" aria-label="Change sort order">
        <button type="button"
                class="btn btn-light-primary px-2"
                title="Move up"
                wire:click="moveSortOrder({{ $row->id }}, 'up')"
                wire:loading.attr="disabled">
            <i class="fa-solid fa-chevron-up"></i>
        </button>
        <button type="button"
                class="btn btn-light-primary px-2"
                title="Move down"
                wire:click="moveSortOrder({{ $row->id }}, 'down')"
                wire:loading.attr="disabled">
            <i class="fa-solid fa-chevron-down"></i>
        </button>
    </div>
</div>
