<?php

namespace App\Livewire;

use App\Repositories\DashboardRepository;
use Livewire\Component;

class EmployerDashboard extends Component
{
    public $data;

    public function mount(DashboardRepository $dashboardRepository): void
    {
        $this->data = $dashboardRepository->getEmployerDashboardData();
    }

    public function placeholder()
    {
        return view('livewire_lazy_load.employer_dashboard');
    }

    public function render()
    {
        return view('livewire.employer-dashboard');
    }
}
