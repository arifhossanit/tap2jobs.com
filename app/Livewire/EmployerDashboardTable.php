<?php

namespace App\Livewire;

use App\Repositories\DashboardRepository;
use Livewire\Component;

class EmployerDashboardTable extends Component
{
    public $recentJobs;
    public $recentFollowers;

    public function mount(DashboardRepository $dashboardRepository): void
    {
        $this->recentJobs = $dashboardRepository->getEmployerRecentJobsData();
        $this->recentFollowers = $dashboardRepository->getEmployerRecentFollowerData();
    }

    public function placeholder()
    {
        return view('livewire_lazy_load.employer_dashboard_table');
    }

    public function render()
    {
        return view('livewire.employer-dashboard-table');
    }
}
