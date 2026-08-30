<div class="d-flex justify-content-center">
    <label class="form-check form-switch form-switch-sm">
        <input type="checkbox" name="status" class="form-check-input jobCategoryStatus" data-id="{{ $row->id }}"
               {{ (int) $row->status === \App\Models\JobCategory::STATUS_ACTIVE ? 'checked' : '' }}>
    </label>
</div>
