<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DefaultRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
            ],
            [
                'name' => 'Admin',
            ],
            [
                'name' => 'Employer',
            ],
            [
                'name' => 'Candidate',
            ],
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate($role + ['guard_name' => 'web']);
        }
        /** @var Role $adminRole */
        $adminRole = Role::whereName('Admin')->first();
        $superAdminRole = Role::whereName('Super Admin')->first();

        /** @var User $user */
        $user = User::whereEmail('admin@gmail.com')->first();
        if ($user) {
            $user->syncRoles([$superAdminRole, $adminRole]);
        }

        $this->call(RolePermissionSeeder::class);
    }
}
