@extends('layouts.app')

@section('title', 'Roles & Permissions')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header border-0 pt-5 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="card-title fw-bold mb-1">Roles &amp; Permissions</h2>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                <i class="fas fa-plus me-1"></i> Add Role
            </button>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-5">
                @foreach ($roles as $role)
                    @php
                        $isSuperAdmin = $role->name === 'Super Admin';
                    @endphp
                    <div class="col-xl-6">
                        <div class="border rounded p-5 h-100">
                            <div class="d-flex align-items-start justify-content-between mb-4">
                                <div>
                                    <h3 class="mb-1">{{ $role->name }}</h3>
                                    <span class="text-muted">
                                        {{ $isSuperAdmin ? 'Full system access; permissions are locked.' : 'Permissions apply to every user with this role.' }}
                                    </span>
                                </div>
                                <span class="badge {{ $isSuperAdmin ? 'bg-danger' : 'bg-primary' }}">
                                    {{ $isSuperAdmin ? 'Protected' : 'Editable' }}
                                </span>
                            </div>

                            <form action="{{ $isSuperAdmin ? '#' : route('roles-permissions.update', $role) }}" method="POST">
                                @csrf
                                @if (! $isSuperAdmin)
                                    @method('PUT')
                                @endif
                                <div class="row g-3">
                                    @foreach ($permissions as $permission)
                                        @php
                                            $checked = $isSuperAdmin || $role->hasPermissionTo($permission);
                                            $label = str($permission->name)
                                                ->after('admin.')
                                                ->replace('_', ' ')
                                                ->title();
                                        @endphp
                                        <div class="col-md-6">
                                            <label class="permission-option border rounded w-100">
                                                <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]"
                                                       value="{{ $permission->name }}"
                                                       {{ $checked ? 'checked' : '' }}
                                                       {{ $isSuperAdmin ? 'disabled' : '' }}>
                                                <span>{{ $label }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                @if (! $isSuperAdmin)
                                    <div class="text-end mt-5">
                                        <button type="submit" class="btn btn-primary">Save Permissions</button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="alert alert-info mt-5 mb-0">
                Employer and Candidate are frontend account roles and are intentionally not managed from this admin-permission page.
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('roles-permissions.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title" id="addRoleModalLabel">Add New Role</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-5">
                        <label for="roleName" class="form-label required">Role Name</label>
                        <input type="text" id="roleName" name="name" value="{{ old('name') }}"
                               class="form-control" maxlength="100" required placeholder="Example: Content Manager">
                    </div>
                    <label class="form-label">Permissions</label>
                    <div class="row g-3">
                        @foreach ($permissions as $permission)
                            @php
                                $label = str($permission->name)->after('admin.')->replace('_', ' ')->title();
                                $isDashboard = $permission->name === 'admin.dashboard';
                            @endphp
                            <div class="col-md-6">
                                <label class="permission-option border rounded w-100">
                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                           name="permissions[]" value="{{ $permission->name }}"
                                           {{ $isDashboard || in_array($permission->name, old('permissions', []), true) ? 'checked' : '' }}
                                           {{ $isDashboard ? 'disabled' : '' }}>
                                    @if ($isDashboard)
                                        <input type="hidden" name="permissions[]" value="admin.dashboard">
                                    @endif
                                    <span>{{ $label }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-text mt-3">Dashboard access is automatically included with every admin role.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .permission-option {
        display: flex;
        align-items: center;
        gap: .75rem;
        min-height: 48px;
        padding: .75rem 1rem;
        cursor: pointer;
    }

    .permission-option .permission-checkbox {
        float: none;
        flex: 0 0 auto;
        margin: 0;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const message = @json(session('role_permission_success'));
    if (message && typeof window.displaySuccessMessage === 'function') {
        window.displaySuccessMessage(message);
    }

    @if ($errors->any() && old('name'))
        const addRoleModal = document.getElementById('addRoleModal');
        if (addRoleModal && window.bootstrap) {
            bootstrap.Modal.getOrCreateInstance(addRoleModal).show();
        }
    @endif
});
</script>
@endsection
