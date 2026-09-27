<?php

namespace Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;

class AdminPermissionRouteCoverageTest extends TestCase
{
    public function test_every_protected_admin_route_is_assigned_to_a_permission(): void
    {
        $unassigned = [];

        foreach (app('router')->getRoutes() as $route) {
            if (! in_array('admin.permission', $route->gatherMiddleware(), true)) {
                continue;
            }

            $path = Str::after($route->uri(), 'admin/');
            $assigned = collect(config('admin_permissions.route_groups'))
                ->flatten()
                ->contains(fn (string $pattern) => Str::is($pattern, $path));

            if (! $assigned) {
                $unassigned[] = implode('|', $route->methods()).' '.$route->uri();
            }
        }

        $this->assertSame([], $unassigned, 'Unassigned admin routes: '.implode(', ', $unassigned));
    }
}
