<div class="d-flex justify-content-center">
    @if ($row->is_active == 0)
        <label class="form-check form-switch form-switch-sm justify-content-center">
            <input type="checkbox" name="Is Active" class="form-check-input isActiveAd" data-id="{{ $row->id }}">
        </label>
    @else
        <label class="form-check form-switch form-switch-sm justify-content-center">
            <input type="checkbox" name="Is Active" class="form-check-input isActiveAd" data-id="{{ $row->id }}"
                   checked>
        </label>
    @endif
</div>
