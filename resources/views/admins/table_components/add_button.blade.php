@can('admin.manage_admins')
<div class="menu-item">
    <a href="{{ route('admin.create') }}" type="button" class="btn btn-primary">
        {{ __('messages.common.add') }}
    </a>
</div>
@endcan
