@if($row->is_created_by_admin == 1)
    <span class="badge bg-light-primary">Admin</span>
@else
    <span class="badge bg-light-info">Employer</span>
@endif
