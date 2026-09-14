<?php

namespace App\Livewire;

use App\Repositories\DashboardRepository;
use Livewire\Component;

class AdminDashboardTable extends Component
{

    public $registerCandidatesData;
    public $registerEmployersData;
    public $recentJobsData;

    public function mount(DashboardRepository $dashboardRepository)
    {
        $this->registerCandidatesData = $dashboardRepository->getRegisteredCandidatesData();
        $this->registerEmployersData = $dashboardRepository->getRegisteredEmployersData();
        $this->recentJobsData = $dashboardRepository->getRecentJobsData();
    }

    public function placeholder()
    {
        return view('livewire_lazy_load.admin_dashboard_table');
    }
    public function render()
    {
        return view('livewire.admin-dashboard-table');
    }
}
