<?php

namespace App\Livewire;

use App\Repositories\DashboardRepository;
use Livewire\Component;

class AdminDashboard extends Component
{
    public $dashboardData;

    public function mount(DashboardRepository $dashboardRepository)
    {
        $this->dashboardData = $dashboardRepository->getDashboardAssociatedData();
    }

    public function placeholder()
    {
        return view('livewire_lazy_load.admin_dashboard');
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
