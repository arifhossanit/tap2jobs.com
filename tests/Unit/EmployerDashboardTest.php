<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class EmployerDashboardTest extends TestCase
{
    public function test_dashboard_uses_contact_person_name_and_null_safe_user_name(): void
    {
        $view = file_get_contents(resource_path('views/employer/dashboard/index.blade.php'));
        $user = new User([
            'first_name' => 'Shimzo',
            'last_name' => null,
        ]);

        $this->assertStringContainsString('$dashboardEmployer->company?->contact_person_name', $view);
        $this->assertStringContainsString('{{ $dashboardEmployerName }}', $view);
        $this->assertSame('Shimzo', $user->full_name);
    }

    public function test_chart_uses_valid_date_bounds_and_returns_the_total(): void
    {
        $repository = file_get_contents(app_path('Repositories/DashboardRepository.php'));
        $script = file_get_contents(resource_path('assets/js/employer/dashboard.js'));

        $this->assertStringContainsString('$dateS->copy()->startOfDay()', $repository);
        $this->assertStringContainsString('$dateE->copy()->endOfDay()', $repository);
        $this->assertStringNotContainsString("\$dateE.' 23:59:59'", $repository);
        $this->assertStringContainsString("\$data['totalJobApplication'] = array_sum(\$jobApplicationData);", $repository);
        $this->assertStringContainsString('data.totalJobApplication === 0', $script);
        $this->assertStringContainsString('append(\'<canvas id="employerDashboardChart"></canvas>\')', $script);
        $this->assertStringNotContainsString('return true;', $script);
    }

    public function test_each_dashboard_layer_only_loads_the_data_it_uses(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/DashboardController.php'));
        $stats = file_get_contents(app_path('Livewire/EmployerDashboard.php'));
        $tables = file_get_contents(app_path('Livewire/EmployerDashboardTable.php'));
        $tableView = file_get_contents(resource_path('views/livewire/employer-dashboard-table.blade.php'));

        $dashboardMethod = substr(
            $controller,
            strpos($controller, 'public function employerDashboard(): View'),
            strpos($controller, 'public function employerDashboardChart')
                - strpos($controller, 'public function employerDashboard(): View')
        );

        $this->assertStringNotContainsString('getEmployerDashboardData()', $dashboardMethod);
        $this->assertStringNotContainsString('getEmployerRecentJobsData()', $dashboardMethod);
        $this->assertStringContainsString('getEmployerDashboardData()', $stats);
        $this->assertStringNotContainsString('getEmployerRecentJobsData()', $stats);
        $this->assertStringContainsString('getEmployerRecentJobsData()', $tables);
        $this->assertStringContainsString('getEmployerRecentFollowerData()', $tables);
        $this->assertStringNotContainsString('getEmployerDashboardData()', $tables);
        $this->assertSame(2, substr_count($tableView, "__('messages.employer_menu.no_data_available')"));
    }
}
