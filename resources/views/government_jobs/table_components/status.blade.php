<span class="badge bg-light-{{ $row->is_published ? 'success' : 'secondary' }} text-{{ $row->is_published ? 'success' : 'secondary' }}">
    {{ $row->is_published ? 'Published' : 'Draft' }}
</span>
