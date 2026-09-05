<div class="d-flex flex-wrap gap-1">
    @forelse($row->selected_job_categories as $category)
        <span class="badge bg-light-primary">{{ Str::limit($category->name, 30) }}</span>
    @empty
        <span class="badge bg-secondary">-</span>
    @endforelse
</div>