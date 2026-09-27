<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionController extends Controller
{
    public function index(): View
    {
        $permissionNames = collect(config('admin_permissions.permissions', []));
        $permissions = Permission::query()
            ->whereIn('name', $permissionNames)
            ->orderBy('name')
            ->get()
            ->sortBy(fn (Permission $permission) => $permissionNames->search($permission->name))
            ->values();

        $roles = Role::query()
            ->whereNotIn('name', ['Employer', 'Candidate'])
            ->with('permissions')
            ->orderByRaw("CASE WHEN name = 'Super Admin' THEN 0 ELSE 1 END")
            ->get();

        return view('roles_permissions.index', compact('roles', 'permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $allowedPermissions = config('admin_permissions.permissions', []);
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('roles', 'name')->where('guard_name', 'web'),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in($allowedPermissions)],
        ]);

        $role = Role::create([
            'name' => trim($validated['name']),
            'guard_name' => 'web',
        ]);

        $permissions = collect($validated['permissions'] ?? [])
            ->push('admin.dashboard')
            ->unique()
            ->values()
            ->all();

        $role->syncPermissions($permissions);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('roles-permissions.index')
            ->with('role_permission_success', "{$role->name} role created successfully.");
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        abort_if($role->name === 'Super Admin', 403, 'Super Admin permissions cannot be changed.');
        abort_if(in_array($role->name, ['Employer', 'Candidate'], true), 403);

        $allowedPermissions = config('admin_permissions.permissions', []);
        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in($allowedPermissions)],
        ]);

        $permissions = collect($validated['permissions'] ?? [])
            ->push('admin.dashboard')
            ->unique()
            ->values()
            ->all();

        $role->syncPermissions($permissions);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('roles-permissions.index')
            ->with('role_permission_success', "{$role->name} role permissions updated successfully.");
    }
}
