<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = config('auth.defaults.guard', 'web');
        $roles = collect(['Super Admin', 'Admin', 'Employer', 'Candidate'])
            ->mapWithKeys(fn (string $name) => [
                $name => Role::firstOrCreate(['name' => $name, 'guard_name' => $guard]),
            ]);

        $permissions = collect(config('admin_permissions.permissions', []))
            ->map(fn (string $name) => Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => $guard,
            ]));

        $roles['Super Admin']->syncPermissions($permissions);
        $roles['Admin']->syncPermissions(config('admin_permissions.admin_permissions', []));

        $superAdmin = User::query()->where('email', 'admin@gmail.com')->first();
        if ($superAdmin) {
            // Keep Admin for compatibility with legacy checks while adding the real role.
            $superAdmin->syncRoles([$roles['Super Admin'], $roles['Admin']]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
